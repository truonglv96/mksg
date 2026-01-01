<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Material;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class MaterialController extends Controller
{
    /**
     * Display a listing of the materials.
     */
    public function index(Request $request)
    {
        $search = $request->get('search', '');
        $sort = $request->get('sort', 'weight');
        
        // Build query
        $query = Material::query();
        
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
        
        $materials = $query->paginate(20);
        
        // Get stats
        $totalMaterials = Material::count();
        
        return view('admin.materials.index', compact(
            'materials',
            'totalMaterials',
            'search',
            'sort'
        ));
    }

    /**
     * Store a newly created material in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'weight' => 'nullable|integer|min:0',
            ]);
            
            // Set defaults
            $validated['weight'] = $validated['weight'] ?? 0;
            
            $material = Material::create($validated);
            
            return response()->json([
                'success' => true,
                'message' => 'Chất liệu đã được tạo thành công!',
                'material' => $material
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
     * Update the specified material in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $material = Material::findOrFail($id);
            
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'weight' => 'nullable|integer|min:0',
            ]);
            
            // Set defaults
            $validated['weight'] = $validated['weight'] ?? $material->weight ?? 0;
            
            $material->update($validated);
            
            return response()->json([
                'success' => true,
                'message' => 'Chất liệu đã được cập nhật thành công!',
                'material' => $material
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
     * Remove the specified material from storage.
     */
    public function destroy($id)
    {
        try {
            $material = Material::findOrFail($id);
            
            // Check if material is used in products
            $productCount = DB::table('products')
                ->where('material_id', $id)
                ->count();
            
            if ($productCount > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không thể xóa chất liệu này vì đang được sử dụng trong ' . $productCount . ' sản phẩm.'
                ], 400);
            }
            
            $material->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Chất liệu đã được xóa thành công!'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get material data for edit
     */
    public function getMaterial($id)
    {
        try {
            $material = Material::findOrFail($id);
            
            return response()->json([
                'success' => true,
                'material' => $material
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy chất liệu'
            ], 404);
        }
    }
}

