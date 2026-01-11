<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    /**
     * Display a listing of the categories.
     */
    public function index(Request $request)
    {
        $type = $request->get('type', '');
        
        // Build query
        $query = Category::query();
        
        // Filter by type if provided
        if (!empty($type)) {
            $query->where('type', $type);
        }
        
        // Get all categories ordered by parent_id and weight
        $allCategories = $query->orderBy('parent_id', 'ASC')
            ->orderBy('weight', 'ASC')
            ->orderBy('id', 'ASC')
            ->get();
        
        // Build hierarchical tree
        $categories = $this->buildTree($allCategories);
        
        // Get flat list for parent dropdown (with level for display)
        $flatCategories = $this->getFlatCategoriesWithLevel($allCategories);
        
        return view('admin.categories.index', compact('categories', 'flatCategories', 'type'));
    }

    /**
     * Build hierarchical tree from flat collection
     */
    private function buildTree($categories, $parentId = 0)
    {
        $tree = collect();
        
        foreach ($categories as $category) {
            if ($category->parent_id == $parentId) {
                $category->children = $this->buildTree($categories, $category->id);
                $tree->push($category);
            }
        }
        
        return $tree;
    }

    /**
     * Get flat categories list with level for dropdown
     */
    private function getFlatCategoriesWithLevel($categories, $parentId = 0, $level = 0, $excludeId = null)
    {
        $result = collect();
        
        foreach ($categories as $category) {
            if ($category->parent_id == $parentId && $category->id != $excludeId) {
                $category->level = $level;
                $result->push($category);
                $children = $this->getFlatCategoriesWithLevel($categories, $category->id, $level + 1, $excludeId);
                $result = $result->merge($children);
            }
        }
        
        return $result;
    }

    /**
     * Show the form for creating a new category.
     */
    public function create()
    {
        $categories = Category::orderBy('parent_id', 'ASC')
            ->orderBy('weight', 'ASC')
            ->get();
        $flatCategories = $this->getFlatCategoriesWithLevel($categories);
        
        return view('admin.categories.create', compact('flatCategories'));
    }

    /**
     * Store a newly created category in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'alias' => 'nullable|string|max:255',
            'type' => 'required|string|in:product,new,brand,partner',
            'parent_id' => 'nullable|integer',
            'des' => 'nullable|string|max:500',
            'kw' => 'nullable|string|max:255',
            'hidden' => 'nullable|integer|in:0,1',
            'index_hidden' => 'nullable|integer|in:0,1',
            'weight' => 'nullable|integer|min:0',
        ]);
        
        // Validate parent_id if it's not 0
        if (isset($validated['parent_id']) && $validated['parent_id'] > 0) {
            $request->validate([
                'parent_id' => 'exists:category,id'
            ]);
        }
        
        // Generate alias if not provided
        if (empty($validated['alias'])) {
            $validated['alias'] = $this->generateAlias($validated['name']);
        }
        
        // Ensure unique alias
        $validated['alias'] = $this->ensureUniqueAlias($validated['alias']);
        
        // Set defaults
        $validated['parent_id'] = isset($validated['parent_id']) ? (int)$validated['parent_id'] : 0;
        $validated['hidden'] = isset($validated['hidden']) ? (int)$validated['hidden'] : 0;
        $validated['index_hidden'] = isset($validated['index_hidden']) ? (int)$validated['index_hidden'] : 0;
        $validated['weight'] = isset($validated['weight']) ? (int)$validated['weight'] : 0;
        $validated['total'] = 0;
        
        $category = Category::create($validated);
        
        return response()->json([
            'success' => true,
            'message' => 'Danh mục đã được tạo thành công!',
            'category' => $category
        ]);
    }

    /**
     * Display the specified category.
     */
    public function show($id)
    {
        $category = Category::findOrFail($id);
        
        return view('admin.categories.show', compact('category'));
    }

    /**
     * Show the form for editing the specified category.
     */
    public function edit($id)
    {
        $category = Category::findOrFail($id);
        $categories = Category::orderBy('parent_id', 'ASC')
            ->orderBy('weight', 'ASC')
            ->get();
        $flatCategories = $this->getFlatCategoriesWithLevel($categories, 0, 0, $id);
        
        return view('admin.categories.edit', compact('category', 'flatCategories'));
    }

    /**
     * Update the specified category in storage.
     */
    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'alias' => 'nullable|string|max:255',
            'type' => 'required|string|in:product,new,brand,partner',
            'parent_id' => 'nullable|integer',
            'des' => 'nullable|string|max:500',
            'kw' => 'nullable|string|max:255',
            'hidden' => 'nullable|integer|in:0,1',
            'index_hidden' => 'nullable|integer|in:0,1',
            'weight' => 'nullable|integer|min:0',
        ]);
        
        // Validate parent_id if it's not 0
        if (isset($validated['parent_id']) && $validated['parent_id'] > 0) {
            $request->validate([
                'parent_id' => 'exists:category,id'
            ]);
        }
        
        // Prevent circular reference (category cannot be its own parent or child)
        if (isset($validated['parent_id']) && $validated['parent_id'] == $id) {
            return response()->json([
                'success' => false,
                'message' => 'Danh mục không thể là cha của chính nó!'
            ], 422);
        }
        
        // Check if new parent is a descendant (would create circular reference)
        if (isset($validated['parent_id']) && $validated['parent_id'] > 0) {
            $isDescendant = $this->isDescendant($id, $validated['parent_id']);
            if ($isDescendant) {
                return response()->json([
                    'success' => false,
                    'message' => 'Danh mục không thể là cha của một danh mục con của nó!'
                ], 422);
            }
        }
        
        // Generate alias if not provided
        if (empty($validated['alias'])) {
            $validated['alias'] = $this->generateAlias($validated['name']);
        }
        
        // Ensure unique alias (except for current category)
        $validated['alias'] = $this->ensureUniqueAlias($validated['alias'], $id);
        
        // Set defaults (checkboxes send 0 or 1)
        $validated['parent_id'] = isset($validated['parent_id']) ? (int)$validated['parent_id'] : ($category->parent_id ?? 0);
        $validated['hidden'] = isset($validated['hidden']) ? (int)$validated['hidden'] : 0;
        $validated['index_hidden'] = isset($validated['index_hidden']) ? (int)$validated['index_hidden'] : 0;
        $validated['weight'] = isset($validated['weight']) ? (int)$validated['weight'] : ($category->weight ?? 0);
        
        $category->update($validated);
        
        return response()->json([
            'success' => true,
            'message' => 'Danh mục đã được cập nhật thành công!',
            'category' => $category
        ]);
    }

    /**
     * Remove the specified category from storage.
     */
    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        
        // Check if category has children
        $hasChildren = Category::where('parent_id', $id)->exists();
        
        if ($hasChildren) {
            // Delete all children first (recursive)
            $this->deleteCategoryAndChildren($id);
        } else {
            $category->delete();
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Danh mục đã được xóa thành công!'
        ]);
    }

    /**
     * Recursively delete category and all its children
     */
    private function deleteCategoryAndChildren($categoryId)
    {
        $children = Category::where('parent_id', $categoryId)->get();
        
        foreach ($children as $child) {
            $this->deleteCategoryAndChildren($child->id);
        }
        
        Category::where('id', $categoryId)->delete();
    }

    /**
     * Update category order (for drag and drop)
     */
    public function reorder(Request $request)
    {
        $request->validate([
            'categories' => 'required|array',
            'categories.*.id' => 'required|integer|exists:category,id',
            'categories.*.parent_id' => 'required|integer',
            'categories.*.weight' => 'required|integer|min:0',
        ]);
        
        DB::beginTransaction();
        try {
            foreach ($request->categories as $item) {
                Category::where('id', $item['id'])->update([
                    'parent_id' => $item['parent_id'] ?? 0,
                    'weight' => $item['weight'] ?? 0
                ]);
            }
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Đã cập nhật thứ tự danh mục!'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi cập nhật thứ tự: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate alias from name (Vietnamese-friendly)
     */
    private function generateAlias($name)
    {
        // Use Laravel's Str::slug which handles Vietnamese characters better
        return Str::slug($name);
    }

    /**
     * Ensure alias is unique
     */
    private function ensureUniqueAlias($alias, $excludeId = null)
    {
        $originalAlias = $alias;
        $counter = 1;
        
        $query = Category::where('alias', $alias);
        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }
        
        while ($query->exists()) {
            $alias = $originalAlias . '-' . $counter;
            $counter++;
            
            $query = Category::where('alias', $alias);
            if ($excludeId) {
                $query->where('id', '!=', $excludeId);
            }
        }
        
        return $alias;
    }

    /**
     * Check if category is descendant of another category
     */
    private function isDescendant($categoryId, $potentialParentId)
    {
        $current = Category::find($potentialParentId);
        
        while ($current && $current->parent_id > 0) {
            if ($current->parent_id == $categoryId) {
                return true;
            }
            $current = Category::find($current->parent_id);
        }
        
        return false;
    }
}
