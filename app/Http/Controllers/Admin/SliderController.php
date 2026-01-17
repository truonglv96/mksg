<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Slider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class SliderController extends Controller
{
    /**
     * Display a listing of the sliders.
     */
    public function index(Request $request)
    {
        $search = $request->get('search', '');
        $sort = $request->get('sort', 'weight');
        
        // Build query
        $query = Slider::query();
        
        // Search
        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', '%' . $search . '%')
                  ->orWhere('alias', 'LIKE', '%' . $search . '%')
                  ->orWhere('content', 'LIKE', '%' . $search . '%');
            });
        }
        
        // Sort
        switch ($sort) {
            case 'name':
                $query->orderBy('name', 'ASC');
                break;
            case 'name_desc':
                $query->orderBy('name', 'DESC');
                break;
            case 'created_at':
                $query->orderBy('created_at', 'DESC');
                break;
            case 'hidden':
                $query->orderBy('hidden', 'ASC');
                break;
            case 'weight':
            default:
                $query->orderBy('weight', 'ASC');
                break;
        }
        
        $sliders = $query->paginate(20);
        
        // Get stats
        $totalSliders = Slider::count();
        $activeSliders = Slider::where('hidden', 1)->count();
        
        return view('admin.sliders.index', compact(
            'sliders',
            'totalSliders',
            'activeSliders',
            'search',
            'sort'
        ));
    }

    /**
     * Store a newly created slider in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'alias' => 'nullable|string|max:255',
                'content' => 'nullable|string',
                'weight' => 'nullable|integer|min:0',
                'hidden' => 'nullable|boolean',
                'image' => 'required|image|mimes:jpeg,jpg,png,gif|max:2048',
            ]);
            
            // Generate alias if not provided
            if (empty($validated['alias'])) {
                $validated['alias'] = Str::slug($validated['name']);
            }
            
            // Handle image upload
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
                $imagePath = public_path('img/slider');
                
                // Create directory if not exists
                if (!File::exists($imagePath)) {
                    File::makeDirectory($imagePath, 0755, true);
                }
                
                $image->move($imagePath, $imageName);
                $validated['url_img'] = $imageName;
            }
            
            // Set default values
            $validated['weight'] = $validated['weight'] ?? 0;
            $validated['hidden'] = $validated['hidden'] ?? 0;
            
            // Create slider
            Slider::create($validated);
            
            return response()->json([
                'success' => true,
                'message' => 'Slider đã được tạo thành công!'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi tạo slider!',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get a specific slider.
     */
    public function getSlider($id)
    {
        try {
            $slider = Slider::findOrFail($id);
            
            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $slider->id,
                    'name' => $slider->name,
                    'alias' => $slider->alias,
                    'content' => $slider->content,
                    'url_img' => $slider->url_img,
                    'image_url' => $slider->getImage(),
                    'weight' => $slider->weight,
                    'hidden' => $slider->hidden,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy slider!'
            ], 404);
        }
    }

    /**
     * Update the specified slider in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $slider = Slider::findOrFail($id);
            
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'alias' => 'nullable|string|max:255',
                'content' => 'nullable|string',
                'weight' => 'nullable|integer|min:0',
                'hidden' => 'nullable|boolean',
                'image' => 'nullable|image|mimes:jpeg,jpg,png,gif|max:2048',
            ]);
            
            // Generate alias if not provided
            if (empty($validated['alias'])) {
                $validated['alias'] = Str::slug($validated['name']);
            }
            
            // Handle image upload
            if ($request->hasFile('image')) {
                // Delete old image
                if ($slider->url_img && File::exists(public_path('img/slider/' . $slider->url_img))) {
                    File::delete(public_path('img/slider/' . $slider->url_img));
                }
                
                $image = $request->file('image');
                $imageName = time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
                $imagePath = public_path('img/slider');
                
                // Create directory if not exists
                if (!File::exists($imagePath)) {
                    File::makeDirectory($imagePath, 0755, true);
                }
                
                $image->move($imagePath, $imageName);
                $validated['url_img'] = $imageName;
            } else {
                // If no new image, don't update image field
                unset($validated['url_img']);
            }
            
            // Update slider
            $slider->update($validated);
            
            return response()->json([
                'success' => true,
                'message' => 'Slider đã được cập nhật thành công!'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi cập nhật slider!',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified slider from storage.
     */
    public function destroy(Request $request, $id)
    {
        try {
            $slider = Slider::findOrFail($id);
            
            // Delete image
            if ($slider->url_img && File::exists(public_path('img/slider/' . $slider->url_img))) {
                File::delete(public_path('img/slider/' . $slider->url_img));
            }
            
            // Delete slider
            $slider->delete();
            
            $successMessage = 'Slider đã được xóa thành công!';
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => $successMessage
                ]);
            }

            return redirect()
                ->route('admin.sliders.index')
                ->with('success', $successMessage);
        } catch (\Exception $e) {
            $message = 'Có lỗi xảy ra khi xóa slider!';
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $message
                ], 500);
            }

            return redirect()
                ->route('admin.sliders.index')
                ->with('error', $message);
        }
    }
}
