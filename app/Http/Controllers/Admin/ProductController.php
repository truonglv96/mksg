<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use Illuminate\Http\Request;
use App\Models\Products;
use App\Models\ProductImage;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Material;
use App\Models\Color;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use App\Models\ProductCategories;
use App\Models\ProductColor;
use App\Models\ProductPriceSale;
use App\Models\DiscountedCombo;
use App\Models\FeaturesProduct;
use App\Models\ProductDegreeRange;
use Exception;

class ProductController extends Controller
{
    /**
     * Display a listing of the products.
     */
    public function index(Request $request)
    {
        // Get query parameters
        $search = $request->get('search', '');
        $category = $request->get('category', '');
        $brand = $request->get('brand', '');
        $sort = $request->get('sort', 'newest');
        
        // Build optimized query - sử dụng subquery để tối ưu performance
        $query = Products::select('products.*')
            ->distinct();
        
        // Apply filters using subqueries when possible để tránh duplicate rows
        if (!empty($category) && $category != '-1') {
            $query->whereExists(function($q) use ($category) {
                $q->select(DB::raw(1))
                  ->from('product_categories')
                  ->whereColumn('product_categories.ProductID', 'products.id')
                  ->where('product_categories.CategoryID', $category);
            });
        }
        
        if (!empty($brand) && $brand != '' && $brand != '-1') {
            $query->where('products.brand_id', $brand);
        }
        
        // Search - tìm theo tên, mã sản phẩm (code_sp), và mô tả
        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('products.name', 'LIKE', '%' . $search . '%')
                  ->orWhere('products.code_sp', 'LIKE', '%' . $search . '%')
                  ->orWhere('products.description', 'LIKE', '%' . $search . '%');
            });
        }
        
        // Sort
        switch ($sort) {
            case 'name_asc':
                $query->orderBy('products.name', 'ASC');
                break;
            case 'name_desc':
                $query->orderBy('products.name', 'DESC');
                break;
            case 'price_asc':
                $query->orderBy('products.price_sale', 'ASC')
                      ->orderBy('products.price', 'ASC');
                break;
            case 'price_desc':
                $query->orderBy('products.price_sale', 'DESC')
                      ->orderBy('products.price', 'DESC');
                break;
            default: // newest
                $query->orderBy('products.id', 'DESC')
                      ->orderBy('products.weight', 'ASC');
        }
        
        // Paginate với eager loading images và categories trong cùng query
        $products = $query->with(['images' => function($q) {
                $q->orderBy('weight', 'ASC');
            }])
            ->paginate(20)
            ->withQueryString();
        
        // Eager load categories cho products trong current page
        $productIds = $products->pluck('id')->toArray();
        if (!empty($productIds)) {
            $productCategories = DB::table('product_categories')
                ->join('category', 'product_categories.CategoryID', '=', 'category.id')
                ->whereIn('product_categories.ProductID', $productIds)
                ->where('category.type', 'product')
                ->select('product_categories.ProductID', 'category.*')
                ->get()
                ->groupBy('ProductID');
            
            // Attach categories to products
            foreach ($products as $product) {
                $product->categories = $productCategories->get($product->id, collect());
            }
        }
        
        // Get stats - Cache for 5 minutes để tăng tốc độ
        // Cache sẽ tự động hoạt động trên cPanel khi bảng cache đã được tạo
        try {
            $stats = Cache::remember('admin_products_stats', 300, function() {
                return Products::selectRaw('
                    COUNT(*) as total,
                    SUM(CASE WHEN hidden = 1 THEN 1 ELSE 0 END) as active,
                    SUM(CASE WHEN hidden = 0 THEN 1 ELSE 0 END) as hidden
                ')->first();
            });
        } catch (\Exception $e) {
            // Fallback: Query trực tiếp nếu cache chưa được setup
            \Log::warning('Cache not available, using direct query', ['error' => $e->getMessage()]);
            $stats = Products::selectRaw('
                COUNT(*) as total,
                SUM(CASE WHEN hidden = 1 THEN 1 ELSE 0 END) as active,
                SUM(CASE WHEN hidden = 0 THEN 1 ELSE 0 END) as hidden
            ')->first();
        }
        
        $totalProducts = $stats->total ?? 0;
        $activeProducts = $stats->active ?? 0;
        $outOfStockProducts = 0; // Note: stock column doesn't exist
        $hiddenProducts = $stats->hidden ?? 0;
        
        // Get hierarchical categories for filter - Cache for 1 hour
        $categories = $this->getCachedCategories();
        
        // Get brands for filter - Cache for 1 hour
        $brands = $this->getCachedBrands();
        
        return view('admin.products.index', compact(
            'products',
            'totalProducts',
            'activeProducts',
            'outOfStockProducts',
            'hiddenProducts',
            'categories',
            'brands',
            'search',
            'category',
            'brand',
            'sort'
        ));
    }

    /**
     * Show the form for creating a new product.
     */
    public function create()
    {
        $categories = $this->getHierarchicalCategories();
        $brands = Brand::orderBy('name', 'ASC')->get();
        $materials = Material::orderBy('weight', 'ASC')->get();
        $colors = Color::orderBy('weight', 'ASC')->get();
        $combos = \App\Models\DiscountedCombo::where('status', 1)->orderBy('weight', 'ASC')->orderBy('name', 'ASC')->get();
        $featuresProducts = FeaturesProduct::orderBy('id', 'ASC')->get();
        
        return view('admin.products.create', compact('categories', 'brands', 'materials', 'colors', 'combos', 'featuresProducts'));
    }

    /**
     * Store a newly created product in storage.
     */
    public function store(StoreProductRequest $request)
    {
        try {
            DB::beginTransaction();
            $validated = $request->validated();
                
            // Generate alias if not provided
            if (empty($validated['alias']) && !empty($validated['name'])) {
                $validated['alias'] = Str::slug($validated['name']);
                // Ensure uniqueness
                $count = Products::where('alias', $validated['alias'])->count();
                if ($count > 0) {
                    $validated['alias'] = $validated['alias'] . '-' . time();
                }
            }
            
            // Set defaults
            $validated = array_merge($validated, [
                'name' => $validated['name'] ?? '',
                'alias' => $validated['alias'] ?? '',
                'description' => $validated['description'] ?? '',
                'content' => $validated['content'] ?? '',
                'tech' => $validated['tech'] ?? '',
                'service' => $validated['service'] ?? '',
                'tutorial' => $validated['tutorial'] ?? '',
                'price' => $validated['price'] ?? 0,
                'price_sale' => $validated['price_sale'] ?? 0,
                'url_imgs' => $validated['url_imgs'] ?? '',
                'type_sale' => $validated['type_sale'] ?? 0,
                'brand_id' => $validated['brand_id'] ?? '',
                'material_id' => $validated['material_id'] ?? '',
                'color_id' => $validated['color_id'] ?? '',
                'kw' => $validated['kw'] ?? '',
                'weight' => $validated['weight'] ?? 0,
                'cat_id' => $validated['categories'] ?? '',
                'hidden' => $validated['hidden'] ?? 1,
                'meta_des' => $validated['meta_des'] ?? '',
                'address_sale' => $validated['address_sale'] ?? '',
                'open_time' => $validated['open_time'] ?? '',
                'discount_sale' => $validated['discount_sale'] ?? 0,
                'unit' => $validated['unit'] ?? '',
                'gender' => $validated['gender'] ?? '',
                'type_color' => $validated['type_color'] ?? 0,
            ]);

            
            // Create product
            $product = Products::create($validated);
            
            // Handle multiple images upload and save to ProductImage model
            if ($request->hasFile('images')) {
                $imageColors = $request->input('image_colors', []);
                $weight = 0;
                foreach ($request->file('images') as $index => $image) {
                    $imageName = time() . '_' . Str::random(10) . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                    $image->move(public_path('img/product'), $imageName);
                    
                    // Get color_id for this image if exists
                    $colorId = isset($imageColors[$index]) && !empty($imageColors[$index]) ? $imageColors[$index] : null;
                    
                    // Save to ProductImage model
                    ProductImage::create([
                        'product_id' => $product->id,
                        'image' => $imageName,
                        'weight' => $weight++,
                        'color_id' => $colorId,
                    ]);
                }
            }
            
            // Attach categories
            if (!empty($validated['categories'])) {
                foreach ($validated['categories'] as $categoryId) {
                    ProductCategories::create([
                        'ProductID' => $product->id,
                        'CategoryID' => $categoryId,
                        'type' => 'product',
                    ]);
                }
            }

            if (!empty($validated['sale_prices'])) {
                foreach ($validated['sale_prices'] as $salePrice) {
                    ProductPriceSale::create([
                        'id_Product' => $product->id,
                        'parent_category' => $salePrice['category1'] ?? null,
                        'id_category' => $salePrice['category2'] ?? null,
                        'price' => $salePrice['discount_price'] ?? 0,
                    ]);
                }
            }

            if (!empty($validated['combos'])) {
                foreach ($validated['combos'] as $combo) {
                    DiscountedCombo::create([
                        'product_id' => $product->id,
                        'name' => $combo['name'] ?? '',
                        'weight' => $combo['weight'] ?? 0,
                        'description' => $combo['description'] ?? null,
                        'price' => $combo['price'] ?? 0,
                        'status' => 1,
                    ]);
                }
            }

            // Handle features products (many-to-many)
            if ($request->has('features_products') && !empty($request->input('features_products'))) {
                $featuresProductIds = $request->input('features_products');
                // Store as array (Laravel will automatically encode to JSON via model cast)
                $product->update(['id_features_product' => $featuresProductIds]);
            } else {
                $product->update(['id_features_product' => null]);
            }

            // Handle degree ranges
            if (!empty($validated['degree_ranges'])) {
                foreach ($validated['degree_ranges'] as $degreeRange) {
                    if (!empty($degreeRange['name'])) {
                        ProductDegreeRange::create([
                            'product_id' => $product->id,
                            'name' => $degreeRange['name'] ?? '',
                            'price' => $degreeRange['price'] ?? 0,
                            'price_sale' => $degreeRange['price_sale'] ?? 0,
                            'weight' => $degreeRange['weight'] ?? 0,
                        ]);
                    }
                }
            }
            
            DB::commit();
            
            return redirect()->route('admin.products.index')->with('success', 'Sản phẩm đã được tạo thành công!');
            
        } catch (Exception $e) {
            DB::rollBack();
            
            // Log error for debugging
            \Log::error('Error creating product: ' . $e->getMessage(), [
                'exception' => $e,
                'request_data' => $request->except(['images'])
            ]);
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Có lỗi xảy ra khi tạo sản phẩm: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified product.
     */
    public function show($id)
    {
        // Redirect to edit page instead of show
        return redirect()->route('admin.products.edit', $id);
    }

    /**
     * Show the form for editing the specified product.
     */
    public function edit($id)
    {
        // Load product with relationships
        $product = Products::with(['images', 'brand'])->findOrFail($id);
        
        // Get product categories
        $productCategoryIds = DB::table('product_categories')
            ->where('ProductID', $product->id)
            ->where('type', 'product')
            ->pluck('CategoryID')
            ->toArray();
        
        // Get product colors
        $productColorIds = DB::table('product_color')
            ->where('productID', $product->id)
            ->pluck('colorID')
            ->toArray();
        
        // Get product sale prices
        $productSalePrices = ProductPriceSale::where('id_Product', $product->id)->get();
        
        // Get product combos
        $productCombos = DiscountedCombo::where('product_id', $product->id)->orderBy('weight', 'ASC')->get();
        
        // Get product features (many-to-many relationship)
        // With model cast, id_features_product is automatically decoded to array
        $productFeaturesProductIds = [];
        if ($product->id_features_product) {
            $productFeaturesProductIds = is_array($product->id_features_product) 
                ? $product->id_features_product 
                : [$product->id_features_product];
            // Filter out empty values
            $productFeaturesProductIds = array_filter($productFeaturesProductIds);
        }
        
        // Get product degree ranges
        $productDegreeRanges = ProductDegreeRange::where('product_id', $product->id)->orderBy('weight', 'ASC')->get();
        
        // Get dropdown data (same as create)
        $categories = $this->getHierarchicalCategories();
        $brands = Brand::orderBy('name', 'ASC')->get();
        $materials = Material::orderBy('weight', 'ASC')->get();
        $colors = Color::orderBy('weight', 'ASC')->get();
        $combos = DiscountedCombo::where('status', 1)->orderBy('weight', 'ASC')->orderBy('name', 'ASC')->get();
        $featuresProducts = FeaturesProduct::orderBy('id', 'ASC')->get();
        
        return view('admin.products.edit', compact(
            'product',
            'productCategoryIds',
            'productColorIds',
            'productSalePrices',
            'productCombos',
            'productFeaturesProductIds',
            'productDegreeRanges',
            'categories',
            'brands',
            'materials',
            'colors',
            'combos',
            'featuresProducts'
        ));
    }

    /**
     * Update the specified product in storage.
     */
    public function update(UpdateProductRequest $request, $id)
    {
        try {
            DB::beginTransaction();
            $product = Products::findOrFail($id);
            $validated = $request->validated();
            
            // Generate alias if not provided
            if (empty($validated['alias']) && !empty($validated['name'])) {
                $validated['alias'] = Str::slug($validated['name']);
                // Ensure uniqueness
                $count = Products::where('alias', $validated['alias'])->where('id', '!=', $id)->count();
                if ($count > 0) {
                    $validated['alias'] = $validated['alias'] . '-' . time();
                }
            }
            
            // Set defaults
            $validated = array_merge($validated, [
                'name' => $validated['name'] ?? '',
                'alias' => $validated['alias'] ?? '',
                'description' => $validated['description'] ?? '',
                'content' => $validated['content'] ?? '',
                'tech' => $validated['tech'] ?? '',
                'service' => $validated['service'] ?? '',
                'tutorial' => $validated['tutorial'] ?? '',
                'price' => $validated['price'] ?? 0,
                'price_sale' => $validated['price_sale'] ?? 0,
                'url_imgs' => $validated['url_imgs'] ?? '',
                'type_sale' => $validated['type_sale'] ?? 0,
                'brand_id' => $validated['brand_id'] ?? '',
                'material_id' => $validated['material_id'] ?? '',
                'color_id' => $validated['color_id'] ?? '',
                'kw' => $validated['kw'] ?? '',
                'weight' => $validated['weight'] ?? 0,
                'cat_id' => $validated['categories'] ?? '',
                'hidden' => $validated['hidden'] ?? 1,
                'meta_des' => $validated['meta_des'] ?? '',
                'address_sale' => $validated['address_sale'] ?? '',
                'open_time' => $validated['open_time'] ?? '',
                'discount_sale' => $validated['discount_sale'] ?? 0,
                'unit' => $validated['unit'] ?? '',
                'gender' => $validated['gender'] ?? '',
                'type_color' => $validated['type_color'] ?? 0,
            ]);
            
            // Handle main image upload
            if ($request->hasFile('url_img')) {
                // Delete old image if exists
                if ($product->url_img && file_exists(public_path('img/product/' . $product->url_img))) {
                    unlink(public_path('img/product/' . $product->url_img));
                }
                
                $image = $request->file('url_img');
                $imageName = time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('img/product'), $imageName);
                $validated['url_img'] = $imageName;
            }
            
            // Handle multiple images - Delete images
            if ($request->has('images_to_delete')) {
                foreach ($request->input('images_to_delete') as $imageIdOrPath) {
                    // Check if it's an ID (numeric) or path (string)
                    if (is_numeric($imageIdOrPath)) {
                        $image = ProductImage::find($imageIdOrPath);
                        if ($image) {
                            // Delete file
                            if (file_exists(public_path('img/product/' . $image->image))) {
                                unlink(public_path('img/product/' . $image->image));
                            }
                            // Delete record
                            $image->delete();
                        }
                    } else {
                        // It's a path, find and delete
                        $image = ProductImage::where('product_id', $product->id)
                            ->where('image', $imageIdOrPath)
                            ->first();
                        if ($image) {
                            if (file_exists(public_path('img/product/' . $image->image))) {
                                unlink(public_path('img/product/' . $image->image));
                            }
                            $image->delete();
                        }
                    }
                }
            }
            
            // Update product
            $product->update($validated);
            
            // Handle image order - cập nhật thứ tự của existing images và thêm new images
            $imageOrder = $request->input('image_order', []);
            $imageColors = $request->input('image_colors', []);
            $existingImageIds = $request->input('existing_image_ids', []);
            
            // Tạo map để lưu existing images
            $existingImagesMap = [];
            if (!empty($existingImageIds)) {
                $existingImages = ProductImage::whereIn('id', $existingImageIds)
                    ->where('product_id', $product->id)
                    ->get()
                    ->keyBy('id');
                $existingImagesMap = $existingImages->toArray();
            }
            
            // Cập nhật thứ tự tất cả images (existing + new) theo thứ tự trong image_order
            $weight = 0;
            $newImageIndex = 0;
            
            foreach ($imageOrder as $orderIndex => $imageId) {
                // Kiểm tra xem đây là existing image (numeric ID) hay new image (temp ID)
                if (is_numeric($imageId) && isset($existingImagesMap[$imageId])) {
                    // Existing image - cập nhật weight và color_id
                    $existingImage = ProductImage::find($imageId);
                    if ($existingImage && $existingImage->product_id == $product->id) {
                        $colorId = isset($imageColors[$orderIndex]) && !empty($imageColors[$orderIndex]) 
                            ? $imageColors[$orderIndex] 
                            : $existingImage->color_id;
                        
                        $existingImage->update([
                            'weight' => $weight,
                            'color_id' => $colorId,
                        ]);
                    }
                } else {
                    // New image - xử lý file upload
                    if ($request->hasFile('images') && isset($request->file('images')[$newImageIndex])) {
                        $image = $request->file('images')[$newImageIndex];
                        $imageName = time() . '_' . Str::random(10) . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                        $image->move(public_path('img/product'), $imageName);
                        
                        $colorId = isset($imageColors[$orderIndex]) && !empty($imageColors[$orderIndex]) 
                            ? $imageColors[$orderIndex] 
                            : null;
                        
                        ProductImage::create([
                            'product_id' => $product->id,
                            'image' => $imageName,
                            'weight' => $weight,
                            'color_id' => $colorId,
                        ]);
                        
                        $newImageIndex++;
                    }
                }
                $weight++;
            }
            
            // Attach categories
            if (isset($validated['categories'])) {
                DB::table('product_categories')->where('ProductID', $product->id)->where('type', 'product')->delete();
                foreach ($validated['categories'] as $categoryId) {
                    ProductCategories::create([
                        'ProductID' => $product->id,
                        'CategoryID' => $categoryId,
                        'type' => 'product',
                    ]);
                }
            }

            // Handle sale prices
            if (isset($validated['sale_prices'])) {
                // Delete existing sale prices
                ProductPriceSale::where('id_Product', $product->id)->delete();
                
                // Create new sale prices
                foreach ($validated['sale_prices'] as $salePrice) {
                    if (!empty($salePrice['discount_price'])) {
                        ProductPriceSale::create([
                            'id_Product' => $product->id,
                            'parent_category' => $salePrice['category1'] ?? null,
                            'id_category' => $salePrice['category2'] ?? null,
                            'price' => $salePrice['discount_price'] ?? 0,
                        ]);
                    }
                }
            }

            // Handle combos
            if (isset($validated['combos'])) {
                // Delete existing combos
                DiscountedCombo::where('product_id', $product->id)->delete();
                
                // Create new combos
                foreach ($validated['combos'] as $combo) {
                    if (!empty($combo['name'])) {
                        DiscountedCombo::create([
                            'product_id' => $product->id,
                            'name' => $combo['name'] ?? '',
                            'weight' => $combo['weight'] ?? 0,
                            'description' => $combo['description'] ?? null,
                            'price' => $combo['price'] ?? 0,
                            'status' => 1,
                        ]);
                    }
                }
            }

            // Handle features products (many-to-many)
            if ($request->has('features_products') && !empty($request->input('features_products'))) {
                $featuresProductIds = $request->input('features_products');
                // Store as array (Laravel will automatically encode to JSON via model cast)
                $product->update(['id_features_product' => $featuresProductIds]);
            } else {
                $product->update(['id_features_product' => null]);
            }

            // Handle degree ranges
            if (isset($validated['degree_ranges'])) {
                // Delete existing degree ranges
                ProductDegreeRange::where('product_id', $product->id)->delete();
                
                // Create new degree ranges
                foreach ($validated['degree_ranges'] as $degreeRange) {
                    if (!empty($degreeRange['name'])) {
                        ProductDegreeRange::create([
                            'product_id' => $product->id,
                            'name' => $degreeRange['name'] ?? '',
                            'price' => $degreeRange['price'] ?? 0,
                            'price_sale' => $degreeRange['price_sale'] ?? 0,
                            'weight' => $degreeRange['weight'] ?? 0,
                        ]);
                    }
                }
            } else {
                // If no degree ranges provided, delete all existing ones
                ProductDegreeRange::where('product_id', $product->id)->delete();
            }
            
            // Handle colors
            if (isset($validated['colors'])) {
                DB::table('product_color')->where('productID', $product->id)->delete();
                foreach ($validated['colors'] as $colorId) {
                    DB::table('product_color')->insert([
                        'productID' => $product->id,
                        'colorID' => $colorId,
                    ]);
                }
            }
            
            DB::commit();
            
            return redirect()->route('admin.products.index')->with('success', 'Sản phẩm đã được cập nhật thành công!');
            
        } catch (Exception $e) {
            DB::rollBack();
            
            // Log error for debugging
            \Log::error('Error updating product: ' . $e->getMessage(), [
                'exception' => $e,
                'request_data' => $request->except(['images']),
                'product_id' => $id
            ]);
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Có lỗi xảy ra khi cập nhật sản phẩm: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified product from storage.
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            
            // Find product
            $product = Products::find($id);
            
            if (!$product) {
                return redirect()->route('admin.products.index')
                    ->with('error', 'Sản phẩm không tồn tại!');
            }
            
            // Delete all product images and their files
            $productImages = ProductImage::where('product_id', $product->id)->get();
            foreach ($productImages as $image) {
                // Delete image file from storage
                if ($image->image && file_exists(public_path('img/product/' . $image->image))) {
                    unlink(public_path('img/product/' . $image->image));
                }
                // Delete record
                $image->delete();
            }
            
            // Delete main product image if exists
            if ($product->url_img && file_exists(public_path('img/product/' . $product->url_img))) {
                unlink(public_path('img/product/' . $product->url_img));
            }
            
            // Delete product categories (many-to-many relationship)
            ProductCategories::where('ProductID', $product->id)->delete();
            
            // Delete product colors (many-to-many relationship)
            ProductColor::where('productID', $product->id)->delete();
            
            // Delete product price sales
            ProductPriceSale::where('id_Product', $product->id)->delete();
            
            // Delete discounted combos
            DiscountedCombo::where('product_id', $product->id)->delete();
            
            // Delete product degree ranges
            ProductDegreeRange::where('product_id', $product->id)->delete();
            
            // Delete the product itself
            $product->delete();
            
            DB::commit();
            
            return redirect()->route('admin.products.index')
                ->with('success', 'Sản phẩm đã được xóa thành công!');
                
        } catch (Exception $e) {
            DB::rollBack();
            
            return redirect()->route('admin.products.index')
                ->with('error', 'Có lỗi xảy ra khi xóa sản phẩm: ' . $e->getMessage());
        }
    }
    
    /**
     * Get hierarchical categories with formatted names
     */
    private function getHierarchicalCategories()
    {
        // Get all parent categories (level 0)
        $parents = Category::where('type', 'product')
            ->where('hidden', 1)
            ->where('parent_id', 0)
            ->orderBy('weight', 'ASC')
            ->orderBy('name', 'ASC')
            ->get();
        
        $result = [];
        
        foreach ($parents as $parent) {
            // Add parent category
            $result[] = [
                'id' => $parent->id,
                'name' => $parent->name,
                'level' => 0,
                'parent_id' => 0,
                'formatted_name' => $parent->name
            ];
            
            // Get level 1 children
            $children = Category::where('type', 'product')
                ->where('hidden', 1)
                ->where('parent_id', $parent->id)
                ->orderBy('weight', 'ASC')
                ->orderBy('name', 'ASC')
                ->get();
            
            foreach ($children as $child) {
                // Add level 1 child
                $result[] = [
                    'id' => $child->id,
                    'name' => $child->name,
                    'level' => 1,
                    'parent_id' => $parent->id,
                    'formatted_name' => '|-->' . $child->name
                ];
                
                // Get level 2 children
                $grandChildren = Category::where('type', 'product')
                    ->where('hidden', 1)
                    ->where('parent_id', $child->id)
                    ->orderBy('weight', 'ASC')
                    ->orderBy('name', 'ASC')
                    ->get();
                
                foreach ($grandChildren as $grandChild) {
                    // Add level 2 child
                    $result[] = [
                        'id' => $grandChild->id,
                        'name' => $grandChild->name,
                        'level' => 2,
                        'parent_id' => $child->id,
                        'formatted_name' => '|---->' . $grandChild->name
                    ];
                }
            }
        }
        
        return $result;
    }
    
    /**
     * Handle image upload
     */
    public function uploadImage(Request $request)
    {
        $request->validate([
            'upload' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);
        
        if ($request->hasFile('upload')) {
            $image = $request->file('upload');
            $imageName = time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('img/product'), $imageName);
            
            return response()->json([
                'url' => asset('img/product/' . $imageName)
            ]);
        }
        
        return response()->json(['error' => 'Upload failed'], 400);
    }
    
    /**
     * Get cached categories with fallback
     */
    private function getCachedCategories()
    {
        try {
            // Try to use cache (database or file)
            return Cache::remember('admin_categories_hierarchical', 3600, function() {
                return $this->getHierarchicalCategories();
            });
        } catch (\Exception $e) {
            // Fallback: return without cache if cache is not available
            \Log::warning('Cache not available for categories, using direct query', ['error' => $e->getMessage()]);
            return $this->getHierarchicalCategories();
        }
    }
    
    /**
     * Get cached brands with fallback
     */
    private function getCachedBrands()
    {
        try {
            // Try to use cache (database or file)
            return Cache::remember('admin_brands_list', 3600, function() {
                return Brand::orderBy('name', 'ASC')->get();
            });
        } catch (\Exception $e) {
            // Fallback: return without cache if cache is not available
            \Log::warning('Cache not available for brands, using direct query', ['error' => $e->getMessage()]);
            return Brand::orderBy('name', 'ASC')->get();
        }
    }
}


