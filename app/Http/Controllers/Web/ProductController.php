<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Products;
use App\Models\Category;
use App\Models\Color;
use App\Models\Material;
use App\Models\Brand;
use App\Models\DiscountedCombo;
use App\Models\Area;
use App\Models\ClientInformation;
use App\Models\Bill as BillDetail;
use App\Mail\OrderConfirmationMail;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;

class ProductController extends Controller
{
    public function category(Request $request)
    {
        $categoryAlias = $request->get('category');
        $category = $categoryAlias ? Category::where('alias', $categoryAlias)->where('type', 'product')->where('hidden', 1)->first() : null;
        $products = $category ? Products::getProductByCategory($category->id, 12) : Products::where('hidden', 1)->orderBy('id', 'DESC')->orderBy('weight', 'ASC')->paginate(12);
        
        return view('web.page.products.product-category', [
            'title' => ($category ? $category->name : 'Sản Phẩm') . ' - Mắt Kính Sài Gòn',
            'category' => $category,
            'products' => $products,
            'totalProducts' => $products->total(),
        ]);
    }

    private function getCategoryIdsRecursive($categoryId) {
        $ids = [$categoryId];
        foreach (Category::where('parent_id', $categoryId)->where('type', 'product')->where('hidden', 1)->pluck('id') as $childId) {
            $ids = array_merge($ids, $this->getCategoryIdsRecursive($childId));
        }
        return $ids;
    }

    public function categoryByAlias($alias)
    {
        $category = Category::where('alias', $alias)->where('type', 'product')->where('hidden', 1)->first();
        if (!$category) {
            $products = Products::where('hidden', 1)->orderBy('id', 'DESC')->orderBy('weight', 'ASC')->paginate(12);
            return view('web.page.products.product-category', ['title' => 'Sản Phẩm - Mắt Kính Sài Gòn', 'category' => null, 'products' => $products, 'totalProducts' => $products->total()]);
        }
        
        $categoryIds = $this->getCategoryIdsRecursive($category->id);
        $products = Products::select('products.*', 'category.name as cateName', 'category.id as cateID', 'category.alias as cateAlias')
            ->join('product_categories', 'product_categories.productID', '=', 'products.id')
            ->join('category', 'category.id', '=', 'product_categories.CategoryID')
            ->whereIn('category.id', $categoryIds)->where('products.hidden', 1)
            ->orderBy('products.id', 'DESC')->orderBy('products.weight', 'ASC')
            ->distinct('products.id')->paginate(12);
        
        return view('web.page.products.product-category', [
            'title' => $category->name . ' - Mắt Kính Sài Gòn',
            'category' => $category,
            'products' => $products,
            'totalProducts' => $products->total(),
        ]);
    }

    public function categoryPath($segments)
    {
        if (empty($segments)) return $this->category(request());
        
        $pathArray = explode('/', trim($segments, '/'));
        $categories = [];
        foreach ($pathArray as $alias) {
            // Tìm category theo alias, type = product, và hidden = 1
            $cat = Category::where('alias', $alias)->where('type', 'product')->where('hidden', 1)->first();
            if ($cat) {
                $categories[] = $cat;
            } else {
                // Debug: Log nếu không tìm thấy category
                \Log::warning("Category not found: alias={$alias}, path={$segments}");
            }
        }
        
        // Debug: Log số lượng categories tìm được
        \Log::info("CategoryPath Debug", [
            'segments' => $segments,
            'pathArray' => $pathArray,
            'categories_count' => count($categories),
            'category_aliases' => array_map(fn($c) => $c->alias, $categories)
        ]);
        
        if (empty($categories)) {
            $product = Products::where('alias', end($pathArray))->where('hidden', 1)->first();
            if ($product) {
                $productCategoryPath = $product->getCategoryPath();
                return redirect()->route('product.detail', [
                    'categoryPath' => $productCategoryPath,
                    'productAlias' => $product->alias
                ]);
            }
            abort(404);
        }
        
        $lastCategory = end($categories);
        $firstCategory = reset($categories);
        $categoryIds = array_column($categories, 'id');
        $sort = request('sort', 'newest');
        $filters = [
            'price_min' => request('price_min'),
            'price_max' => request('price_max'),
            'color_id' => request('color_id'),
            'material_id' => request('material_id'),
            'brand_id' => request('brand_id'),
        ];
        $products = Products::getProductByCategoryPath($categoryIds, $lastCategory->id, 12, $sort, $filters);
        
        // Lấy filters theo category path
        $colors = collect();
        $priceRanges = [];
        $materials = collect();
        $brands = collect();
        
        if ($firstCategory) {
            // Lấy price ranges từ category cha
            $priceRanges = Products::getPriceRangesByCategoryId($firstCategory->id);
            
            // Lấy materials theo category path (để đếm đúng)
            $materials = Material::getMaterialsByCategoryPath($categoryIds);
            
            // Lấy product IDs thuộc category cuối cùng
            $productIds = Products::select('products.id')
                ->join('product_categories', 'product_categories.productID', '=', 'products.id')
                ->where('product_categories.CategoryID', $lastCategory->id)
                ->where('products.hidden', 1)
                ->distinct('products.id')
                ->pluck('id');
            
            // Lấy brands từ products thuộc category cuối cùng
            $brands = Brand::getBrandsByCategoryPath($categoryIds, $lastCategory->id);
            
            // Lấy colors từ products thuộc category cuối cùng
            $colors = Color::getColorsByProductIds($productIds);
        }
        
        return view('web.page.products.product-category', [
            'title' => $lastCategory->name . ' - Mắt Kính Sài Gòn',
            'category' => $lastCategory,
            'categories' => $categories, // Truyền mảng categories để breadcrumb hiển thị đầy đủ path
            'categoryPathArray' => $pathArray, // Truyền path array để breadcrumb có thể hiển thị đầy đủ ngay cả khi một số category không tìm thấy
            'products' => $products,
            'totalProducts' => $products->total(),
            'colors' => $colors,
            'priceRanges' => $priceRanges,
            'materials' => $materials,
            'brands' => $brands,
        ]);
    }

