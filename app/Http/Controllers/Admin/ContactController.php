<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Contact;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class ContactController extends Controller
{
    /**
     * Display a listing of the contacts (store information).
     */
    public function index(Request $request)
    {
        $search = $request->get('search', '');
        $sort = $request->get('sort', 'weight');
        
        // Build query
        $query = Contact::query();
        
        // Search
        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', '%' . $search . '%')
                  ->orWhere('phone', 'LIKE', '%' . $search . '%')
                  ->orWhere('address', 'LIKE', '%' . $search . '%');
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
            case 'status':
                $query->orderBy('status', 'ASC');
                break;
            case 'weight':
            default:
                $query->orderBy('weight', 'ASC');
                break;
        }
        
        $contacts = $query->paginate(20);
        
        // Get stats
        $totalContacts = Contact::count();
        $activeContacts = Contact::where('status', 1)->count();
        
        return view('admin.store-information.index', compact(
            'contacts',
            'totalContacts',
            'activeContacts',
            'search',
            'sort'
        ));
    }

    /**
     * Store a newly created contact in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'phone' => 'nullable|string|max:100',
                'address' => 'nullable|string|max:500',
                'strart_time' => 'nullable|string|max:100',
                'end_time' => 'nullable|string|max:100',
                'weight' => 'nullable|integer|min:0',
                'status' => 'nullable|boolean',
            ]);
            
            // Set default values
            $validated['weight'] = $validated['weight'] ?? 0;
            $validated['status'] = $validated['status'] ?? 0;
            
            // Create contact
            Contact::create($validated);
            
            return response()->json([
                'success' => true,
                'message' => 'Thông tin cửa hàng đã được tạo thành công!'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi tạo thông tin cửa hàng!',
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
     * Get a specific contact.
     */
    public function getContact($id)
    {
        try {
            $contact = Contact::findOrFail($id);
            
            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $contact->id,
                    'name' => $contact->name,
                    'phone' => $contact->phone,
                    'address' => $contact->address,
                    'strart_time' => $contact->strart_time,
                    'end_time' => $contact->end_time,
                    'weight' => $contact->weight,
                    'status' => $contact->status,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy thông tin cửa hàng!'
            ], 404);
        }
    }

    /**
     * Update the specified contact in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $contact = Contact::findOrFail($id);
            
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'phone' => 'nullable|string|max:100',
                'address' => 'nullable|string|max:500',
                'strart_time' => 'nullable|string|max:100',
                'end_time' => 'nullable|string|max:100',
                'weight' => 'nullable|integer|min:0',
                'status' => 'nullable|boolean',
            ]);
            
            // Update contact
            $contact->update($validated);
            
            return response()->json([
                'success' => true,
                'message' => 'Thông tin cửa hàng đã được cập nhật thành công!'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi cập nhật thông tin cửa hàng!',
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
     * Remove the specified contact from storage.
     */
    public function destroy($id)
    {
        try {
            $contact = Contact::findOrFail($id);
            
            // Delete contact
            $contact->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Thông tin cửa hàng đã được xóa thành công!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi xóa thông tin cửa hàng!'
            ], 500);
        }
    }
}

