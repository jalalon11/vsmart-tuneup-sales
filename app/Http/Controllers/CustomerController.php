<?php

namespace App\Http\Controllers;

use App\Http\Requests\CustomerRequest;
use App\Models\Customer;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\View as ViewFacade;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    public function index(): View
    {
        $query = Customer::query()
            ->withCount(['devices', 'devices as pending_repairs_count' => function ($query) {
                $query->whereHas('repairs', function ($q) {
                    $q->where('status', 'pending');
                });
            }]);

        // Handle search
        if (request()->has('search')) {
            $searchTerm = request('search');
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                    ->orWhere('phone', 'like', "%{$searchTerm}%")
                    ->orWhereHas('devices', function($q) use ($searchTerm) {
                        $q->where('brand', 'like', "%{$searchTerm}%")
                            ->orWhere('model', 'like', "%{$searchTerm}%");
                    });
            });
        }

        $customers = $query->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        // Add services for repair modal
        $services = \App\Models\Service::with('category')
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return ViewFacade::make('customers.index', compact('customers', 'services'));
    }

    public function create(): View
    {
        return ViewFacade::make('customers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'facebook_url' => 'nullable|url|max:255',
            'address' => 'nullable|string|max:1000',
        ]);

        $customer = Customer::create($validated);

        // Create device if brand or model is provided
        if ($request->filled('device_brand') || $request->filled('device_model')) {
            $customer->devices()->create([
                'brand' => $request->device_brand,
                'model' => $request->device_model,
                'status' => 'received',
            ]);
        }

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => "Customer '{$customer->name}' has been created successfully.",
                'customer' => $customer->load(['devices' => function($query) {
                    $query->withCount('repairs as pending_repairs_count')
                        ->whereHas('repairs', function($query) {
                            $query->whereNotIn('status', ['completed', 'delivered']);
                        });
                }])->loadCount(['devices', 'devices as pending_repairs_count' => function($query) {
                    $query->whereHas('repairs', function($query) {
                        $query->whereNotIn('status', ['completed', 'delivered']);
                    });
                }])
            ]);
        }

        return Redirect::route('customers.show', $customer)
            ->with('success', "Customer '{$customer->name}' has been created successfully.");
    }

    public function show(Customer $customer): mixed
    {
        if (request()->ajax()) {
            return response()->json([
                'customer' => $customer->load(['devices' => function($query) {
                    $query->with(['deviceModel', 'repairs' => function($q) {
                        $q->select('repairs.*')
                          ->latest('repairs.created_at')
                          ->with('items.service');
                    }])
                    ->latest('devices.created_at');
                }])
            ]);
        }

        $devices = $customer->devices()
            ->with(['deviceModel', 'repairs' => function($q) {
                $q->select('repairs.*')
                  ->latest('repairs.created_at')
                  ->with('items.service');
            }])
            ->latest('devices.created_at')
            ->paginate(10);

        return ViewFacade::make('customers.show', compact('customer', 'devices'));
    }

    public function edit(Customer $customer): mixed
    {
        if (request()->ajax()) {
            return response()->json([
                'customer' => $customer
            ]);
        }

        return ViewFacade::make('customers.edit', compact('customer'));
    }

    public function update(CustomerRequest $request, Customer $customer): mixed
    {
        $customer->update($request->validated());

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => "Customer '{$customer->name}' has been updated successfully."
            ]);
        }

        return Redirect::route('customers.index')
            ->with('success', "Customer '{$customer->name}' has been updated successfully.");
    }

    public function destroy(Customer $customer): RedirectResponse
    {
        try {
            // Begin transaction
            DB::beginTransaction();

            // Delete all associated devices
            $customer->devices()->delete();

            // Delete the customer
            $customer->delete();

            // Commit transaction
            DB::commit();

            return Redirect::route('customers.index')
                ->with('success', "Customer '{$customer->name}' and all associated devices have been deleted successfully.");
        } catch (\Exception $e) {
            // Rollback transaction if something goes wrong
            DB::rollback();
            
            return Redirect::back()
                ->with('error', 'An error occurred while deleting the customer. Please try again.');
        }
    }

    public function devices(Customer $customer)
    {
        try {
            Log::info('Devices request received', [
                'customer_id' => $customer->id,
                'customer_name' => $customer->name,
                'auth_check' => Auth::check(),
                'user_id' => Auth::id()
            ]);

            $devices = $customer->devices()
                ->with(['deviceModel', 'customer'])
                ->get();

            Log::info('Devices fetched', [
                'customer_id' => $customer->id,
                'device_count' => $devices->count(),
                'devices' => $devices->map(fn($d) => [
                    'id' => $d->id,
                    'brand' => $d->brand,
                    'model' => $d->model
                ])
            ]);

            $formattedDevices = $devices->map(function ($device) {
                $deviceName = $device->deviceModel 
                    ? "{$device->deviceModel->brand} {$device->deviceModel->model}"
                    : "{$device->brand} {$device->model}";

                return [
                    'id' => $device->id,
                    'name' => $deviceName,
                    'brand' => $device->brand,
                    'model' => $device->model,
                    'customer_name' => $device->customer->name
                ];
            });

            return response()->json($formattedDevices);
        } catch (\Exception $e) {
            Log::error('Error in devices method', [
                'customer_id' => $customer->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'error' => 'Error loading devices',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function addDevice(Request $request, Customer $customer)
    {
        try {
            Log::info('Adding device request received', [
                'customer_id' => $customer->id,
                'request_data' => $request->all()
            ]);

            $validated = $request->validate([
                'brand' => 'required|string|max:255',
                'model' => 'required|string|max:255',
                'serial_number' => 'nullable|string|max:255'
            ]);

            $device = $customer->devices()->create([
                'brand' => $validated['brand'],
                'model' => $validated['model'],
                'serial_number' => $validated['serial_number'] ?? null,
                'status' => 'received'
            ]);

            Log::info('Device created successfully', [
                'device_id' => $device->id,
                'customer_id' => $customer->id
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Device added successfully',
                    'device' => $device->load(['repairs' => function($query) {
                        $query->select('repairs.id', 'repairs.status', 'repairs.created_at')
                            ->latest('repairs.created_at');
                    }])
                ]);
            }

            return redirect()->back()->with('success', 'Device added successfully');
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation error while adding device', [
                'customer_id' => $customer->id,
                'errors' => $e->errors()
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'message' => 'The given data was invalid.',
                    'errors' => $e->errors()
                ], 422);
            }

            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Error adding device', [
                'customer_id' => $customer->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'message' => 'Error adding device: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()->with('error', 'Error adding device');
        }
    }

    public function getDevices(Customer $customer)
    {
        try {
            Log::info('Fetching devices for customer', [
                'customer_id' => $customer->id,
                'customer_name' => $customer->name
            ]);

            $devices = $customer->devices()
                ->with(['repairs' => function($query) {
                    $query->select('repairs.id', 'repairs.status', 'repairs.created_at')
                        ->latest('repairs.created_at');
                }])
                ->get()
                ->map(function($device) {
                    // Get the latest repair status
                    $latestRepair = $device->repairs->first();
                    $status = $latestRepair ? $latestRepair->status : 'no_repairs';
                    
                    return [
                        'id' => $device->id,
                        'brand' => $device->brand,
                        'model' => $device->model,
                        'status' => $status,
                        'pending_repairs_count' => $device->repairs->where('status', 'pending')->count(),
                        'in_progress_repairs_count' => $device->repairs->where('status', 'in_progress')->count(),
                        'completed_repairs_count' => $device->repairs->where('status', 'completed')->count()
                    ];
                });

            Log::info('Successfully fetched devices', [
                'customer_id' => $customer->id,
                'device_count' => $devices->count()
            ]);

            return response()->json($devices);
        } catch (\Exception $e) {
            Log::error('Error in getDevices method', [
                'customer_id' => $customer->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'error' => 'Error loading devices',
                'message' => $e->getMessage()
            ], 500);
        }
    }
} 