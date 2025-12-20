<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
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
        $categoryPathArray = [];
        if (!empty($categoryPath)) {
            $aliases = array_filter(explode('/', trim($categoryPath, '/')));
            $categoryPathArray = $aliases; // Lưu path array để truyền vào breadcrumb
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
            'categories' => $categories ?? [], // Truyền mảng categories để breadcrumb hiển thị đầy đủ path
            'categoryPathArray' => $categoryPathArray, // Truyền path array để breadcrumb có thể hiển thị đầy đủ ngay cả khi một số category không tìm thấy
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

        // Cache news detail query - sử dụng file cache nếu database cache không available
        $cacheKey = "news_detail_{$alias}";
        try {
            $news = Cache::remember($cacheKey, 3600, function () use ($alias) {
                return News::getDetailNews($alias);
            });
        } catch (\Exception $e) {
            // Fallback nếu cache không hoạt động
            $news = News::getDetailNews($alias);
        }

        if (!$news) {
            abort(404);
        }

        // Lấy category chính của bài viết (nếu có) để hiển thị - cache
        $categoryCacheKey = "news_category_{$news->id}";
        try {
            $relatedCategory = Cache::remember($categoryCacheKey, 3600, function () use ($news) {
                return News::getRelatedCategories($news->id);
            });
        } catch (\Exception $e) {
            // Fallback nếu cache không hoạt động
            $relatedCategory = News::getRelatedCategories($news->id);
        }

        // Lấy danh sách tin liên quan theo category - cache và limit
        $relatedNews = collect();
        if ($relatedCategory) {
            $relatedNewsCacheKey = "related_news_{$relatedCategory->id}_{$news->id}";
            try {
                $relatedNews = Cache::remember($relatedNewsCacheKey, 1800, function () use ($relatedCategory, $news) {
                    return News::getRetedNewsByCate($relatedCategory->id)
                        ->where('id', '!=', $news->id)
                        ->take(5); // Đảm bảo chỉ lấy 5 tin
                });
            } catch (\Exception $e) {
                // Fallback nếu cache không hoạt động
                $relatedNews = News::getRetedNewsByCate($relatedCategory->id)
                    ->where('id', '!=', $news->id)
                    ->take(5);
            }
        }

        // Pre-process data để giảm logic trong view
        $seoData = $this->prepareSeoData($news);
        $relatedNewsData = $this->prepareRelatedNewsData($relatedNews, $relatedCategory);

        // Xây dựng categories và categoryPathArray cho breadcrumb
        $categories = [];
        $categoryPathArray = [];
        if ($relatedCategory) {
            // Lấy full path từ category
            $categoryPath = method_exists($relatedCategory, 'getFullPath') ? $relatedCategory->getFullPath() : $relatedCategory->alias;
            $categoryPathArray = explode('/', trim($categoryPath, '/'));
            
            // Lấy tất cả categories trong path
            foreach ($categoryPathArray as $alias) {
                $cat = Category::where('alias', $alias)->where('type', 'new')->first();
                if ($cat) {
                    $categories[] = $cat;
                }
            }
        }

        return view('web.page.news.detail', [
            'title'           => ($news->title ?? $news->name ?? 'Chi Tiết Tin Tức') . ' - Mắt Kính Sài Gòn',
            'type'            => 'detail',
            'news'            => $news,
            'relatedNews'     => $relatedNews,
            'relatedCategory' => $relatedCategory,
            'seoData'         => $seoData,
            'relatedNewsData' => $relatedNewsData,
            'canonicalUrl'    => route('new.detail', [$news->alias]),
            'categories' => $categories, // Truyền mảng categories để breadcrumb hiển thị đầy đủ path
            'categoryPathArray' => $categoryPathArray, // Truyền path array để breadcrumb có thể hiển thị đầy đủ ngay cả khi một số category không tìm thấy
        ]);
    }

    /**
     * Chuẩn bị dữ liệu SEO để giảm logic trong view
     */
    private function prepareSeoData($news)
    {
        $seoDescription = $news->meta_description
            ?? $news->description
            ?? \Illuminate\Support\Str::limit(strip_tags($news->content ?? ''), 160);

        $seoKeywords = $news->kw
            ?? $news->keyword
            ?? $news->meta_keyword
            ?? null;

        $seoTitle = $news->title ?? $news->name ?? 'Tin Tức - Mắt Kính Sài Gòn';
        $canonicalUrl = route('new.detail', [$news->alias]);
        $imageUrl = method_exists($news, 'getImage') ? $news->getImage() : '';

        $schemaData = [
            '@context' => 'https://schema.org',
            '@type' => 'Article',
            'headline' => \Illuminate\Support\Str::limit(trim(strip_tags($seoTitle)), 110, ''),
            'description' => \Illuminate\Support\Str::limit(trim(strip_tags($seoDescription ?? '')), 160, ''),
            'datePublished' => optional($news->created_at)->toIso8601String(),
            'dateModified' => optional($news->updated_at ?? $news->created_at)->toIso8601String(),
            'author' => [
                '@type' => 'Person',
                'name' => $news->author ?? 'Mắt Kính Sài Gòn',
            ],
            'mainEntityOfPage' => [
                '@type' => 'WebPage',
                '@id' => $canonicalUrl,
            ],
            'publisher' => [
                '@type' => 'Organization',
                'name' => 'Mắt Kính Sài Gòn',
                'logo' => [
                    '@type' => 'ImageObject',
                    'url' => asset('img/setting/logo_mksg_2025.png'),
                ],
            ],
        ];

        if (!empty($imageUrl)) {
            $schemaData['image'] = $imageUrl;
        }

        return [
            'description' => $seoDescription,
            'keywords' => $seoKeywords,
            'title' => $seoTitle,
            'imageUrl' => $imageUrl,
            'canonicalUrl' => $canonicalUrl,
            'schemaData' => $schemaData,
        ];
    }

    /**
     * Chuẩn bị dữ liệu tin liên quan để giảm logic trong view
     */
    private function prepareRelatedNewsData($relatedNews, $relatedCategory)
    {
        $categoryName = $relatedCategory ? ($relatedCategory->name ?? $relatedCategory->title ?? 'Tin tức') : 'Tin tức';

        return $relatedNews->map(function ($item) use ($categoryName) {
            $createdAt = optional($item->created_at);
            $itemDate = $createdAt->isValid() ? $createdAt->format('d/m/Y') : null;
            $excerptSource = $item->description ?? $item->content ?? '';
            $excerpt = \Illuminate\Support\Str::limit(strip_tags($excerptSource), 90);
            $imageUrl = method_exists($item, 'getImage') ? $item->getImage() : null;
            $detailUrl = route('new.detail', $item->alias);

            return [
                'id' => $item->id,
                'title' => $item->title ?? $item->name,
                'alias' => $item->alias,
                'date' => $itemDate,
                'excerpt' => $excerpt,
                'imageUrl' => $imageUrl,
                'detailUrl' => $detailUrl,
                'categoryName' => $categoryName,
            ];
        });
    }
}

