<?php

namespace App\Http\Controllers;

use App\Http\Requests\ServiceRequest;
use App\Models\Service;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\View as ViewFacade;
use Illuminate\Support\Facades\Redirect;

class ServiceController extends Controller
{
    public function index(): View
    {
        $query = Service::query()
            ->with('category');

        // Handle search
        if (request()->has('search')) {
            $searchTerm = request('search');
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                  ->orWhere('description', 'like', "%{$searchTerm}%")
                  ->orWhereHas('category', function($q) use ($searchTerm) {
                      $q->where('name', 'like', "%{$searchTerm}%");
                  });
            });
        }

        $services = $query->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return ViewFacade::make('services.index', compact('services'));
    }

    public function create(): View
    {
        return ViewFacade::make('services.create');
    }

    public function store(ServiceRequest $request): mixed
    {
        $service = Service::create($request->validated());

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => "Service '{$service->name}' has been created successfully.",
                'service' => $service->load('category')
            ]);
        }

        return Redirect::route('services.index')
            ->with('success', "Service '{$service->name}' has been created successfully.");
    }

    public function show(Service $service): View
    {
        $service->load('category');
        
        $repairs = $service->repairItems()
            ->with(['repair', 'device.customer'])
            ->whereHas('repair')
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->repair->id,
                    'device' => $item->device,
                    'customer' => $item->device->customer,
                    'status' => $item->repair->status,
                    'cost' => $item->cost,
                    'created_at' => $item->repair->created_at,
                ];
            })
            ->sortByDesc('created_at')
            ->values();

        return ViewFacade::make('services.show', compact('service', 'repairs'));
    }

    public function edit(Service $service): View
    {
        return ViewFacade::make('services.edit', compact('service'));
    }

    public function update(ServiceRequest $request, Service $service): RedirectResponse
    {
        $service->update($request->validated());

        return Redirect::route('services.index')
            ->with('success', "Service '{$service->name}' has been updated successfully.");
    }

    public function destroy(Service $service): RedirectResponse
    {
        if ($service->repairs()->exists()) {
            return Redirect::back()->with('error', 'Cannot delete service with associated repairs.');
        }

        $service->delete();

        return Redirect::route('services.index')
            ->with('success', "Service '{$service->name}' has been deleted successfully.");
    }
} 