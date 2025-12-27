<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Brand;
use App\Models\BrandImage;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class BrandController extends Controller
{
    /**
     * Display a listing of the brands.
     */
    public function index(Request $request)
    {
        $search = $request->get('search', '');
        $status = $request->get('status', '');
        $sort = $request->get('sort', 'weight');
        
        // Build query
        $query = Brand::query();
        
        // Search
        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', '%' . $search . '%')
                  ->orWhere('alias', 'LIKE', '%' . $search . '%');
            });
        }
        
        // Filter by status
        if ($status !== '') {
            $query->where('hidden', $status);
        }
        
        // Sort
        switch ($sort) {
            case 'name':
                $query->orderBy('name', 'ASC');
                break;
            case 'created_at':
                $query->orderBy('created_at', 'DESC');
                break;
            case 'weight':
            default:
                $query->orderBy('weight', 'ASC');
                break;
        }
        
        $brands = $query->paginate(20);
        
        // Get stats
        $totalBrands = Brand::count();
        $activeBrands = Brand::where('hidden', 1)->count();
        $hiddenBrands = Brand::where('hidden', 0)->count();
        $totalImages = BrandImage::count();
        
        return view('admin.brands.index', compact(
            'brands',
            'totalBrands',
            'activeBrands',
            'hiddenBrands',
            'totalImages',
            'search',
            'status',
            'sort'
        ));
    }

    /**
     * Show the form for creating a new brand.
     */
    public function create()
    {
        return view('admin.brands.create');
    }

    /**
     * Store a newly created brand in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'alias' => 'nullable|string|max:255',
            'content' => 'nullable|string',
            'logo' => 'required|image|mimes:jpeg,jpg,png,gif|max:2048',
            'images.*' => 'nullable|image|mimes:jpeg,jpg,png,gif|max:2048',
            'weight' => 'nullable|integer|min:0',
            'hidden' => 'nullable|integer|in:0,1',
        ]);
        
        DB::beginTransaction();
        try {
            // Generate alias if not provided
            if (empty($validated['alias'])) {
                $validated['alias'] = Str::slug($validated['name']);
            }
            
            // Ensure unique alias
            $validated['alias'] = $this->ensureUniqueAlias($validated['alias']);
            
            // Handle logo upload
            if ($request->hasFile('logo')) {
                $logo = $request->file('logo');
                $logoName = time() . '_' . Str::random(10) . '.' . $logo->getClientOriginalExtension();
                $logo->move(public_path('img/brand'), $logoName);
                $validated['logo'] = $logoName;
            }
            
            // Handle images upload
            $images = [];
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $imageName = time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
                    $image->move(public_path('img/brand'), $imageName);
                    $images[] = $imageName;
                }
            }
            
            if (!empty($images)) {
                $validated['url_imgs'] = json_encode($images);
            }
            
            // Set defaults
            $validated['weight'] = $validated['weight'] ?? 0;
            $validated['hidden'] = $validated['hidden'] ?? 1;
            
            $brand = Brand::create($validated);
            
            // Save images to brand_images table if needed
            if (!empty($images)) {
                foreach ($images as $imageName) {
                    BrandImage::create([
                        'brand_id' => $brand->id,
                        'images' => $imageName
                    ]);
                }
            }
            
            DB::commit();
            
            return redirect()->route('admin.brands.index')
                ->with('success', 'Thương hiệu đã được tạo thành công!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified brand.
     */
    public function show($id)
    {
        $brand = Brand::with('images')->findOrFail($id);
        return view('admin.brands.show', compact('brand'));
    }

    /**
     * Show the form for editing the specified brand.
     */
    public function edit($id)
    {
        $brand = Brand::with('images')->findOrFail($id);
        return view('admin.brands.edit', compact('brand'));
    }

    /**
     * Update the specified brand in storage.
     */
    public function update(Request $request, $id)
    {
        $brand = Brand::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'alias' => 'nullable|string|max:255',
            'content' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,jpg,png,gif|max:2048',
            'images.*' => 'nullable|image|mimes:jpeg,jpg,png,gif|max:2048',
            'weight' => 'nullable|integer|min:0',
            'hidden' => 'nullable|integer|in:0,1',
            'delete_images' => 'nullable|array',
            'delete_brand_images' => 'nullable|array',
            'delete_logo' => 'nullable|boolean',
        ]);
        
        DB::beginTransaction();
        try {
            // Generate alias if not provided
            if (empty($validated['alias'])) {
                $validated['alias'] = Str::slug($validated['name']);
            }
            
            // Ensure unique alias (except current brand)
            $validated['alias'] = $this->ensureUniqueAlias($validated['alias'], $id);
            
            // Handle logo deletion
            if ($request->has('delete_logo') && $brand->logo) {
                $oldLogoPath = public_path('img/brand/' . $brand->logo);
                if (file_exists($oldLogoPath)) {
                    unlink($oldLogoPath);
                }
                $validated['logo'] = null;
            }
            
            // Handle logo upload
            if ($request->hasFile('logo')) {
                // Delete old logo
                if ($brand->logo) {
                    $oldLogoPath = public_path('img/brand/' . $brand->logo);
                    if (file_exists($oldLogoPath)) {
                        unlink($oldLogoPath);
                    }
                }
                
                $logo = $request->file('logo');
                $logoName = time() . '_' . Str::random(10) . '.' . $logo->getClientOriginalExtension();
                $logo->move(public_path('img/brand'), $logoName);
                $validated['logo'] = $logoName;
            } else {
                unset($validated['logo']);
            }
            
            // Handle images deletion from url_imgs
            $existingImages = [];
            if ($brand->url_imgs) {
                $existingImages = is_string($brand->url_imgs) ? json_decode($brand->url_imgs, true) : $brand->url_imgs;
                if (!is_array($existingImages)) {
                    $existingImages = [];
                }
            }
            
            if ($request->has('delete_images') && is_array($request->delete_images)) {
                foreach ($request->delete_images as $index) {
                    if (isset($existingImages[$index])) {
                        $imagePath = public_path('img/brand/' . $existingImages[$index]);
                        if (file_exists($imagePath)) {
                            unlink($imagePath);
                        }
                        unset($existingImages[$index]);
                    }
                }
                $existingImages = array_values($existingImages); // Reindex array
            }
            
            // Handle brand_images deletion
            if ($request->has('delete_brand_images') && is_array($request->delete_brand_images)) {
                foreach ($request->delete_brand_images as $imageId) {
                    $brandImage = BrandImage::find($imageId);
                    if ($brandImage) {
                        $imagePath = public_path('img/brand/' . $brandImage->images);
                        if (file_exists($imagePath)) {
                            unlink($imagePath);
                        }
                        $brandImage->delete();
                    }
                }
            }
            
            // Handle new images upload
            $newImages = [];
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $imageName = time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
                    $image->move(public_path('img/brand'), $imageName);
                    $newImages[] = $imageName;
                    
                    // Also save to brand_images table
                    BrandImage::create([
                        'brand_id' => $brand->id,
                        'images' => $imageName
                    ]);
                }
            }
            
            // Merge existing and new images
            $allImages = array_merge($existingImages, $newImages);
            if (!empty($allImages)) {
                $validated['url_imgs'] = json_encode($allImages);
            } elseif (empty($existingImages) && empty($newImages)) {
                $validated['url_imgs'] = null;
            }
            
            // Set defaults
            $validated['weight'] = $validated['weight'] ?? $brand->weight ?? 0;
            $validated['hidden'] = isset($validated['hidden']) ? (int)$validated['hidden'] : ($brand->hidden ?? 1);
            
            $brand->update($validated);
            
            DB::commit();
            
            return redirect()->route('admin.brands.index')
                ->with('success', 'Thương hiệu đã được cập nhật thành công!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified brand from storage.
     */
    public function destroy($id)
    {
        $brand = Brand::findOrFail($id);
        
        DB::beginTransaction();
        try {
            // Delete logo
            if ($brand->logo) {
                $logoPath = public_path('img/brand/' . $brand->logo);
                if (file_exists($logoPath)) {
                    unlink($logoPath);
                }
            }
            
            // Delete images from url_imgs
            if ($brand->url_imgs) {
                $images = is_string($brand->url_imgs) ? json_decode($brand->url_imgs, true) : $brand->url_imgs;
                if (is_array($images)) {
                    foreach ($images as $image) {
                        $imagePath = public_path('img/brand/' . $image);
                        if (file_exists($imagePath)) {
                            unlink($imagePath);
                        }
                    }
                }
            }
            
            // Delete brand_images
            $brandImages = BrandImage::where('brand_id', $id)->get();
            foreach ($brandImages as $brandImage) {
                $imagePath = public_path('img/brand/' . $brandImage->images);
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
                $brandImage->delete();
            }
            
            $brand->delete();
            
            DB::commit();
            
            return redirect()->route('admin.brands.index')
                ->with('success', 'Thương hiệu đã được xóa thành công!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * Ensure alias is unique
     */
    private function ensureUniqueAlias($alias, $excludeId = null)
    {
        $originalAlias = $alias;
        $counter = 1;
        
        $query = Brand::where('alias', $alias);
        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }
        
        while ($query->exists()) {
            $alias = $originalAlias . '-' . $counter;
            $counter++;
            
            $query = Brand::where('alias', $alias);
            if ($excludeId) {
                $query->where('id', '!=', $excludeId);
            }
        }
        
        return $alias;
    }
}
