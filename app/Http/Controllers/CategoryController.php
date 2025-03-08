<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\View as ViewFacade;
use Illuminate\Support\Facades\Redirect;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $categories = Category::query()
            ->withCount('services')
            ->orderBy('name')
            ->paginate(10);

        return ViewFacade::make('categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return ViewFacade::make('categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): mixed
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:categories'],
            'description' => ['nullable', 'string'],
        ]);

        $category = Category::create($validated);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => "Category '{$category->name}' has been created successfully.",
                'category' => $category
            ]);
        }

        if ($request->headers->get('referer') === route('services.create')) {
            return Redirect::route('services.create')
                ->with('success', "Category '{$category->name}' has been created successfully.");
        }

        return Redirect::route('categories.index')
            ->with('success', "Category '{$category->name}' has been created successfully.");
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category): View
    {
        return ViewFacade::make('categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:categories,name,' . $category->id],
            'description' => ['nullable', 'string'],
        ]);

        $category->update($validated);

        return Redirect::route('categories.index')
            ->with('success', "Category '{$category->name}' has been updated successfully.");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category): RedirectResponse
    {
        if ($category->services()->exists()) {
            return Redirect::back()
                ->with('error', 'Cannot delete category with associated services. Please reassign or delete the services first.');
        }

        $category->delete();

        return Redirect::route('categories.index')
            ->with('success', "Category '{$category->name}' has been deleted successfully.");
    }
}
