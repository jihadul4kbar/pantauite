<?php

namespace App\Services;

use App\Models\KbCategory;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class KbCategoryService
{
    /**
     * Get all categories with hierarchy
     */
    public function getAllCategories(): Collection
    {
        return KbCategory::with(['children', 'articles' => function ($query) {
            $query->published()->limit(5);
        }])
        ->withCount('articles')
        ->active()
        ->ordered()
        ->get();
    }

    /**
     * Create category
     */
    public function create(array $data): KbCategory
    {
        return KbCategory::create([
            'name' => $data['name'],
            'slug' => $this->generateSlug($data['name']),
            'description' => $data['description'] ?? null,
            'parent_id' => $data['parent_id'] ?? null,
            'icon' => $data['icon'] ?? null,
            'sort_order' => $data['sort_order'] ?? 0,
            'is_active' => $data['is_active'] ?? true,
        ]);
    }

    /**
     * Update category
     */
    public function update(KbCategory $category, array $data): KbCategory
    {
        $updateData = [];

        if (isset($data['name']) && $data['name'] !== $category->name) {
            $updateData['slug'] = $this->generateSlug($data['name']);
        }

        $updateData = array_merge($updateData, [
            'name' => $data['name'] ?? $category->name,
            'description' => $data['description'] ?? $category->description,
            'parent_id' => $data['parent_id'] ?? $category->parent_id,
            'icon' => $data['icon'] ?? $category->icon,
            'sort_order' => $data['sort_order'] ?? $category->sort_order,
            'is_active' => $data['is_active'] ?? $category->is_active,
        ]);

        $category->update($updateData);

        return $category->fresh();
    }

    /**
     * Delete category
     */
    public function delete(KbCategory $category): bool
    {
        // Cannot delete category with articles
        if ($category->articles()->count() > 0) {
            throw new \Exception('Cannot delete category with existing articles.');
        }

        return $category->delete();
    }

    /**
     * Generate unique slug
     */
    protected function generateSlug(string $name): string
    {
        $slug = Str::slug($name);
        $originalSlug = $slug;
        $counter = 1;

        while (KbCategory::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }
}
