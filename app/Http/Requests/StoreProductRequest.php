<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'alias' => 'nullable|string|max:255|unique:products,alias',
            'code_sp' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'content' => 'nullable|string',
            'tech' => 'nullable|string',
            'service' => 'nullable|string',
            'tutorial' => 'nullable|string',
            'address_sale' => 'nullable|string',
            'open_time' => 'nullable|string',
            'price' => 'nullable|numeric|min:0',
            'price_sale' => 'nullable|numeric|min:0',
            'url_img' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'brand_id' => 'nullable|exists:brand,id',
            'material_id' => 'nullable|exists:material,id',
            'hidden' => 'nullable|in:0,1',
            'weight' => 'nullable|integer|min:0',
            'unit' => 'nullable|string|max:50',
            'type_color' => 'nullable|integer|in:0,1',
            'type_sale' => 'nullable|integer|in:0,1,2',
            'gender' => 'nullable|string|in:all,male,female,children',
            'kw' => 'nullable|string|max:255',
            'meta_des' => 'nullable|string|max:500',
            'categories' => 'nullable|array',
            'categories.*' => 'exists:category,id',
            'colors' => 'nullable|array',
            'colors.*' => 'exists:color,id',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'sale_prices' => 'nullable|array',
            'sale_prices.*.category1' => 'nullable|exists:category,id',
            'sale_prices.*.category2' => 'nullable|exists:category,id',
            'sale_prices.*.discount_price' => 'nullable|numeric|min:0',
            'combos' => 'nullable|array',
            'combos.*.name' => 'nullable|string|max:255',
            'combos.*.price' => 'nullable|numeric|min:0',
            'combos.*.weight' => 'nullable|integer|min:0',
            'combos.*.description' => 'nullable|string',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Tên sản phẩm là bắt buộc.',
            'name.max' => 'Tên sản phẩm không được vượt quá 255 ký tự.',
            'alias.unique' => 'Alias đã tồn tại. Vui lòng chọn alias khác.',
            'alias.max' => 'Alias không được vượt quá 255 ký tự.',
            'price.numeric' => 'Giá phải là số.',
            'price.min' => 'Giá không được nhỏ hơn 0.',
            'price_sale.numeric' => 'Giá bán phải là số.',
            'price_sale.min' => 'Giá bán không được nhỏ hơn 0.',
            'url_img.image' => 'File phải là hình ảnh.',
            'url_img.mimes' => 'Hình ảnh phải có định dạng: jpeg, png, jpg, gif.',
            'url_img.max' => 'Kích thước hình ảnh không được vượt quá 2MB.',
            'brand_id.exists' => 'Thương hiệu không tồn tại.',
            'material_id.exists' => 'Chất liệu không tồn tại.',
            'hidden.in' => 'Giá trị ẩn/hiện không hợp lệ.',
            'weight.integer' => 'Thứ tự hiển thị phải là số nguyên.',
            'weight.min' => 'Thứ tự hiển thị không được nhỏ hơn 0.',
            'unit.max' => 'Đơn vị tính không được vượt quá 50 ký tự.',
            'type_color.in' => 'Loại màu sắc không hợp lệ.',
            'type_sale.in' => 'Hình thức bán không hợp lệ.',
            'gender.in' => 'Giới tính không hợp lệ.',
            'categories.array' => 'Danh mục phải là mảng.',
            'categories.*.exists' => 'Một hoặc nhiều danh mục không tồn tại.',
            'colors.array' => 'Màu sắc phải là mảng.',
            'colors.*.exists' => 'Một hoặc nhiều màu sắc không tồn tại.',
            'images.array' => 'Hình ảnh phải là mảng.',
            'images.*.image' => 'Tất cả các file phải là hình ảnh.',
            'images.*.mimes' => 'Hình ảnh phải có định dạng: jpeg, png, jpg, gif.',
            'images.*.max' => 'Kích thước mỗi hình ảnh không được vượt quá 2MB.',
            'sale_prices.array' => 'Giá sale phải là mảng.',
            'sale_prices.*.category1.exists' => 'Danh mục 1 không tồn tại.',
            'sale_prices.*.category2.exists' => 'Danh mục 2 không tồn tại.',
            'sale_prices.*.discount_price.numeric' => 'Giá giảm phải là số.',
            'sale_prices.*.discount_price.min' => 'Giá giảm không được nhỏ hơn 0.',
            'combos.array' => 'Combo phải là mảng.',
            'combos.*.name.max' => 'Tên combo không được vượt quá 255 ký tự.',
            'combos.*.price.numeric' => 'Giá combo phải là số.',
            'combos.*.price.min' => 'Giá combo không được nhỏ hơn 0.',
            'combos.*.weight.integer' => 'Thứ tự combo phải là số nguyên.',
            'combos.*.weight.min' => 'Thứ tự combo không được nhỏ hơn 0.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'name' => 'tên sản phẩm',
            'alias' => 'alias',
            'code_sp' => 'mã sản phẩm',
            'description' => 'mô tả',
            'content' => 'nội dung',
            'tech' => 'thông số kỹ thuật',
            'service' => 'tư vấn sử dụng',
            'tutorial' => 'hướng dẫn mua hàng',
            'address_sale' => 'địa chỉ mua hàng',
            'open_time' => 'thời gian mở cửa',
            'price' => 'giá gốc',
            'price_sale' => 'giá bán',
            'url_img' => 'hình ảnh chính',
            'brand_id' => 'thương hiệu',
            'material_id' => 'chất liệu',
            'hidden' => 'trạng thái',
            'weight' => 'thứ tự hiển thị',
            'unit' => 'đơn vị tính',
            'type_color' => 'loại màu sắc',
            'type_sale' => 'hình thức bán',
            'gender' => 'giới tính',
            'categories' => 'danh mục',
            'colors' => 'màu sắc',
            'images' => 'hình ảnh',
            'sale_prices' => 'giá sale',
            'combos' => 'combo',
        ];
    }
}

