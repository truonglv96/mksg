<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class PolicyController extends Controller
{
    /**
     * Display a listing of policies.
     */
    public function index(Request $request)
    {
        $search = trim((string) $request->get('search', ''));
        $status = $request->get('status', '');
        $sort = $request->get('sort', 'weight');

        $query = Page::where('type', 0);

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', '%' . $search . '%')
                    ->orWhere('link', 'LIKE', '%' . $search . '%')
                    ->orWhere('content', 'LIKE', '%' . $search . '%');
            });
        }
        
        if (in_array($status, ['0', '1'], true)) {
            $query->where('status', (int) $status);
        } else {
            $status = '';
        }

        switch ($sort) {
            case 'name':
                $query->orderBy('name', 'ASC');
                break;
            case 'name_desc':
                $query->orderBy('name', 'DESC');
                break;
            case 'updated_at':
                $query->orderBy('updated_at', 'DESC');
                break;
            case 'created_at':
                $query->orderBy('created_at', 'DESC');
                break;
            case 'weight':
            default:
                $sort = 'weight';
                $query->orderBy('weight', 'ASC');
                break;
        }
       
        $policies = $query->paginate(20)->withQueryString();
        $totalPolicies = Page::where('type', 0)->count();
        $activePolicies = Page::where('type', 0)->where('status', Page::IS_ACTIVE)->count();
        $inactivePolicies = Page::where('type', 0)->where('status', '!=', Page::IS_ACTIVE)->count();

        return view('admin.policies.index', compact(
            'policies',
            'totalPolicies',
            'activePolicies',
            'inactivePolicies',
            'search',
            'status',
            'sort'
        ));
    }

    /**
     * Show the form for creating a new policy.
     */
    public function create()
    {
        return view('admin.policies.create');
    }

    /**
     * Store a newly created policy in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'link' => 'nullable|string|max:255|unique:pages,link',
            'content' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,jpg,png,gif|max:2048',
            'weight' => 'nullable|integer|min:0',
        ]);

        if (empty($validated['link'])) {
            $validated['link'] = $this->generateUniqueLink($validated['name']);
        }

        $imageName = null;
        if ($request->hasFile('image')) {
            $imageName = $this->storeImage($request->file('image'));
        }

        $policy = new Page();
        $policy->fill($validated);
        $policy->image = $imageName;
        $policy->weight = $validated['weight'] ?? 0;
        $policy->status = $request->has('status') ? Page::IS_ACTIVE : 0;
        $policy->type = 0;
        $policy->save();

        return redirect()->route('admin.policies.index')
            ->with('success', 'Chính sách đã được tạo thành công!');
    }

    /**
     * Show the form for editing the specified policy.
     */
    public function edit($id)
    {
        $policy = Page::where('type', 0)->findOrFail($id);
        return view('admin.policies.edit', compact('policy'));
    }

    /**
     * Update the specified policy in storage.
     */
    public function update(Request $request, $id)
    {
        $policy = Page::where('type', 0)->findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'link' => 'nullable|string|max:255|unique:pages,link,' . $policy->id,
            'content' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,jpg,png,gif|max:2048',
            'weight' => 'nullable|integer|min:0',
            'delete_image' => 'nullable|boolean',
        ]);

        if (empty($validated['link'])) {
            $validated['link'] = $this->generateUniqueLink($validated['name'], $policy->id);
        }

        if ($request->has('delete_image') && $request->delete_image) {
            $this->deleteImageIfExists($policy->image);
            $policy->image = null;
        }

        if ($request->hasFile('image')) {
            $this->deleteImageIfExists($policy->image);
            $policy->image = $this->storeImage($request->file('image'));
        }

        $policy->fill($validated);
        $policy->weight = $validated['weight'] ?? $policy->weight ?? 0;
        $policy->status = $request->has('status') ? Page::IS_ACTIVE : 0;
        $policy->type = 0;
        $policy->save();

        return redirect()->route('admin.policies.index')
            ->with('success', 'Chính sách đã được cập nhật thành công!');
    }

    /**
     * Remove the specified policy from storage.
     */
    public function destroy(Request $request, $id)
    {
        $policy = Page::where('type', 0)->findOrFail($id);

        $this->deleteImageIfExists($policy->image);
        $policy->delete();

        $successMessage = 'Chính sách đã được xóa thành công!';
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => $successMessage,
            ]);
        }

        return redirect()->route('admin.policies.index')
            ->with('success', $successMessage);
    }

    private function generateUniqueLink(string $name, ?int $ignoreId = null): string
    {
        $base = Str::slug($name);
        $candidate = $base;
        $counter = 1;

        $baseQuery = Page::query();
        if ($ignoreId) {
            $baseQuery->where('id', '!=', $ignoreId);
        }

        while ((clone $baseQuery)->where('link', $candidate)->exists()) {
            $candidate = $base . '-' . $counter;
            $counter++;
        }

        return $candidate;
    }

    private function storeImage($file): string
    {
        $imagePath = public_path(Page::IMAGE);
        if (!File::exists($imagePath)) {
            File::makeDirectory($imagePath, 0755, true);
        }

        $imageName = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
        $file->move($imagePath, $imageName);

        return $imageName;
    }

    private function deleteImageIfExists(?string $image): void
    {
        if (!$image) {
            return;
        }

        $imagePath = public_path(Page::IMAGE . $image);
        if (File::exists($imagePath)) {
            File::delete($imagePath);
        }
    }
}
