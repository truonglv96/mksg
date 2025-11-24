<?php

namespace App\View\Composers;

use App\Models\Category;
use App\Models\Brand;
use App\Models\Setting;
use App\Models\Slider;
use Illuminate\View\View;
use App\Models\Page;
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
        // Lấy data trực tiếp (có thể bật cache sau khi setup xong)
        $data = $this->getData();

        // Share data với view
        $view->with($data);
    }

    /**
     * Lấy data cho master layout
     *
     * @return array
     */
    private function getData()
    {
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
        $policies = Page::where('status', Page::IS_ACTIVE)->where('type', 0)->orderBy('weight', 'ASC')->get();
        return [
            'categories' => $categories,
            'brands' => $brands,
            'settings' => $settings,
            'sliders' => $sliders,
            'policies' => $policies,
        ];
    }
}

