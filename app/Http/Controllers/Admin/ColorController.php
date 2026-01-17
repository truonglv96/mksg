<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Color;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ColorController extends Controller
{
    /**
     * Display a listing of the colors.
     */
    public function index(Request $request)
    {
        $search = $request->get('search', '');
        $sort = $request->get('sort', 'weight');
        
        // Build query
        $query = Color::query();
        
        // Search
        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', '%' . $search . '%');
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
            case 'weight':
            default:
                $query->orderBy('weight', 'ASC');
                break;
        }
        
        $colors = $query->paginate(20);
        
        // Get stats
        $totalColors = Color::count();
        
        return view('admin.colors.index', compact(
            'colors',
            'totalColors',
            'search',
            'sort'
        ));
    }

    /**
     * Store a newly created color in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'url_img' => 'nullable|image|mimes:jpeg,jpg,png,gif|max:2048',
                'weight' => 'nullable|integer|min:0',
            ]);
            
            // Handle image upload
            if ($request->hasFile('url_img')) {
                $image = $request->file('url_img');
                $imageName = time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('img/color'), $imageName);
                $validated['url_img'] = $imageName;
            }
            
            // Set defaults
            $validated['weight'] = $validated['weight'] ?? 0;
            
            $color = Color::create($validated);
            
            return response()->json([
                'success' => true,
                'message' => 'Màu sắc đã được tạo thành công!',
                'color' => $color
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
     * Update the specified color in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $color = Color::findOrFail($id);
            
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'url_img' => 'nullable|image|mimes:jpeg,jpg,png,gif|max:2048',
                'weight' => 'nullable|integer|min:0',
                'delete_image' => 'nullable|boolean',
            ]);
            
            // Handle image deletion
            if ($request->has('delete_image') && $request->delete_image) {
                if ($color->url_img && file_exists(public_path('img/color/' . $color->url_img))) {
                    unlink(public_path('img/color/' . $color->url_img));
                }
                $validated['url_img'] = null;
            }
            
            // Handle image upload
            if ($request->hasFile('url_img')) {
                // Delete old image if exists
                if ($color->url_img && file_exists(public_path('img/color/' . $color->url_img))) {
                    unlink(public_path('img/color/' . $color->url_img));
                }
                
                $image = $request->file('url_img');
                $imageName = time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('img/color'), $imageName);
                $validated['url_img'] = $imageName;
            } else {
                unset($validated['url_img']);
            }
            
            // Set defaults
            $validated['weight'] = $validated['weight'] ?? $color->weight ?? 0;
            
            $color->update($validated);
            
            return response()->json([
                'success' => true,
                'message' => 'Màu sắc đã được cập nhật thành công!',
                'color' => $color
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
     * Remove the specified color from storage.
     */
    public function destroy(Request $request, $id)
    {
        try {
            $color = Color::findOrFail($id);
            
            // Check if color is used in product images
            $productImageCount = DB::table('product_images')
                ->where('color_id', $id)
                ->count();
            
            if ($productImageCount > 0) {
                $message = 'Không thể xóa màu sắc này vì đang được sử dụng trong ' . $productImageCount . ' hình ảnh sản phẩm.';
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => $message
                    ], 400);
                }

                return redirect()
                    ->route('admin.colors.index')
                    ->with('error', $message);
            }
            
            // Delete image file if exists
            if ($color->url_img && file_exists(public_path('img/color/' . $color->url_img))) {
                unlink(public_path('img/color/' . $color->url_img));
            }
            
            $color->delete();
            
            $successMessage = 'Màu sắc đã được xóa thành công!';
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => $successMessage
                ]);
            }

            return redirect()
                ->route('admin.colors.index')
                ->with('success', $successMessage);
            
        } catch (\Exception $e) {
            $message = 'Có lỗi xảy ra: ' . $e->getMessage();
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $message
                ], 500);
            }

            return redirect()
                ->route('admin.colors.index')
                ->with('error', $message);
        }
    }

    /**
     * Get color data for edit
     */
    public function getColor($id)
    {
        try {
            $color = Color::findOrFail($id);
            
            return response()->json([
                'success' => true,
                'color' => $color
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy màu sắc'
            ], 404);
        }
    }
}

