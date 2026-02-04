<?php

namespace App\Helpers;

class BreadcrumbHelper
{
    public static function build(array $data = []): array
    {
        $items = [['label' => 'Trang chủ', 'url' => route('home'), 'icon' => true]];
        
        // Xác định type dựa trên URL hiện tại (ưu tiên hơn category type)
        $currentPath = request()->path();
        $type = 'product'; // Mặc định
        if (str_starts_with($currentPath, 'tin-tuc')) {
            $type = 'new';
        } elseif (str_starts_with($currentPath, 'bai-viet-san-pham')) {
            $type = 'product';
        } elseif (str_starts_with($currentPath, 'thuong-hieu')) {
            $type = 'brand';
        } elseif (str_starts_with($currentPath, 'doi-tac')) {
            $type = 'partner';
        } elseif (!empty($data['categories'])) {
            // Fallback: dùng type từ category nếu không xác định được từ URL
            $categories = $data['categories'];
            $isCollection = $categories instanceof \Illuminate\Support\Collection;
            $type = ($isCollection ? $categories->last() : end($categories))->type ?? 'product';
        } elseif (isset($data['category'])) {
            $type = $data['category']->type ?? 'product';
        }
        
        $categories = $data['categories'] ?? [];
        $isCollection = $categories instanceof \Illuminate\Support\Collection;
        $pathAliases = $data['categoryPathArray'] ?? ($isCollection ? $categories->pluck('alias')->toArray() : array_column($categories, 'alias'));
        $categoryMap = $isCollection 
            ? $categories->keyBy('alias')->toArray() 
            : (!empty($categories) ? array_column($categories, null, 'alias') : []);
        $hasProductOrNews = isset($data['product']) || isset($data['news']);
        $hasCategories = !empty($pathAliases);
        
        if (!empty($pathAliases)) {
            foreach ($pathAliases as $index => $alias) {
                $cat = $categoryMap[$alias] ?? null;
                
                // Nếu không tìm thấy trong map, thử tìm lại từ database (không cần điều kiện hidden để lấy được name có dấu)
                if (!$cat) {
                    // Thử tìm với type trước
                    $cat = \App\Models\Category::where('alias', $alias)->where('type', $type)->first();
                    // Nếu không tìm thấy, thử tìm không cần type
                    if (!$cat) {
                        $cat = \App\Models\Category::where('alias', $alias)->first();
                    }
                }
                
                $isLast = ($index === count($pathAliases) - 1) && !$hasProductOrNews;
                $path = implode('/', array_slice($pathAliases, 0, $index + 1));
                
                // Ưu tiên lấy name từ database, nếu không có mới format từ alias
                $label = null;
                if ($cat) {
                    $label = $cat->name ?? $cat->title ?? null;
                }
                
                // Nếu không có label từ database, bỏ qua item này (không format từ alias vì sẽ mất dấu)
                if (empty($label) || $label === 'Danh mục') {
                    continue;
                }
                
                $routeName = $type === 'new' ? 'new.category.path' : 'product.category.path';
                $routeParam = $type === 'new' ? ['categoryPath' => $path] : ['segments' => $path];
                
                $items[] = ['label' => $label, 'url' => $isLast ? null : route($routeName, $routeParam)];
            }
        } elseif (isset($data['category']) && $data['category']) {
            $category = $data['category'];
            $path = method_exists($category, 'getFullPath') ? $category->getFullPath() : $category->alias;
            $type = $category->type ?? 'product';
            $routeName = $type === 'new' ? 'new.category.path' : 'product.category.path';
            $routeParam = $type === 'new' ? ['categoryPath' => $path] : ['segments' => $path];
            $label = $category->name ?? $category->title ?? null;
            if ($label && $label !== 'Danh mục') {
                $items[] = [
                    'label' => $label, 
                    'url' => $hasProductOrNews ? route($routeName, $routeParam) : null
                ];
            }
        }
        
        // Handle brand breadcrumb
        if ($type === 'brand' && isset($data['brand'])) {
            $brand = $data['brand'];
            $items[] = ['label' => 'Thương hiệu', 'url' => route('brand.index')];
            $items[] = ['label' => $brand->name ?? 'Thương hiệu', 'url' => null];
        } elseif ($type === 'brand' && !isset($data['brand'])) {
            $items[] = ['label' => 'Thương hiệu', 'url' => null];
        }
        
        // Handle partner breadcrumb
        if ($type === 'partner' && isset($data['partner'])) {
            $partner = $data['partner'];
            $items[] = ['label' => 'Đối tác', 'url' => route('partner.index')];
            $items[] = ['label' => $partner->name ?? 'Đối tác', 'url' => null];
        } elseif ($type === 'partner' && !isset($data['partner'])) {
            $items[] = ['label' => 'Đối tác', 'url' => null];
        }
        
        // Chỉ thêm current page item nếu chưa có item nào là current page và không phải là category page
        if (!collect($items)->contains(fn($item) => !($item['url'] ?? null)) && !$hasCategories && !isset($data['category']) && $type !== 'brand' && $type !== 'partner') {
            if (isset($data['product'])) {
                $items[] = ['label' => $data['product']->name ?? 'Sản phẩm', 'url' => null];
            } elseif (isset($data['news'])) {
                $items[] = ['label' => $data['news']->title ?? $data['news']->name ?? 'Tin tức', 'url' => null];
            } elseif (isset($data['pageTitle'])) {
                $items[] = ['label' => $data['pageTitle'], 'url' => null];
            } elseif (isset($data['title']) && !isset($data['product']) && !isset($data['news'])) {
                $items[] = ['label' => $data['title'], 'url' => null];
            }
        }
        
        return $items;
    }
}

