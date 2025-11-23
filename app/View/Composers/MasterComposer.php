<?php

namespace App\View\Composers;

use App\Models\Category;
use App\Models\Brand;
use App\Models\Setting;
use App\Models\Slider;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class MasterComposer
{
    /**
     * Bind data to the view.
     *
     * @param  \Illuminate\View\View  $view
     * @return void
     */
    public function compose(View $view)
    {
        // Cache data để tối ưu performance (cache 1 giờ)
        $cacheKey = 'master_layout_data';
        $data = Cache::remember($cacheKey, 3600, function () {
            // Lấy danh mục sản phẩm (menu) - sử dụng method có sẵn
            $categories = Category::getAllCategoriesParentIDMenu();

            // Lấy thương hiệu
            $brands = Brand::where('hidden', Brand::IS_ACTIVE)
                ->orderBy('weight', 'ASC')
                ->orderBy('id', 'ASC')
                ->get();

            // Lấy settings (logo, thông tin công ty, social icons)
            $settings = Setting::first();

            // Lấy sliders (nếu cần cho header hoặc banner)
            $sliders = Slider::getSliderWeb();

            return [
                'categories' => $categories,
                'brands' => $brands,
                'settings' => $settings,
                'sliders' => $sliders,
            ];
        });

        // Share data với view
        $view->with($data);
    }
}

