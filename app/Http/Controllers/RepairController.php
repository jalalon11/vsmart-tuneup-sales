<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Device;
use App\Models\Repair;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Redirect;
use Carbon\Carbon;

class RepairController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Repair::with(['items.device.customer', 'items.service']);
        
        // Handle customer_id and device_id parameters coming from device links
        $customerId = null;
        $deviceId = null;
        
        if ($request->has('customer_id') && $request->has('device_id')) {
            $customerId = $request->input('customer_id');
            $deviceId = $request->input('device_id');
            
            // Get customer and device information to display in the info message
            $customer = Customer::find($customerId);
            $device = Device::find($deviceId);
            
            if ($customer && $device) {
                // Pass info message
                session()->flash('info', "Viewing repairs for {$customer->name}'s {$device->brand} {$device->model}");
                
                // Filter repairs to show only those for this customer and device
                $query->whereHas('items', function($q) use ($deviceId) {
                    $q->where('device_id', $deviceId);
                });
            }
        }

        // Handle search
        if ($request->has('search')) {
            $searchTerm = $request->search;
            $query->where(function($query) use ($searchTerm) {
                $query->whereHas('items.device.customer', function($q) use ($searchTerm) {
                    $q->where('name', 'like', "%{$searchTerm}%");
                })
                ->orWhereHas('items.device', function($q) use ($searchTerm) {
                    $q->where('brand', 'like', "%{$searchTerm}%")
                      ->orWhere('model', 'like', "%{$searchTerm}%");
                })
                ->orWhereHas('items.service', function($q) use ($searchTerm) {
                    $q->where('name', 'like', "%{$searchTerm}%");
                })
                ->orWhere('status', 'like', "%{$searchTerm}%");
            });
        }

        // Handle sorting
        $sortField = $request->get('sort', 'created_at');
        $sortDirection = $request->get('direction', 'desc');

        // Define allowed sort fields
        $allowedSortFields = [
            'customer' => 'items.device.customer.name',
            'device' => 'items.device.brand',
            'service' => 'items.service.name',
            'status' => 'status',
            'created_at' => 'created_at'
        ];

        if (array_key_exists($sortField, $allowedSortFields)) {
            if (str_contains($allowedSortFields[$sortField], '.')) {
                // Handle relationship sorting through repair items
                $query->join('repair_items', 'repairs.id', '=', 'repair_items.repair_id')
                      ->join('devices', 'repair_items.device_id', '=', 'devices.id')
                      ->join('customers', 'devices.customer_id', '=', 'customers.id')
                      ->join('services', 'repair_items.service_id', '=', 'services.id')
                      ->orderBy($allowedSortFields[$sortField], $sortDirection)
                      ->select('repairs.*')
                      ->distinct();
            } else {
                $query->orderBy($allowedSortFields[$sortField], $sortDirection);
            }
        }

        $perPage = $request->get('perPage', 10);
        $repairs = $query->paginate($perPage)->withQueryString();
        $customers = Customer::orderBy('name')->get();
        $services = Service::active()->with('category')->orderBy('name')->get();
        
        return View::make('repairs.index', compact(
            'repairs', 
            'sortField', 
            'sortDirection', 
            'customers', 
            'services',
            'customerId',
            'deviceId'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Redirect to repairs index page since the create view has been removed
        return redirect()->route('repairs.index')->with('info', 'Repairs can only be created from the device list in the Customer Profile');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the basic repair data
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'items' => 'required|array|min:1',
            'items.*.device_id' => 'required|exists:devices,id',
            'items.*.service_id' => 'required|exists:services,id',
            'items.*.cost' => 'required|numeric|min:0',
            'items.*.status' => 'required|in:pending,in_progress,completed,cancelled',
            'items.*.notes' => 'nullable|string|max:500',
            'notes' => 'nullable|string|max:1000',
            'payment_method' => 'required|in:cash,gcash,bank_transfer,credit_card',
        ]);
        
        // Create the repair with status
        $repair = Repair::create([
            'status' => 'pending', // Set initial status
            'notes' => $request->notes,
            'started_at' => now(), // Set started_at to current time
            'payment_method' => $request->payment_method,
        ]);
        
        // Create repair items
        foreach ($request->items as $itemData) {
            $repair->items()->create([
                'device_id' => $itemData['device_id'],
                'service_id' => $itemData['service_id'],
                'cost' => $itemData['cost'],
                'notes' => $itemData['notes'] ?? null,
            ]);
        }
        
        // If the request is AJAX, return JSON
        if ($request->ajax() || $request->wantsJson()) {
            // Load the repair with its relationships for the JSON response
            $repair->load(['items.device.customer', 'items.service']);
            
            return response()->json([
                'success' => true,
                'message' => 'Repair created successfully!',
                'repair' => $repair
            ]);
        }
        
        // Normal redirect response
        return redirect()->route('repairs.index')
            ->with('success', 'Repair created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Repair $repair)
    {
        $repair->load(['items.device.customer', 'items.service']);
        return View::make('repairs.show', compact('repair'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Repair $repair)
    {
        $repair->load(['items.device.customer', 'items.service']);
        $devices = Device::with('customer')->get();
        $services = Service::active()->orderBy('name')->get();
        return View::make('repairs.edit', compact('repair', 'devices', 'services'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Repair $repair)
    {
        $validated = $request->validate([
            'items' => ['required', 'array', 'min:1'],
            'items.*.device_id' => ['required', 'exists:devices,id'],
            'items.*.service_id' => ['required', 'exists:services,id'],
            'items.*.cost' => ['required', 'numeric', 'min:0'],
            'items.*.notes' => ['nullable', 'string'],
            'status' => ['required', 'in:pending,in_progress,completed,cancelled'],
            'notes' => ['nullable', 'string'],
            'started_at' => ['nullable', 'date'],
            'completed_at' => ['nullable', 'date', 'after_or_equal:started_at'],
            'payment_method' => ['required', 'in:cash,gcash,bank_transfer,credit_card'],
        ]);

        $oldStatus = $repair->status;

        // Handle different status transitions
        if ($validated['status'] === 'in_progress' && $oldStatus !== 'in_progress') {
            // If changing to in_progress, set started_at to now if it's not already set
            if (empty($validated['started_at'])) {
                $validated['started_at'] = now();
            }
        }
        // If status is being changed to completed and completed_at is not set
        elseif ($validated['status'] === 'completed' && 
            $oldStatus !== 'completed' && 
            empty($validated['completed_at'])) {
            $validated['completed_at'] = now();
        }

        // Update the repair
        $repair->update([
            'status' => $validated['status'],
            'notes' => $validated['notes'],
            'started_at' => $validated['started_at'],
            'completed_at' => $validated['completed_at'],
            'payment_method' => $validated['payment_method'],
        ]);

        // Delete existing items and create new ones
        $repair->items()->delete();
        foreach ($validated['items'] as $item) {
            $repair->items()->create([
                'device_id' => $item['device_id'],
                'service_id' => $item['service_id'],
                'cost' => $item['cost'],
                'notes' => $item['notes'] ?? null,
            ]);
        }

        return Redirect::route('repairs.show', $repair)->with('success', 'Repair updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Repair $repair)
    {
        $repair->delete();
        return Redirect::route('repairs.index')->with('success', 'Repair deleted successfully.');
    }

    /**
     * Generate a receipt for a completed repair.
     */
    public function receipt(Repair $repair)
    {
        if ($repair->status !== 'completed') {
            return Redirect::back()->with('error', 'Receipt is only available for completed repairs.');
        }

        $repair->load(['items.device.customer', 'items.service']);
        return View::make('repairs.receipt', compact('repair'));
    }
} 