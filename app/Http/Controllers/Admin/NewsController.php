<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\News;
use App\Models\Category;
use App\Models\NewsCategories;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class NewsController extends Controller
{
    /**
     * Display a listing of the news.
     */
    public function index(Request $request)
    {
        $search = $request->get('search', '');
        $sort = $request->get('sort', 'created_at');
        $status = $request->get('status', '');
        
        // Build query
        $query = News::query();
        
        // Search
        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', '%' . $search . '%')
                  ->orWhere('alias', 'LIKE', '%' . $search . '%')
                  ->orWhere('description', 'LIKE', '%' . $search . '%');
            });
        }
        
        // Filter by status
        if ($status !== '') {
            $query->where('hidden', $status == '1' ? 0 : 1);
        }
        
        // Sort
        switch ($sort) {
            case 'name':
                $query->orderBy('name', 'ASC');
                break;
            case 'name_desc':
                $query->orderBy('name', 'DESC');
                break;
            case 'views':
                $query->orderBy('views', 'DESC');
                break;
            case 'weight':
                $query->orderBy('weight', 'ASC');
                break;
            case 'created_at':
            default:
                $query->orderBy('created_at', 'DESC');
                break;
        }
        
        $news = $query->paginate(20);
        
        // Get stats
        $totalNews = News::count();
        $publishedNews = News::where('hidden', 0)->count();
        $draftNews = News::where('hidden', 1)->count();
        $totalViews = News::sum('views');
        
        return view('admin.news.index', compact(
            'news',
            'totalNews',
            'publishedNews',
            'draftNews',
            'totalViews',
            'search',
            'sort',
            'status'
        ));
    }

    /**
     * Show the form for creating a new news article.
     */
    public function create()
    {
        $categories = $this->getHierarchicalNewsCategories();
        return view('admin.news.create', compact('categories'));
    }

    /**
     * Store a newly created news article in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'alias' => 'nullable|string|max:255|unique:news,alias',
            'description' => 'nullable|string',
            'content' => 'nullable|string',
            'url_img' => 'nullable|image|mimes:jpeg,jpg,png,gif|max:2048',
            'weight' => 'nullable|integer|min:0',
            'hidden' => 'nullable|boolean',
            'kw' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'cat_id' => 'nullable|integer',
            'categories' => 'nullable|array',
            'categories.*' => 'integer|exists:category,id',
        ]);

        DB::beginTransaction();
        try {
            // Generate alias if not provided
            if (empty($validated['alias'])) {
                $validated['alias'] = Str::slug($validated['name']);

                // Ensure uniqueness
                $count = 1;
                $originalAlias = $validated['alias'];
                while (News::where('alias', $validated['alias'])->exists()) {
                    $validated['alias'] = $originalAlias . '-' . $count;
                    $count++;
                }
            }

            // Handle image upload
            if ($request->hasFile('url_img')) {
                $image = $request->file('url_img');
                $imageName = time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('img/news'), $imageName);
                $validated['url_img'] = $imageName;
            }

            // Set defaults
            $validated['weight'] = $validated['weight'] ?? 0;
            $validated['hidden'] = $request->has('hidden') ? ($validated['hidden'] ?? 0) : 0;
            $validated['views'] = 0;

            $news = News::create($validated);

            // Handle categories (many-to-many)
            if ($request->has('categories') && is_array($request->categories)) {
                foreach ($request->categories as $categoryId) {
                    NewsCategories::create([
                        'newsID' => $news->id,
                        'categoryID' => $categoryId,
                        'type' => 'new',
                    ]);
                }
            }

            DB::commit();

            $successMessage = 'Tin tức đã được tạo thành công!';
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => $successMessage,
                    'redirect' => route('admin.news.index')
                ]);
            }

            return redirect()->route('admin.news.index')
                ->with('success', $successMessage);
        } catch (\Exception $e) {
            DB::rollBack();
            $message = 'Có lỗi xảy ra: ' . $e->getMessage();
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $message
                ], 500);
            }

            return back()->withInput()->with('error', $message);
        }
    }

    /**
     * Display the specified news article.
     */
    public function show($id)
    {
        $news = News::findOrFail($id);
        return view('admin.news.show', compact('news'));
    }

    /**
     * Show the form for editing the specified news article.
     */
    public function edit($id)
    {
        $news = News::findOrFail($id);
        $categories = $this->getHierarchicalNewsCategories();
        
        // Get selected category IDs
        $newsCategoryIds = NewsCategories::where('newsID', $id)
            ->pluck('categoryID')
            ->toArray();
        
        return view('admin.news.edit', compact('news', 'categories', 'newsCategoryIds'));
    }

    /**
     * Update the specified news article in storage.
     */
    public function update(Request $request, $id)
    {
        $news = News::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'alias' => 'nullable|string|max:255|unique:news,alias,' . $id,
            'description' => 'nullable|string',
            'content' => 'nullable|string',
            'url_img' => 'nullable|image|mimes:jpeg,jpg,png,gif|max:2048',
            'weight' => 'nullable|integer|min:0',
            'hidden' => 'nullable|boolean',
            'kw' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'cat_id' => 'nullable|integer',
            'delete_image' => 'nullable|boolean',
            'categories' => 'nullable|array',
            'categories.*' => 'integer|exists:category,id',
        ]);
        
        // Handle image deletion
        if ($request->has('delete_image') && $request->delete_image) {
            if ($news->url_img && file_exists(public_path('img/news/' . $news->url_img))) {
                unlink(public_path('img/news/' . $news->url_img));
            }
            $validated['url_img'] = null;
        }
        
        // Handle image upload
        if ($request->hasFile('url_img')) {
            // Delete old image if exists
            if ($news->url_img && file_exists(public_path('img/news/' . $news->url_img))) {
                unlink(public_path('img/news/' . $news->url_img));
            }
            
            $image = $request->file('url_img');
            $imageName = time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('img/news'), $imageName);
            $validated['url_img'] = $imageName;
        } else {
            unset($validated['url_img']);
        }
        
        // Generate alias if not provided
        if (empty($validated['alias'])) {
            $validated['alias'] = Str::slug($validated['name']);
            
            // Ensure uniqueness
            $count = 1;
            $originalAlias = $validated['alias'];
            while (News::where('alias', $validated['alias'])->where('id', '!=', $id)->exists()) {
                $validated['alias'] = $originalAlias . '-' . $count;
                $count++;
            }
        }
        
        // Set defaults
        $validated['weight'] = $validated['weight'] ?? $news->weight ?? 0;
        $validated['hidden'] = $request->has('hidden') ? ($validated['hidden'] ?? 0) : $news->hidden ?? 0;
        
        $news->update($validated);
        
        // Handle categories (many-to-many)
        // Delete existing categories
        NewsCategories::where('newsID', $id)->delete();
        
        // Add new categories
        if ($request->has('categories') && is_array($request->categories)) {
            foreach ($request->categories as $categoryId) {
                NewsCategories::create([
                    'newsID' => $news->id,
                    'categoryID' => $categoryId,
                    'type' => 'new',
                ]);
            }
        }
        
        return redirect()->route('admin.news.index')
            ->with('success', 'Tin tức đã được cập nhật thành công!');
    }

    /**
     * Remove the specified news article from storage.
     */
    public function destroy(Request $request, $id)
    {
        $news = News::findOrFail($id);
        
        // Delete image file if exists
        if ($news->url_img && file_exists(public_path('img/news/' . $news->url_img))) {
            unlink(public_path('img/news/' . $news->url_img));
        }
        
        $news->delete();
        
        $successMessage = 'Tin tức đã được xóa thành công!';
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => $successMessage
            ]);
        }

        return redirect()->route('admin.news.index')
            ->with('success', $successMessage);
    }
    
    /**
     * Get hierarchical news categories with formatted names
     */
    private function getHierarchicalNewsCategories()
    {
        // Get all parent categories (level 0) for news
        $parents = Category::where('type', 'new')
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
            $children = Category::where('type', 'new')
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
                $grandChildren = Category::where('type', 'new')
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
}
