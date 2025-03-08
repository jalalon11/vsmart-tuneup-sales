<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Redirect;

class InventoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = Inventory::query();

        // Handle search
        if (request()->has('search')) {
            $searchTerm = request('search');
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                    ->orWhere('brand', 'like', "%{$searchTerm}%")
                    ->orWhere('model', 'like', "%{$searchTerm}%")
                    ->orWhere('serial_number', 'like', "%{$searchTerm}%");
            });
        }

        $items = $query->latest()->paginate(10);
        $totalItems = Inventory::count();
        $lowStockItems = Inventory::lowStock()->count();
        $outOfStockItems = Inventory::outOfStock()->count();

        return View::make('inventory.index', compact('items', 'totalItems', 'lowStockItems', 'outOfStockItems'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return View::make('inventory.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'brand' => 'nullable|string|max:255',
            'model' => 'nullable|string|max:255',
            'serial_number' => 'nullable|string|max:255|unique:inventories',
            'quantity' => 'required|integer|min:0',
            'unit_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'reorder_point' => 'required|integer|min:0',
        ]);

        $item = Inventory::create($validated);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => "Item '{$item->name}' has been added successfully.",
                'item' => $item
            ]);
        }

        return Redirect::route('inventory.index')
            ->with('success', "Item '{$item->name}' has been added successfully.");
    }

    /**
     * Display the specified resource.
     */
    public function show(Inventory $inventory)
    {
        if (request()->ajax()) {
            return response()->json([
                'item' => $inventory
            ]);
        }

        return View::make('inventory.show', compact('inventory'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Inventory $inventory)
    {
        if (request()->ajax()) {
            return response()->json([
                'item' => $inventory
            ]);
        }

        return View::make('inventory.edit', compact('inventory'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Inventory $inventory)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'brand' => 'nullable|string|max:255',
            'model' => 'nullable|string|max:255',
            'serial_number' => 'nullable|string|max:255|unique:inventories,serial_number,' . $inventory->id,
            'quantity' => 'required|integer|min:0',
            'unit_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'reorder_point' => 'required|integer|min:0',
        ]);

        $inventory->update($validated);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => "Item '{$inventory->name}' has been updated successfully.",
                'item' => $inventory
            ]);
        }

        return Redirect::route('inventory.index')
            ->with('success', "Item '{$inventory->name}' has been updated successfully.");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Inventory $inventory)
    {
        $name = $inventory->name;
        $inventory->delete();

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => "Item '{$name}' has been deleted successfully."
            ]);
        }

        return Redirect::route('inventory.index')
            ->with('success', "Item '{$name}' has been deleted successfully.");
    }
} 