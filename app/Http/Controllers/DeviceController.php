<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class DeviceController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'brand' => 'nullable|string|max:255',
            'model' => 'required|string|max:255',
            'customer_id' => 'required|exists:customers,id',
        ]);

        // Add default status
        $validated['status'] = 'received';

        $device = Device::create($validated);
        $customer = Customer::find($validated['customer_id']);

        return Redirect::route('customers.show', $customer)
            ->with('success', 'Device added successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Device $device)
    {
        $customer = $device->customer;

        if ($device->repairs()->exists()) {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete device with associated repairs.'
                ], 422);
            }
            return Redirect::route('customers.show', $customer)
                ->with('error', 'Cannot delete device with associated repairs.');
        }

        $device->delete();

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Device deleted successfully.'
            ]);
        }
        return Redirect::route('customers.show', $customer)
            ->with('success', 'Device deleted successfully.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Device $device)
    {
        $validated = $request->validate([
            'brand' => 'required|string|max:255',
            'model' => 'required|string|max:255',
        ]);

        $device->update($validated);

        // Refresh the device to get the latest data
        $device->refresh();
        
        // Load any relationships that might be needed in the UI
        $device->load('repairs');

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Device updated successfully',
                'device' => $device
            ]);
        }

        return redirect()->route('customers.show', $device->customer_id)
            ->with('success', 'Device updated successfully');
    }
} 