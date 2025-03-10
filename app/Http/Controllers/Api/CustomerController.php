<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Get all devices for a specific customer
     * 
     * @param int $customerId
     * @return \Illuminate\Http\JsonResponse
     */
    public function devices($customerId)
    {
        // Find the customer or return 404 response
        $customer = Customer::findOrFail($customerId);
        
        // Get all devices for this customer
        $devices = $customer->devices;
        
        return response()->json($devices);
    }
}