    public function detail($categoryPath, $productAlias)
    {
        // Kiểm tra xem productAlias có phải là category alias không
        // Nếu có, redirect đến categoryPath
        $category = Category::where('alias', $productAlias)
            ->where('type', 'product')
            ->where('hidden', 1)
            ->first();
        
        if ($category) {
            // Nếu productAlias là category, xử lý như category path
            $fullPath = $categoryPath . '/' . $productAlias;
            return $this->categoryPath($fullPath);
        }
        
        $product = Products::with('brand')->where('alias', $productAlias)->where('hidden', 1)->firstOrFail();
        
        // Verify và redirect nếu category path không đúng
        $productCategoryPath = $product->getCategoryPath();
        if ($productCategoryPath !== $categoryPath) {
            return redirect()->route('product.detail', [
                'categoryPath' => $productCategoryPath,
                'productAlias' => $productAlias
            ], 301);
        }
        
        // Lấy dữ liệu liên quan
        $categories = $product->categoriesProductByID();
        $mainCategory = !empty($categories) ? reset($categories) : null;
        
        // Decode JSON fields
        $jsonFields = ['content', 'tech', 'service', 'tutorial', 'address_sale', 'open_time', 'description'];
        $decodedData = [];
        foreach ($jsonFields as $field) {
            $decodedData[$field] = $product->$field ? json_decode($product->$field) : null;
        }
        // dd($product);
        return view('web.page.products.detail', array_merge([
            'title' => $product->name . ' - Mắt Kính Sài Gòn',
            'product' => $product,
            'productImages' => \App\Models\ProductImage::getImageCategoryProduct($product->id),
            'productColors' => Color::getColorByPhotoID($product->id),
            'mainCategory' => $mainCategory,
            'brand' => $product->brand,
            'relatedProducts' => $mainCategory ? Products::getProductOrtherByIDCategory($mainCategory->id)
                ->filter(fn($item) => $item->id != $product->id)->take(6) : collect(),
            'discountedCombos' => DiscountedCombo::where('product_id', $product->id)
                ->orderBy('weight', 'ASC')
                ->get(),
            'productPriceSales' => \App\Models\ProductPriceSale::where('id_Product', $product->id)
                ->with(['category', 'mainCategory'])
                ->orderBy('order', 'ASC')
                ->get(),
            'productDegreeRanges' => $productDegreeRanges = \App\Models\ProductDegreeRange::where('product_id', $product->id)
                ->orderBy('weight', 'ASC')
                ->orderBy('id', 'ASC')
                ->get(),
            'productFeatures' => \App\Models\FeaturesProduct::whereIn('id', is_array($product->id_features_product) ? $product->id_features_product : [])
                ->orderBy('id', 'ASC')
                ->get(),
        ], $decodedData));
        
        // Debug: Log để kiểm tra data
        \Log::info('Product Detail - Degree Ranges', [
            'product_id' => $product->id,
            'productDegreeRanges_count' => $productDegreeRanges->count(),
            'productDegreeRanges' => $productDegreeRanges->map(function($item) {
                return [
                    'id' => $item->id,
                    'product_id' => $item->product_id,
                    'name' => $item->name,
                    'price' => $item->price,
                    'price_sale' => $item->price_sale,
                    'weight' => $item->weight,
                ];
            })->toArray()
        ]);
    }

