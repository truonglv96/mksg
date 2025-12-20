<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// use App\Models\News;

class NewsController extends Controller
{
    /**
     * Display a listing of the news.
     */
    public function index()
    {
        // TODO: Implement news listing logic
        // $news = News::orderBy('created_at', 'desc')->paginate(20);
        
        return view('admin.news.index');
    }

    /**
     * Show the form for creating a new news article.
     */
    public function create()
    {
        // TODO: Get news categories for form
        
        return view('admin.news.create');
    }

    /**
     * Store a newly created news article in storage.
     */
    public function store(Request $request)
    {
        // TODO: Implement news creation logic
        // Validate request
        // Create news
        // Handle image upload
        // Redirect with success message
        
        return redirect()->route('admin.news.index')->with('success', 'Tin tức đã được tạo thành công!');
    }

    /**
     * Display the specified news article.
     */
    public function show($id)
    {
        // TODO: Get news by id
        
        return view('admin.news.show', compact('id'));
    }

    /**
     * Show the form for editing the specified news article.
     */
    public function edit($id)
    {
        // TODO: Get news by id
        // Get news categories for form
        
        return view('admin.news.edit', compact('id'));
    }

    /**
     * Update the specified news article in storage.
     */
    public function update(Request $request, $id)
    {
        // TODO: Implement news update logic
        // Validate request
        // Update news
        // Handle image upload if any
        // Redirect with success message
        
        return redirect()->route('admin.news.index')->with('success', 'Tin tức đã được cập nhật thành công!');
    }

    /**
     * Remove the specified news article from storage.
     */
    public function destroy($id)
    {
        // TODO: Implement news deletion logic
        // Delete news
        // Delete related image
        // Redirect with success message
        
        return redirect()->route('admin.news.index')->with('success', 'Tin tức đã được xóa thành công!');
    }
}

