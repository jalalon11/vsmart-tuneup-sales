<?php

namespace App\Http\Controllers;

use App\Models\Repair;
use App\Models\Device;
use App\Models\Service;
use App\Models\Customer;
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

        // Handle search
        if ($request->has('search')) {
            $searchTerm = $request->search;
            $query->whereHas('items.device.customer', function($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%");
            })
            ->orWhereHas('items.device', function($q) use ($searchTerm) {
                $q->where('brand', 'like', "%{$searchTerm}%")
                  ->orWhere('model', 'like', "%{$searchTerm}%");
            })
            ->orWhereHas('items.service', function($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%");
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

        $repairs = $query->paginate(10)->withQueryString();
        return View::make('repairs.index', compact('repairs', 'sortField', 'sortDirection'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $customers = Customer::orderBy('name')->get();
        $services = Service::orderBy('name')->get();

        return view('repairs.create', compact('customers', 'services'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'items' => 'required|array|min:1',
            'items.*.device_id' => 'required|exists:devices,id',
            'items.*.service_id' => 'required|exists:services,id',
            'items.*.cost' => 'required|numeric|min:0',
            'items.*.notes' => 'nullable|string',
            'status' => 'required|in:pending,completed,cancelled',
            'started_at' => 'nullable|date',
            'completed_at' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        // Verify all devices belong to the selected customer
        $customer = Customer::findOrFail($validated['customer_id']);
        $customerDeviceIds = $customer->devices->pluck('id')->toArray();
        
        foreach ($validated['items'] as $item) {
            if (!in_array($item['device_id'], $customerDeviceIds)) {
                return back()->withInput()->withErrors(['device_id' => 'One or more devices do not belong to the selected customer.']);
            }
        }

        $repair = Repair::create([
            'status' => $validated['status'],
            'started_at' => $validated['started_at'],
            'completed_at' => $validated['completed_at'],
            'notes' => $validated['notes'],
        ]);

        foreach ($validated['items'] as $item) {
            $repair->items()->create([
                'device_id' => $item['device_id'],
                'service_id' => $item['service_id'],
                'cost' => $item['cost'],
                'notes' => $item['notes'],
            ]);
        }

        return redirect()->route('repairs.show', $repair)
            ->with('success', 'Repair has been created successfully.');
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
        $services = Service::all();
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
            'status' => ['required', 'in:pending,completed,cancelled'],
            'notes' => ['nullable', 'string'],
            'started_at' => ['nullable', 'date'],
            'completed_at' => ['nullable', 'date', 'after_or_equal:started_at'],
        ]);

        $oldStatus = $repair->status;

        // If status is being changed to completed and completed_at is not set
        if ($validated['status'] === 'completed' && 
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