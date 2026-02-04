<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Trình quản lý tệp</title>
    <style>
        body { font-family: Arial, Helvetica, sans-serif; margin: 0; background: #f7f7f8; color: #1f2937; }
        .header { padding: 16px 20px; background: #111827; color: #fff; display: flex; justify-content: space-between; align-items: center; }
        .header h1 { margin: 0; font-size: 18px; }
        .container { padding: 20px; }
        .toolbar { display: flex; flex-wrap: wrap; gap: 12px; align-items: center; margin-bottom: 16px; }
        .breadcrumb { font-size: 13px; color: #6b7280; }
        .breadcrumb a { color: #2563eb; text-decoration: none; }
        .grid { display: grid; grid-template-columns: 220px 1fr; gap: 16px; }
        .panel { background: #fff; border-radius: 8px; padding: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.08); }
        .panel h2 { font-size: 14px; margin: 0 0 8px; color: #111827; }
        .folder-list { list-style: none; padding: 0; margin: 0; }
        .folder-list li { margin: 6px 0; }
        .folder-list a { color: #111827; text-decoration: none; display: block; padding: 6px 8px; border-radius: 6px; }
        .folder-list a:hover { background: #f3f4f6; }
        .file-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(160px, 1fr)); gap: 12px; }
        .file-card { border: 1px solid #e5e7eb; border-radius: 8px; padding: 10px; background: #fff; display: flex; flex-direction: column; gap: 8px; }
        .file-thumb { height: 100px; display: flex; align-items: center; justify-content: center; background: #f3f4f6; border-radius: 6px; overflow: hidden; }
        .file-thumb img { max-width: 100%; max-height: 100%; }
        .file-name { font-size: 12px; color: #111827; word-break: break-word; }
        .file-meta { font-size: 11px; color: #6b7280; }
        .btn { border: 0; padding: 6px 10px; border-radius: 6px; cursor: pointer; font-size: 12px; }
        .btn-primary { background: #2563eb; color: #fff; }
        .btn-secondary { background: #e5e7eb; color: #111827; }
        .upload-form { display: flex; gap: 8px; align-items: center; }
        .alert { padding: 8px 12px; border-radius: 6px; background: #ecfeff; color: #0e7490; margin-bottom: 12px; font-size: 13px; }
    </style>
</head>
<body data-return-url="{{ $returnUrl ?? '' }}">
    <div class="header">
        <h1>Trình quản lý tệp</h1>
        <div class="breadcrumb">
            <a href="{{ route('admin.file-manager.index') }}">Gốc</a>
            @if($currentPath)
                @foreach(explode('/', $currentPath) as $index => $segment)
                    @php
                        $path = implode('/', array_slice(explode('/', $currentPath), 0, $index + 1));
                    @endphp
                    / <a href="{{ route('admin.file-manager.index', ['path' => $path]) }}">{{ $segment }}</a>
                @endforeach
            @endif
        </div>
    </div>

    <div class="container">
        @if(session('success'))
            <div class="alert">{{ session('success') }}</div>
        @endif

        <div class="toolbar">
            <form class="upload-form" action="{{ route('admin.file-manager.upload') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="path" value="{{ $currentPath }}">
                <input type="file" name="upload" required>
                <button class="btn btn-primary" type="submit">Tải lên</button>
            </form>
            <span class="file-meta">Dung lượng tối đa 5MB, hỗ trợ ảnh và tài liệu cơ bản.</span>
        </div>

        <div class="grid">
            <div class="panel">
                <h2>Thư mục</h2>
                <ul class="folder-list">
                    @if($currentPath)
                        <li>
                            <a href="{{ route('admin.file-manager.index', ['path' => dirname($currentPath) === '.' ? '' : dirname($currentPath)]) }}">.. (Quay lại)</a>
                        </li>
                    @endif
                    @forelse($directories as $dir)
                        <li>
                            <a href="{{ route('admin.file-manager.index', ['path' => $currentPath ? $currentPath . '/' . $dir : $dir]) }}">{{ $dir }}</a>
                        </li>
                    @empty
                        <li class="file-meta">Chưa có thư mục con.</li>
                    @endforelse
                </ul>
            </div>

            <div class="panel">
                <h2>Tệp tin</h2>
                <div class="file-grid">
                    @forelse($files as $file)
                        <div class="file-card">
                            <div class="file-thumb">
                                @if($file['is_image'])
                                    <img src="{{ $file['url'] }}" alt="{{ $file['name'] }}">
                                @else
                                    <span class="file-meta">{{ strtoupper($file['extension'] ?: 'FILE') }}</span>
                                @endif
                            </div>
                            <div class="file-name">{{ $file['name'] }}</div>
                            <div class="file-meta">{{ number_format($file['size'] / 1024, 1) }} KB</div>
                            <button class="btn btn-secondary" type="button" onclick="selectFile('{{ $file['url'] }}')">Chọn</button>
                        </div>
                    @empty
                        <div class="file-meta">Chưa có tệp nào trong thư mục này.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <script>
        function selectFile(url) {
            const params = new URLSearchParams(window.location.search);
            const funcNum = params.get('CKEditorFuncNum');
            const targetWindow = window.opener || window.parent;
            const returnUrl = document.body.getAttribute('data-return-url') || '';

            if (funcNum && targetWindow && targetWindow.CKEDITOR) {
                targetWindow.CKEDITOR.tools.callFunction(funcNum, url);
                window.close();
                return;
            }

            if (returnUrl) {
                try {
                    const target = new URL(returnUrl, window.location.origin);
                    target.searchParams.set('ckfile', url);
                    window.location.href = target.toString();
                    return;
                } catch (e) {}
            }

            if (targetWindow && targetWindow.CKEDITOR) {
                const editor = targetWindow.CKEDITOR.currentInstance
                    || Object.values(targetWindow.CKEDITOR.instances || {})[0];
                if (editor && editor.getDialog) {
                    const dialog = editor.getDialog();
                    if (dialog && dialog.getContentElement) {
                        const urlField = dialog.getContentElement('info', 'txtUrl');
                        if (urlField && urlField.setValue) {
                            urlField.setValue(url);
                            window.close();
                            return;
                        }
                    }
                }
            }

            try {
                localStorage.setItem('ckeditor:file', JSON.stringify({
                    url: url,
                    ts: Date.now(),
                    openDialog: true
                }));
            } catch (e) {}

            if (window.opener) {
                window.close();
                return;
            }

            if (document.referrer) {
                window.location.href = document.referrer;
            }
        }
    </script>
</body>
</html>
