<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class NewsController extends Controller
{
    /**
     * Trang danh mục tin tức
     */
    public function category(Request $request, $categoryPath = null)
    {
        $perPage = 12;

        $currentCategory   = null; // category đang xem (theo URL)
        $typesBaseCategory = null; // category làm gốc cho nhóm filter (danh sách button)
        $newsTypesParentId = 0;    // parent_id truyền cho getTypeNewsSearch

        // Query mặc định: tất cả tin
        $newsQuery = News::where('hidden', 1);

        // Nếu có path category: /tin-tuc/kien-thuc/khuc-xa-mat-kinh
        if (!empty($categoryPath)) {
            $aliases = array_filter(explode('/', trim($categoryPath, '/')));
            $categories = [];

            foreach ($aliases as $alias) {
                $cat = Category::where([
                        ['alias', $alias],
                        ['type', 'new'],
                        ['hidden', 1],
                    ])->first();

                if ($cat) {
                    $categories[] = $cat;
                }
            }

            if ($categories) {
                $currentCategory = end($categories);
                $typesBaseCategory = count($categories) > 1
                    ? $categories[count($categories) - 2]   // cha trực tiếp
                    : $currentCategory;                     // chỉ 1 cấp

                // Nhóm filter: luôn là con của typesBaseCategory
                $newsTypesParentId = $typesBaseCategory->id;

                // ID category dùng để lọc news
                if (count($categories) === 1) {
                    // /tin-tuc/kien-thuc  → bài của KIẾN THỨC + con trực tiếp của nó
                    $categoryIds = Category::where('type', 'new')
                        ->where(function ($q) use ($currentCategory) {
                            $q->where('id', $currentCategory->id)
                              ->orWhere('parent_id', $currentCategory->id);
                        })
                        ->pluck('id')
                        ->toArray();
                } else {
                    // /tin-tuc/kien-thuc/khuc-xa-mat-kinh → chỉ bài thuộc category cuối
                    $categoryIds = [$currentCategory->id];
                }

                $newsQuery = News::select('news.*')
                    ->leftJoin('news_categories', 'news_categories.newsID', '=', 'news.id')
                    ->where('news.hidden', 1)
                    ->whereIn('news_categories.categoryID', $categoryIds)
                    ->distinct('news.id');
            }
        }

        // Filter theo từ khóa (chỉ theo tên bài viết)
        $keyword = trim((string) $request->query('keyword', ''));
        if ($keyword !== '') {
            $newsQuery->where(function ($q) use ($keyword) {
                $like = '%' . $keyword . '%';
                $q->where('name', 'like', $like);
            });
        }

        // Danh sách loại tin cho filter:
        // - Không có path: lấy category gốc (parent_id = 0)
        // - Có path: lấy category con của typesBaseCategory
        $newsTypes = Category::getTypeNewsSearch($newsTypesParentId);

        $news = $newsQuery
            ->orderBy('id', 'desc')
            ->orderBy('weight', 'asc')
            ->paginate($perPage)
            ->appends($request->query());

        // Tin nổi bật (dựa trên lượt xem nếu có, fallback theo id)
        $featuredNewsQuery = News::where('hidden', 1);
        if (Schema::hasColumn('news', 'view')) {
            $featuredNewsQuery->orderBy('view', 'desc');
        }
        $featuredNews = $featuredNewsQuery
            ->orderBy('id', 'desc')
            ->limit(3)
            ->get();

        // Tin đang được quan tâm (trending)
        $trendingNewsQuery = News::where('hidden', 1);
        if (Schema::hasColumn('news', 'view')) {
            $trendingNewsQuery->orderBy('view', 'desc');
        }
        $trendingNews = $trendingNewsQuery
            ->orderBy('id', 'desc')
            ->limit(5)
            ->get();

        return view('web.page.news.new-category', [
            'title'         => 'Tin Tức - Mắt Kính Sài Gòn',
            'type'          => 'category',
            'news'          => $news,
            'newsTypes'     => $newsTypes,
            'featuredNews'  => $featuredNews,
            'trendingNews'  => $trendingNews,
            'currentCategory' => $currentCategory,
            'typesBaseCategory' => $typesBaseCategory,
        ]);
    }

    /**
     * Trang chi tiết tin tức
     */
    public function detail($alias = null)
    {
        if (empty($alias)) {
            abort(404);
        }

        $news = News::getDetailNews($alias);

        if (!$news) {
            abort(404);
        }

        // Lấy category chính của bài viết (nếu có) để hiển thị
        $relatedCategory = News::getRelatedCategories($news->id);

        // Lấy danh sách tin liên quan theo category
        $relatedNews = collect();
        if ($relatedCategory) {
            $relatedNews = News::getRetedNewsByCate($relatedCategory->id)
                ->where('id', '!=', $news->id);
        }

        return view('web.page.news.detail', [
            'title'           => ($news->title ?? 'Chi Tiết Tin Tức') . ' - Mắt Kính Sài Gòn',
            'type'            => 'detail',
            'news'            => $news,
            'relatedNews'     => $relatedNews,
            'relatedCategory' => $relatedCategory,
        ]);
    }
}

