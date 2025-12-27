<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $table = 'category';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'alias',
        'parent_id',
        'total',
        'type',
        'kw',
        'des',
        'weight',
        'hidden',
        'index_hidden',
        'content',
        'icon',
        'show_icon',
    ];

    const IMAGE = 'img/color/';

    public function getIconImages(){
        return asset('img/color/'. $this->icon);
    }
    public function subcategory(){
        return $this->hasMany('App\Models\Category', 'parent_id');
    }
    public static function getByProductID($productID) {
        return  Category::join('product_categories', 'product_categories.CategoryID', '=', 'category.id')
                            ->where('product_categories.ProductID', $productID)
                            ->select('category.*', 'product_categories.ProductID', 'product_categories.CategoryID')
                            ->get();
    }

    public static function getByNewsID($newsID) {
        return  Category::join('news_categories', 'news_categories.categoryID', '=', 'category.id')
                            ->where('news_categories.newsID', $newsID)
                            ->select('category.*', 'news_categories.newsID', 'news_categories.CategoryID')
                            ->get();
    }
    // Admin

    // Web
    public static function getAllCategoriesParentIDMenu() {
        
        $parents = Category::where('parent_id', 0)->where('hidden', 1)->orderBy('weight', 'ASC')->get();
        $chillParent = [];
        foreach ($parents as $parent) {
            $chill = Category::where('parent_id', $parent['id'])
                ->where('hidden', 1)
                ->orderBy('weight', 'asc')
                ->get();
            $parent->chillParent = $chill;
            if($parent->chillParent) {
                $childLevelParent = [];
                foreach ($parent->chillParent as $item) {
                    $childLevel = Category::where('parent_id', $item['id'])
                        ->where('hidden', 1)
                        ->orderBy('weight', 'asc')
                        ->get();
                    $item->childLevelParent = $childLevel;
                }
            }
        }
        
        return $parents;
    }

    public static function getParamMenuCategoryID($id) {
        
        $parents = Category::where('parent_id', $id)->orderBy('weight', 'ASC')->get();
        $chillParent = [];
        foreach ($parents as $parent) {
            $chill = Category::where('parent_id', $parent['id'])
                ->where('hidden', 1)
                ->orderBy('weight', 'asc')
                ->get();
            $parent->chillParent = $chill;
            
        }
        
        return $parents;
    }
    public static function getAllParentCategoryByID() {
        return Category::where([['parent_id', 0], ['index_hidden', 1]])->orderBy('weight', 'asc')->get();
    }

    public static function getParentIDProduct($slugParent) {
        return Category::where('alias', $slugParent)->first();
    }

    public static function getCategorySlugParamMenu($paramMenu) {
        if($paramMenu['parentChillLevel']) {
            return Category::where('alias', $paramMenu['parentChillLevel'])->first();
        }elseif ($paramMenu['parentChill']) {
            return Category::where('alias', $paramMenu['parentChill'])->first();
        }elseif ($paramMenu['parent']) {
            return Category::where('alias', $paramMenu['parent'])->first();
        }
    }

    public static function getAllCategoryParentID($parentID) {
        $parents = Category::where('parent_id', $parentID)->where('type', 'product')->get();
        $chillParent = [];
        foreach ($parents as $parent) {
            $chill = Category::where('parent_id', $parent['id'])
                ->where('type', 'product')
                ->orderBy('weight', 'asc')
                ->get();
            $parent->chillParent = $chill;
            if($parent->chillParent) {
                $childLevelParent = [];
                foreach ($parent->chillParent as $item) {
                    $childLevel = Category::where('parent_id', $item['id'])
                        ->where('type', 'product')
                        ->orderBy('weight', 'asc')
                        ->get();
                    $item->childLevelParent = $childLevel;
                }
            }
        }
        return $parents;
    }

    public static function getAllCategoryParentIDNews($parentID) {
        $parents = Category::where('parent_id', $parentID)->where('type', 'new')->get();
        $chillParent = [];
        foreach ($parents as $parent) {
            $chill = Category::where('parent_id', $parent['id'])
                ->where('type', 'new')
                ->orderBy('weight', 'asc')
                ->get();
            $parent->chillParent = $chill;
            if($parent->chillParent) {
                $childLevelParent = [];
                foreach ($parent->chillParent as $item) {
                    $childLevel = Category::where('parent_id', $item['id'])
                        ->where('type', 'new')
                        ->orderBy('weight', 'asc')
                        ->get();
                    $item->childLevelParent = $childLevel;
                }
            }
        }
        return $parents;
    }

    // News
    public static function getTypeAlias($paramMenu) {
        if($paramMenu['parentChillLevel']) {
            return Category::where('type', 'new')->where('alias', $paramMenu['parentChillLevel'])->first();
        }elseif ($paramMenu['parentChill']) {
            return Category::where('type', 'new')->where('alias', $paramMenu['parentChill'])->first();
        }elseif ($paramMenu['parent']) {
            return Category::where('type', 'new')->where('alias', $paramMenu['parent'])->first();
        }
    }

    public static function getTypeSlugParent($alias) {
        return Category::where('alias', $alias)->first();
    }

    public static function getTypeNewsSearch($parentID) {
        $parents = Category::where('type', 'new')->where('parent_id', $parentID)->get();
        $chillParent = [];
        foreach ($parents as $parent) {
            $chill = Category::where('type', 'new')->where('parent_id', $parent['id'])
                ->where('hidden', 1)
                ->orderBy('weight', 'asc')
                ->get();
            $parent->chillParent = $chill;
        }
        return $parents;
    }


    public static function getCategoryAlias($alias) {
        return Category::where('alias', $alias)->first();
    }

    public static function getParent() {
        return static::where('parent_id', '=', 0)->where('type', 'product')->orderBy('weight', 'asc')->get();
    }

    /**
     * Lấy full path từ root đến category hiện tại
     */
    public function getFullPath() {
        $path = [$this->alias];
        $current = Category::find($this->parent_id);
        
        while ($current && $current->parent_id > 0) {
            array_unshift($path, $current->alias);
            $current = Category::find($current->parent_id);
        }
        
        if ($current && $current->parent_id == 0) {
            array_unshift($path, $current->alias);
        }
        
        return implode('/', $path);
    }
}