    public function shoppingCart()
    {
        // Lấy danh sách thành phố từ database (parent_id = 0)
        $cities = Area::where('parent_id', 0)
            ->orderByDesc('weight')
            ->get();
        
        // Lấy tất cả quận/huyện (parent_id != 0) với parent_id để filter
        $districts = Area::where('parent_id', '!=', 0)
            ->orderByDesc('weight')
            ->get(['id', 'name', 'parent_id']);
        
        return view('web.page.products.shopping-cart', [
            'title' => 'Giỏ Hàng - Mắt Kính Sài Gòn',
            'cities' => $cities,
            'districts' => $districts
        ]);
    }

    public function checkout(Request $request)
    {
        // Validate form data
        $validated = $request->validate([
            'name' => 'required|string|max:191',
            'gender' => 'required|string|in:nam,nu,khac',
            'phone' => 'required|string|max:191',
            'email' => 'required|email|max:191',
            'city' => 'required|integer',
            'district' => 'required|integer',
            'address' => 'required|string|max:191',
            'note' => 'nullable|string',
            'payment-method' => 'required|string|in:bank,cod,store',
            'cart' => 'required|array|min:1',
            'cart.*.id' => 'required|integer',
            'cart.*.name' => 'required|string',
            'cart.*.price' => 'required|integer',
            'cart.*.quantity' => 'required|integer|min:1',
            'cart.*.category' => 'nullable|string',
            'cart.*.sale_off' => 'nullable|integer',
            'cart.*.color_id' => 'nullable|integer',
            'cart.*.lensLabel' => 'nullable|string',
            'cart.*.selectedOptions' => 'nullable|array',
        ]);

        try {
            // Generate unique bill code
            $codeBill = 'BILL-' . strtoupper(Str::random(8)) . '-' . date('YmdHis');

            // Map gender to integer (nam=1, nu=2, khac=0)
            $sexMap = ['nam' => 1, 'nu' => 2, 'khac' => 0];
            $sex = $sexMap[$validated['gender']] ?? 0;

            // Create bill (ClientInformation)
            $bill = ClientInformation::create([
                'name' => $validated['name'],
                'address' => $validated['address'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'sex' => $sex,
                'city' => $validated['city'],
                'district' => $validated['district'],
                'ship' => null, // Có thể thêm sau
                'note' => $validated['note'] ?? null,
                'code_bill' => $codeBill,
                'payment' => $validated['payment-method'],
                'status' => 'pending', // Trạng thái mặc định
            ]);

            // Create bill details
            foreach ($validated['cart'] as $item) {
                // Lấy category_name từ product nếu chưa có trong cart
                $categoryName = $item['category'] ?? null;
                if (!$categoryName && isset($item['id'])) {
                    $product = Products::find($item['id']);
                    if ($product && $product->category) {
                        $categoryName = $product->category->name ?? null;
                    }
                }

                // Lưu lensLabel (selectedOptions) vào category_name nếu có
                $lensLabel = $item['lensLabel'] ?? null;
                if (!$lensLabel && isset($item['selectedOptions']) && is_array($item['selectedOptions'])) {
                    $lensLabel = implode(', ', $item['selectedOptions']);
                }
                $finalCategoryName = $lensLabel ?: $categoryName;

                BillDetail::create([
                    'bill_id' => $bill->id,
                    'product_id' => $item['id'],
                    'category_name' => $finalCategoryName,
                    'sale_off' => $item['sale_off'] ?? null,
                    'price' => $item['price'],
                    'qty' => $item['quantity'],
                    'color_id' => $item['color_id'] ?? null,
                ]);
            }

            // Load bill details với product information
            $billDetails = BillDetail::where('bill_id', $bill->id)
                ->with(['product.brand', 'product.colors'])
                ->get();

            // Tính tổng tiền
            $totalAmount = $billDetails->sum(function($detail) {
                return ($detail->price - ($detail->sale_off ?? 0)) * $detail->qty;
            });

            // Gửi email xác nhận đơn hàng
            try {
                \Log::info('Attempting to send order confirmation email to: ' . $bill->email);
                \Log::info('Mail driver: ' . config('mail.default'));
                
                Mail::to($bill->email)->send(new OrderConfirmationMail($bill, $billDetails, $totalAmount));
                
                \Log::info('Order confirmation email sent successfully to: ' . $bill->email);
            } catch (\Exception $e) {
                \Log::error('Email sending error: ' . $e->getMessage());
                \Log::error('Email sending error trace: ' . $e->getTraceAsString());
                // Không throw error để không ảnh hưởng đến việc tạo đơn hàng
            }

            return response()->json([
                'success' => true,
                'message' => 'Đơn hàng đã được tạo thành công!',
                'code_bill' => $codeBill,
                'bill_id' => $bill->id
            ]);

        } catch (\Exception $e) {
            \Log::error('Checkout error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi tạo đơn hàng. Vui lòng thử lại.'
            ], 500);
        }
    }
}

