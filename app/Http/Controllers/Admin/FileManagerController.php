<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class FileManagerController extends Controller
{
    private const BASE_UPLOAD_DIR = 'upload';

    public function index(Request $request)
    {
        $relativePath = $this->sanitizePath($request->query('path', ''));
        $absolutePath = public_path(self::BASE_UPLOAD_DIR . ($relativePath ? '/' . $relativePath : ''));
        $returnUrl = $this->sanitizeReturnUrl($request->query('returnUrl', ''));

        if (!File::exists($absolutePath)) {
            File::makeDirectory($absolutePath, 0755, true);
        }

        $directories = collect(File::directories($absolutePath))
            ->map(fn ($dir) => basename($dir))
            ->sort()
            ->values();

        $files = collect(File::files($absolutePath))
            ->map(function ($file) use ($relativePath) {
                $filename = $file->getFilename();
                $relativeUrl = self::BASE_UPLOAD_DIR
                    . ($relativePath ? '/' . $relativePath : '')
                    . '/' . $filename;

                return [
                    'name' => $filename,
                    'url' => asset($relativeUrl),
                    'size' => $file->getSize(),
                    'extension' => strtolower($file->getExtension()),
                    'is_image' => in_array(strtolower($file->getExtension()), ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'], true),
                    'modified' => $file->getMTime(),
                ];
            })
            ->sortBy('name')
            ->values();

        return view('admin.file-manager.index', [
            'currentPath' => $relativePath,
            'directories' => $directories,
            'files' => $files,
            'returnUrl' => $returnUrl,
        ]);
    }

    public function upload(Request $request)
    {
        $relativePath = $this->sanitizePath($request->input('path', ''));
        $absolutePath = public_path(self::BASE_UPLOAD_DIR . ($relativePath ? '/' . $relativePath : ''));

        if (!File::exists($absolutePath)) {
            File::makeDirectory($absolutePath, 0755, true);
        }

        $request->validate([
            'upload' => 'required|file|max:5120|mimes:jpeg,jpg,png,gif,webp,svg,pdf,txt,doc,docx,xls,xlsx,ppt,pptx,zip'
        ]);

        $file = $request->file('upload');
        $extension = $file->getClientOriginalExtension();
        $filename = time() . '_' . Str::random(8) . ($extension ? '.' . $extension : '');
        $file->move($absolutePath, $filename);

        $url = asset(self::BASE_UPLOAD_DIR . ($relativePath ? '/' . $relativePath : '') . '/' . $filename);
        $funcNum = $request->input('CKEditorFuncNum');

        if ($funcNum) {
            $message = 'Tải lên thành công';
            $script = "<script>window.parent.CKEDITOR.tools.callFunction($funcNum, '$url', '$message');</script>";
            return response($script)->header('Content-Type', 'text/html; charset=utf-8');
        }

        return redirect()
            ->route('admin.file-manager.index', ['path' => $relativePath])
            ->with('success', 'Tải lên thành công.');
    }

    private function sanitizePath(?string $path): string
    {
        $path = trim((string) $path);
        $path = str_replace('\\', '/', $path);
        $path = trim($path, '/');
        $path = preg_replace('/\.+/', '.', $path);
        $path = preg_replace('/[^a-zA-Z0-9\/_-]/', '', $path);
        $segments = array_filter(explode('/', $path), fn ($segment) => $segment !== '.' && $segment !== '..' && $segment !== '');

        return implode('/', $segments);
    }

    private function sanitizeReturnUrl(?string $url): ?string
    {
        $url = trim((string) $url);
        if ($url === '') {
            return null;
        }

        $baseUrl = rtrim(url('/'), '/');
        if (!Str::startsWith($url, $baseUrl)) {
            return null;
        }

        return $url;
    }
}
