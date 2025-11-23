<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    use HasFactory;
    protected $table = 'news';
    const IMAGE = 'img/news/';

    public function getImage() {
        return asset('img/news/'. $this->url_img);
    }

    public static function getAllNewsAdmin() {
        return News::orderBy('id', 'desc')->orderBy('weight', 'asc')->get();
    }

    public function categoriesNewsByID() {
        $categories =  Category::getByNewsID($this->id);
        $categoriesList = [];
        foreach ($categories as $category) {
            $categoriesList[$category->id] = $category;
        }
        return $categoriesList;
    
    }
    
    // Web

    public static function getNewsPageHome($limit) {
        return News::where('hidden', 1)->orderBy('id', 'desc')->orderBy('weight', 'asc')->limit($limit)->get();
    }

    public static function getNewsByType($typeID, $limit = 12) {
        return  News::select('news.*', 'category.name as typeName')
                ->leftjoin('news_categories', 'news_categories.newsID', '=', 'news.id')
                ->leftjoin('category', 'category.id', '=', 'news_categories.categoryID')
                ->where('category.id', '=', $typeID)
                ->orderBy('id', 'desc')
                ->paginate($limit);
    }


    public static function searchNewsByType($data, $limit = 12) {
        $sql = News::select('news.*', 'category.name as typeName')
            ->leftjoin('news_categories', 'news_categories.newsID', '=', 'news.id')
            ->leftjoin('category', 'category.id', '=', 'news_categories.categoryID');
            if(!empty($data)) {
                $sql->whereIn('category.id', $data);
            }
        return $sql->distinct('news.id')->orderBy('news.id', 'DESC')->paginate($limit);
    }

    public static function getDetailNews($alias) {
        return News::where('alias', $alias)->first();
    }


    public static function getRelatedCategories($idNews) {
        return News::select('category.*', 'news_categories.newsID', 'news_categories.categoryID')
            ->leftjoin('news_categories', 'news_categories.newsID', '=', 'news.id')
            ->leftjoin('category', 'category.id', '=', 'news_categories.categoryID')
            ->where('news_categories.newsID', '=', $idNews)
            ->where('category.parent_id', '!=', 0)
            ->first();
    }

    public static function getRetedNewsByCate($idCate) {
        return News::select('news.*', 'news_categories.newsID', 'news_categories.categoryID')
                    ->leftjoin('news_categories', 'news_categories.newsID', 'news.id')
                    ->where('news_categories.categoryID', $idCate)
                    ->orderBy('id', 'desc')
                    ->limit(5)
                    ->get();
    }
}
