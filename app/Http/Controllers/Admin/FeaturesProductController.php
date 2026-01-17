<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FeaturesProduct;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class FeaturesProductController extends Controller
{
    /**
     * Display a listing of the features products.
     */
    public function index(Request $request)
    {
        $search = $request->get('search', '');
        $sort = $request->get('sort', 'created_at');
        
        // Build query
        $query = FeaturesProduct::query();
        
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
            default:
                $query->orderBy('created_at', 'DESC');
                break;
        }
        
        $featuresProducts = $query->paginate(20);
        
        // Get stats
        $totalFeatures = FeaturesProduct::count();
        
        return view('admin.features-product.index', compact(
            'featuresProducts',
            'totalFeatures',
            'search',
            'sort'
        ));
    }

    /**
     * Store a newly created features product in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:100',
                'image' => 'required|file|mimes:jpeg,jpg,png,gif,svg|max:2048',
            ]);
            
            // Handle image upload
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
                $imagePath = public_path('img/features-product');
                
                // Create directory if not exists
                if (!File::exists($imagePath)) {
                    File::makeDirectory($imagePath, 0755, true);
                }
                
                $image->move($imagePath, $imageName);
                $validated['image'] = $imageName;
            }
            
            // Create features product
            FeaturesProduct::create($validated);
            
            return response()->json([
                'success' => true,
                'message' => 'Tính năng sản phẩm đã được tạo thành công!'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi tạo tính năng sản phẩm!',
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
     * Get a specific features product.
     */
    public function getFeaturesProduct($id)
    {
        try {
            $featuresProduct = FeaturesProduct::findOrFail($id);
            
            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $featuresProduct->id,
                    'name' => $featuresProduct->name,
                    'image' => $featuresProduct->image,
                    'image_url' => $featuresProduct->getImageUrl(),
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy tính năng sản phẩm!'
            ], 404);
        }
    }

    /**
     * Update the specified features product in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $featuresProduct = FeaturesProduct::findOrFail($id);
            
            $validated = $request->validate([
                'name' => 'required|string|max:100',
                'image' => 'nullable|file|mimes:jpeg,jpg,png,gif,svg|max:2048',
            ]);
            
            // Handle image upload
            if ($request->hasFile('image')) {
                // Delete old image
                if ($featuresProduct->image && File::exists(public_path('img/features-product/' . $featuresProduct->image))) {
                    File::delete(public_path('img/features-product/' . $featuresProduct->image));
                }
                
                $image = $request->file('image');
                $imageName = time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
                $imagePath = public_path('img/features-product');
                
                // Create directory if not exists
                if (!File::exists($imagePath)) {
                    File::makeDirectory($imagePath, 0755, true);
                }
                
                $image->move($imagePath, $imageName);
                $validated['image'] = $imageName;
            } else {
                // If no new image, don't update image field
                unset($validated['image']);
            }
            
            // Update features product
            $featuresProduct->update($validated);
            
            return response()->json([
                'success' => true,
                'message' => 'Tính năng sản phẩm đã được cập nhật thành công!'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi cập nhật tính năng sản phẩm!',
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
     * Remove the specified features product from storage.
     */
    public function destroy(Request $request, $id)
    {
        try {
            $featuresProduct = FeaturesProduct::findOrFail($id);
            
            // Delete image
            if ($featuresProduct->image && File::exists(public_path('img/features-product/' . $featuresProduct->image))) {
                File::delete(public_path('img/features-product/' . $featuresProduct->image));
            }
            
            // Delete features product
            $featuresProduct->delete();
            
            $successMessage = 'Tính năng sản phẩm đã được xóa thành công!';
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => $successMessage
                ]);
            }

            return redirect()
                ->route('admin.features-product.index')
                ->with('success', $successMessage);
        } catch (\Exception $e) {
            $message = 'Có lỗi xảy ra khi xóa tính năng sản phẩm!';
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $message
                ], 500);
            }

            return redirect()
                ->route('admin.features-product.index')
                ->with('error', $message);
        }
    }
}

