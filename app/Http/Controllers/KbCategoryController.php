<?php

namespace App\Http\Controllers;

use App\Models\KbCategory;
use App\Services\KbCategoryService;
use Illuminate\Http\Request;

class KbCategoryController extends Controller
{
    public function __construct(
        private KbCategoryService $categoryService
    ) {}

    /**
     * Display a listing of categories (for management).
     */
    public function index()
    {
        $this->authorize('viewAny', KbCategory::class);

        $categories = $this->categoryService->getAllCategories();

        return view('kb.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new category.
     */
    public function create()
    {
        $this->authorize('create', KbCategory::class);

        $parentCategories = KbCategory::active()->root()->get();

        return view('kb.categories.create', compact('parentCategories'));
    }

    /**
     * Store a newly created category.
     */
    public function store(Request $request)
    {
        $this->authorize('create', KbCategory::class);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string'],
            'parent_id' => ['nullable', 'exists:kb_categories,id'],
            'icon' => ['nullable', 'string', 'max:50'],
            'sort_order' => ['sometimes', 'integer'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $category = $this->categoryService->create($validated);

        return redirect()
            ->route('kb.categories.index')
            ->with('success', 'Category created successfully.');
    }

    /**
     * Show the form for editing the specified category.
     */
    public function edit(KbCategory $category)
    {
        $this->authorize('update', $category);

        $parentCategories = KbCategory::active()
            ->where('id', '!=', $category->id)
            ->root()
            ->get();

        return view('kb.categories.edit', compact('category', 'parentCategories'));
    }

    /**
     * Update the specified category.
     */
    public function update(Request $request, KbCategory $category)
    {
        $this->authorize('update', $category);

        $validated = $request->validate([
            'name' => ['sometimes', 'string', 'max:100'],
            'description' => ['nullable', 'string'],
            'parent_id' => ['nullable', 'exists:kb_categories,id'],
            'icon' => ['nullable', 'string', 'max:50'],
            'sort_order' => ['sometimes', 'integer'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $this->categoryService->update($category, $validated);

        return redirect()
            ->route('kb.categories.index')
            ->with('success', 'Category updated successfully.');
    }

    /**
     * Remove the specified category.
     */
    public function destroy(KbCategory $category)
    {
        $this->authorize('delete', $category);

        try {
            $this->categoryService->delete($category);

            return redirect()
                ->route('kb.categories.index')
                ->with('success', 'Category deleted successfully.');
        } catch (\Exception $e) {
            return redirect()
                ->route('kb.categories.index')
                ->with('error', $e->getMessage());
        }
    }
}
