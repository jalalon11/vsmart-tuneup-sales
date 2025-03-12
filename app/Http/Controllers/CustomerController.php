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
            }])
            ->with(['devices' => function ($query) {
                $query->with(['deviceModel'])
                    ->latest();
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
                'customer' => $customer->load('devices')
            ]);
        }

        $devices = $customer->devices()
            ->with(['repairs' => function ($query) {
                $query->latest();
            }])
            ->latest()
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
        $validated = $request->validate([
            'brand' => 'required|string|max:255',
            'model' => 'required|string|max:255',
        ]);

        $device = $customer->devices()->create([
            'brand' => $validated['brand'],
            'model' => $validated['model'],
            'status' => 'received',
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Device added successfully',
                'device' => $device
            ]);
        }

        return redirect()->back()->with('success', 'Device added successfully');
    }

    public function getDevices(Customer $customer)
    {
        return response()->json($customer->devices);
    }
} 