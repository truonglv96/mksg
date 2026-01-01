<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FeaturedCategory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class FeaturedCategoryController extends Controller
{
    /**
     * Display a listing of the featured categories.
     */
    public function index(Request $request)
    {
        $search = $request->get('search', '');
        $sort = $request->get('sort', 'weight');
        
        // Build query
        $query = FeaturedCategory::query();
        
        // Search
        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', '%' . $search . '%')
                  ->orWhere('link', 'LIKE', '%' . $search . '%');
            });
        }
        
        // Sort
        switch ($sort) {
            case 'name':
                $query->orderBy('name', 'ASC');
                break;
            case 'name_desc':
                $query->orderBy('name', 'DESC');
                break;
            case 'created_at':
                $query->orderBy('created_at', 'DESC');
                break;
            case 'status':
                $query->orderBy('status', 'DESC');
                break;
            case 'weight':
            default:
                $query->orderBy('weight', 'ASC');
                break;
        }
        
        $featuredCategories = $query->paginate(20);
        
        // Get stats
        $totalCategories = FeaturedCategory::count();
        $activeCategories = FeaturedCategory::where('status', 1)->count();
        
        return view('admin.featured-categories.index', compact(
            'featuredCategories',
            'totalCategories',
            'activeCategories',
            'search',
            'sort'
        ));
    }

    /**
     * Store a newly created featured category in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'link' => 'nullable|string|max:500',
                'color' => 'nullable|string|max:50',
                'status' => 'nullable|boolean',
                'weight' => 'nullable|integer|min:0',
                'image' => 'nullable|image|mimes:jpeg,jpg,png,gif|max:2048',
            ]);
            
            // Handle image upload
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('img/featured-category'), $imageName);
                $validated['image'] = $imageName;
            }
            
            // Set defaults
            $validated['weight'] = $validated['weight'] ?? 0;
            $validated['status'] = $request->has('status') ? ($validated['status'] ?? 1) : 0;
            
            $featuredCategory = FeaturedCategory::create($validated);
            
            return response()->json([
                'success' => true,
                'message' => 'Danh mục nổi bật đã được tạo thành công!',
                'featuredCategory' => $featuredCategory
            ]);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified featured category in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $featuredCategory = FeaturedCategory::findOrFail($id);
            
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'link' => 'nullable|string|max:500',
                'color' => 'nullable|string|max:50',
                'status' => 'nullable|boolean',
                'weight' => 'nullable|integer|min:0',
                'image' => 'nullable|image|mimes:jpeg,jpg,png,gif|max:2048',
                'delete_image' => 'nullable|boolean',
            ]);
            
            // Handle image deletion
            if ($request->has('delete_image') && $request->delete_image) {
                if ($featuredCategory->image && file_exists(public_path('img/featured-category/' . $featuredCategory->image))) {
                    unlink(public_path('img/featured-category/' . $featuredCategory->image));
                }
                $validated['image'] = null;
            }
            
            // Handle image upload
            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($featuredCategory->image && file_exists(public_path('img/featured-category/' . $featuredCategory->image))) {
                    unlink(public_path('img/featured-category/' . $featuredCategory->image));
                }
                
                $image = $request->file('image');
                $imageName = time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('img/featured-category'), $imageName);
                $validated['image'] = $imageName;
            } else {
                unset($validated['image']);
            }
            
            // Set defaults
            $validated['weight'] = $validated['weight'] ?? $featuredCategory->weight ?? 0;
            $validated['status'] = $request->has('status') ? ($validated['status'] ?? 0) : $featuredCategory->status ?? 0;
            
            $featuredCategory->update($validated);
            
            return response()->json([
                'success' => true,
                'message' => 'Danh mục nổi bật đã được cập nhật thành công!',
                'featuredCategory' => $featuredCategory
            ]);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified featured category from storage.
     */
    public function destroy($id)
    {
        try {
            $featuredCategory = FeaturedCategory::findOrFail($id);
            
            // Delete image file if exists
            if ($featuredCategory->image && file_exists(public_path('img/featured-category/' . $featuredCategory->image))) {
                unlink(public_path('img/featured-category/' . $featuredCategory->image));
            }
            
            $featuredCategory->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Danh mục nổi bật đã được xóa thành công!'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get featured category data for edit
     */
    public function getFeaturedCategory($id)
    {
        try {
            $featuredCategory = FeaturedCategory::findOrFail($id);
            
            return response()->json([
                'success' => true,
                'featuredCategory' => $featuredCategory
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy danh mục nổi bật'
            ], 404);
        }
    }
}

