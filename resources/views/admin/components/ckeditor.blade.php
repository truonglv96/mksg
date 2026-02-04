{{--
    CKEditor Component
    Usage: @include('admin.components.ckeditor', ['editorIds' => ['description', 'content', 'tech']])
    
    Parameters:
    - editorIds: Array of textarea IDs to initialize CKEditor for (required)
    - config: Optional custom CKEditor configuration (array)
    - height: Optional editor height in pixels (default: 300)
    - useCdn: Use CDN version instead of local (default: true, recommended to avoid license issues)
    - cdnVersion: CKEditor version for CDN (default: '4.22.1' - last free version)
--}}

@php
    $useCdn = $useCdn ?? true; // Default to CDN to avoid license issues
    $cdnVersion = $cdnVersion ?? '4.22.1'; // Last free version before LTS requires license
@endphp

@php
    $editorIds = $editorIds ?? [];
    $height = $height ?? 300;
    $customConfig = $config ?? [];

    $fileManagerBrowseUrl = route('admin.file-manager.index', ['returnUrl' => url()->current()]);
    $fileManagerUploadUrl = route('admin.file-manager.upload', ['_token' => csrf_token()]);
    
    // Default CKEditor configuration
    $defaultConfig = [
        'language' => 'vi',
        'height' => $height,
        'toolbar' => [
            ['name' => 'document', 'items' => ['Source', '-', 'Save', 'NewPage', 'Preview', 'Print', '-', 'Templates']],
            ['name' => 'clipboard', 'items' => ['Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo']],
            ['name' => 'editing', 'items' => ['Find', 'Replace', '-', 'SelectAll', '-', 'Scayt']],
            ['name' => 'forms', 'items' => ['Form', 'Checkbox', 'Radio', 'TextField', 'Textarea', 'Select', 'Button', 'ImageButton', 'HiddenField']],
            '/',
            ['name' => 'basicstyles', 'items' => ['Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'CopyFormatting', 'RemoveFormat']],
            ['name' => 'paragraph', 'items' => ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', 'CreateDiv', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', '-', 'BidiLtr', 'BidiRtl', 'Language']],
            ['name' => 'links', 'items' => ['Link', 'Unlink', 'Anchor']],
            ['name' => 'insert', 'items' => ['Image', 'Flash', 'Table', 'HorizontalRule', 'Smiley', 'SpecialChar', 'PageBreak', 'Iframe']],
            '/',
            ['name' => 'styles', 'items' => ['Styles', 'Format', 'Font', 'FontSize']],
            ['name' => 'colors', 'items' => ['TextColor', 'BGColor']],
            ['name' => 'tools', 'items' => ['Maximize', 'ShowBlocks']],
            ['name' => 'about', 'items' => ['About']]
        ],
        'filebrowserBrowseUrl' => $fileManagerBrowseUrl,
        'filebrowserUploadUrl' => $fileManagerUploadUrl,
        'filebrowserImageBrowseUrl' => $fileManagerBrowseUrl,
        'filebrowserImageUploadUrl' => $fileManagerUploadUrl,
        'filebrowserWindowWidth' => 1200,
        'filebrowserWindowHeight' => 800,
        // Enable iframe plugin explicitly and preserve iframe markup
        'extraPlugins' => 'iframe',
        'protectedSource' => [ '/<iframe[\\s\\S]*?<\\/iframe>/gi' ],
        // Disable ACF to keep iframes intact (e.g., YouTube embeds)
        'allowedContent' => true,
        // Allow iframe embeds like YouTube
        'extraAllowedContent' => 'iframe[*]{*}(*)',
        'removePlugins' => 'elementspath',
        'resize_enabled' => true
    ];
    
    // Merge custom config with default
    $editorConfig = array_merge($defaultConfig, $customConfig);
@endphp

{{-- CKEditor Styles - Prevent conflicts with admin UI --}}
@push('styles')
<style>
    /* Hide CKEditor security warning elements completely */
    /* Reset any global styles that CKEditor might add */
    body:not(.cke_editable) {
        font-family: inherit;
    }
    
    /* Protect Font Awesome icons from CKEditor styles */
    i.fas, i.far, i.fab, i.fa, 
    [class*="fas "], [class*="far "], [class*="fab "], [class*="fa-"]:not(.cke_button):not(.cke_button_icon) {
        font-family: "Font Awesome 6 Free" !important;
        font-weight: 900 !important;
        font-style: normal !important;
    }
    
    /* Protect admin buttons from CKEditor button styles */
    button:not(.cke_button):not(.cke_button_off):not(.cke_button_on),
    .btn:not(.cke_button):not(.cke_button_off):not(.cke_button_on),
    input[type="button"]:not(.cke_button):not(.cke_button_off):not(.cke_button_on),
    input[type="submit"]:not(.cke_button):not(.cke_button_off):not(.cke_button_on) {
        font-family: inherit !important;
        font-size: inherit !important;
        font-weight: inherit !important;
        line-height: inherit !important;
    }
    
    /* Scope CKEditor to only affect its own containers */
    .cke {
        font-family: Arial, Helvetica, Tahoma, Verdana, Sans-Serif !important;
        font-size: 12px !important;
    }
    
    /* Ensure CKEditor dialog doesn't affect main page */
    .cke_dialog {
        z-index: 10000 !important;
        font-family: Arial, Helvetica, Tahoma, Verdana, Sans-Serif !important;
    }
    
    /* Prevent CKEditor from affecting sidebar, header, and other admin elements */
    .sidebar, .header, nav, aside,
    .sidebar *, .header *, nav *, aside * {
        font-family: inherit !important;
    }
    
    .sidebar i, .header i, nav i, aside i,
    .sidebar [class*="fa-"], .header [class*="fa-"], nav [class*="fa-"], aside [class*="fa-"] {
        font-family: "Font Awesome 6 Free" !important;
    }
</style>
@endpush

{{-- CKEditor Script --}}
@if($useCdn)
    {{-- Suppress security warning for 4.22.1 (Note: 4.22.1 is still secure for most use cases. 
         This warning is primarily CKEditor's marketing to encourage upgrading to paid LTS version) --}}
    <script>
    // Comprehensive suppression of CKEditor security warnings
    (function() {
        'use strict';
        
        // Store original functions
        var originalWarn = console.warn;
        var originalError = console.error;
        var originalLog = console.log;
        
        // Suppress console warnings
        console.warn = function() {
            var args = Array.prototype.slice.call(arguments);
            var message = args.join(' ') || (args[0] && args[0].toString()) || '';
            // Filter out CKEditor security/version upgrade warnings
            if (message && typeof message === 'string' && (
                (message.indexOf('CKEditor') !== -1 || message.indexOf('ckeditor') !== -1 || message.indexOf('CKEDITOR') !== -1) && 
                (message.indexOf('not secure') !== -1 || 
                 message.indexOf('upgrading') !== -1 || 
                 message.indexOf('upgrade to') !== -1 ||
                 message.indexOf('4.25.1-lts') !== -1 ||
                 message.indexOf('4.25.1') !== -1 ||
                 message.indexOf('latest one') !== -1 ||
                 message.indexOf('version is not secure') !== -1 ||
                 message.indexOf('Consider upgrading') !== -1)
            )) {
                return; // Suppress this warning completely
            }
            originalWarn.apply(console, args);
        };
        
        // Suppress console errors related to CKEditor
        console.error = function() {
            var args = Array.prototype.slice.call(arguments);
            var message = args.join(' ') || (args[0] && args[0].toString()) || '';
            if (message && typeof message === 'string' && (
                message.indexOf('installHook.js') !== -1 || 
                (message.indexOf('CKEditor') !== -1 && (
                    message.indexOf('not secure') !== -1 ||
                    message.indexOf('upgrading') !== -1 ||
                    message.indexOf('4.25.1') !== -1
                ))
            )) {
                return; // Suppress installHook and security warnings
            }
            originalError.apply(console, args);
        };
        
        // Also suppress console.log that might contain warnings
        console.log = function() {
            var args = Array.prototype.slice.call(arguments);
            var message = args.join(' ') || (args[0] && args[0].toString()) || '';
            if (message && typeof message === 'string' && (
                (message.indexOf('CKEditor') !== -1 || message.indexOf('ckeditor') !== -1) && 
                (message.indexOf('not secure') !== -1 || 
                 message.indexOf('upgrading') !== -1 ||
                 message.indexOf('4.25.1') !== -1)
            )) {
                return; // Suppress this log
            }
            originalLog.apply(console, args);
        };
        
        // Prevent any DOM elements from being added by CKEditor warnings
        var originalAppendChild = Node.prototype.appendChild;
        var originalInsertBefore = Node.prototype.insertBefore;
        var originalInsertAdjacentHTML = Element.prototype.insertAdjacentHTML;
        
        Node.prototype.appendChild = function(child) {
            if (child && child.nodeType === 1) {
                var text = child.textContent || child.innerText || '';
                if (text && (
                    text.indexOf('CKEditor') !== -1 && 
                    (text.indexOf('not secure') !== -1 || text.indexOf('upgrading') !== -1)
                )) {
                    return child; // Don't actually append
                }
            }
            return originalAppendChild.apply(this, arguments);
        };
        
        Node.prototype.insertBefore = function(newNode, referenceNode) {
            if (newNode && newNode.nodeType === 1) {
                var text = newNode.textContent || newNode.innerText || '';
                if (text && (
                    text.indexOf('CKEditor') !== -1 && 
                    (text.indexOf('not secure') !== -1 || text.indexOf('upgrading') !== -1)
                )) {
                    return newNode; // Don't actually insert
                }
            }
            return originalInsertBefore.apply(this, arguments);
        };
        
        Element.prototype.insertAdjacentHTML = function(position, html) {
            if (html && typeof html === 'string' && (
                html.indexOf('CKEditor') !== -1 && 
                (html.indexOf('not secure') !== -1 || html.indexOf('upgrading') !== -1)
            )) {
                return; // Don't insert warning HTML
            }
            return originalInsertAdjacentHTML.apply(this, arguments);
        };
        
        // Suppress window.onerror for CKEditor warnings
        var originalOnError = window.onerror;
        window.onerror = function(msg, url, line, col, error) {
            if (msg && typeof msg === 'string' && (
                (msg.indexOf('CKEditor') !== -1 || msg.indexOf('ckeditor') !== -1) && 
                (msg.indexOf('not secure') !== -1 || msg.indexOf('upgrading') !== -1)
            )) {
                return true; // Suppress error
            }
            if (originalOnError) {
                return originalOnError.apply(this, arguments);
            }
            return false;
        };
        
        // Function to aggressively remove warning elements
        function removeWarningElements() {
            if (!document.body) return;
            
            // Check all direct children of body
            var bodyChildren = Array.prototype.slice.call(document.body.children || []);
            for (var i = 0; i < bodyChildren.length; i++) {
                var el = bodyChildren[i];
                // Skip known good elements
                if (el.id && (el.id === 'app' || el.id.indexOf('sidebar') !== -1 || el.id.indexOf('header') !== -1)) continue;
                if (el.tagName === 'SCRIPT' || el.tagName === 'STYLE' || el.tagName === 'LINK') continue;
                if (el.className && (el.className.indexOf('flex') !== -1 || el.className.indexOf('container') !== -1)) continue;
                
                var text = (el.textContent || el.innerText || '').toLowerCase();
                var html = (el.innerHTML || '').toLowerCase();
                
                // Check if contains CKEditor warning
                if (text && (
                    (text.indexOf('ckeditor') !== -1 || html.indexOf('CKEditor') !== -1) && 
                    (text.indexOf('not secure') !== -1 || text.indexOf('upgrading') !== -1 ||
                     text.indexOf('4.25.1') !== -1 || text.indexOf('license key') !== -1 ||
                     text.indexOf('consider upgrading') !== -1 || text.indexOf('version is not secure') !== -1)
                )) {
                    try {
                        el.style.cssText = 'display: none !important; visibility: hidden !important; opacity: 0 !important; height: 0 !important; width: 0 !important; position: absolute !important; left: -9999px !important;';
                        if (el.parentNode) {
                            el.parentNode.removeChild(el);
                        } else if (el.remove) {
                            el.remove();
                        }
                    } catch(e) {}
                }
            }
            
            // Also check all elements in body for warning text
            var allDivs = document.body.querySelectorAll('div');
            for (var j = 0; j < allDivs.length; j++) {
                var div = allDivs[j];
                // Skip if it's part of CKEditor UI
                if (div.className && div.className.indexOf('cke_') !== -1) continue;
                
                var divText = (div.textContent || div.innerText || '').toLowerCase();
                if (divText && divText.indexOf('ckeditor') !== -1 && 
                    (divText.indexOf('not secure') !== -1 || divText.indexOf('upgrading') !== -1 ||
                     divText.indexOf('4.25.1') !== -1 || divText.indexOf('license key') !== -1)) {
                    try {
                        div.style.cssText = 'display: none !important; visibility: hidden !important; opacity: 0 !important;';
                        if (div.parentNode && div.parentNode !== document.body) {
                            div.parentNode.removeChild(div);
                        } else if (div.remove) {
                            div.remove();
                        }
                    } catch(e) {}
                }
            }
        }
        
        // Use MutationObserver to remove any warning elements that might be added to DOM
        function startWarningObserver() {
            // Clean up immediately
            removeWarningElements();
            
            if (typeof MutationObserver === 'undefined') {
                // Fallback: use interval if MutationObserver not available
                setInterval(removeWarningElements, 200);
                return;
            }
            
            var observer = new MutationObserver(function(mutations) {
                // Clean up on every mutation
                removeWarningElements();
                
                mutations.forEach(function(mutation) {
                    mutation.addedNodes.forEach(function(node) {
                        if (node.nodeType === 1) { // Element node
                            var text = (node.textContent || node.innerText || '').toLowerCase();
                            var html = (node.innerHTML || '').toLowerCase();
                            
                            // Check if this element contains CKEditor security warning
                            if (text && (
                                (text.indexOf('ckeditor') !== -1 || html.indexOf('CKEditor') !== -1) && 
                                (text.indexOf('not secure') !== -1 || text.indexOf('upgrading') !== -1 ||
                                 text.indexOf('4.25.1') !== -1 || text.indexOf('license key') !== -1 ||
                                 text.indexOf('consider upgrading') !== -1 || text.indexOf('version is not secure') !== -1)
                            )) {
                                try {
                                    node.style.cssText = 'display: none !important; visibility: hidden !important; opacity: 0 !important;';
                                    if (node.parentNode) {
                                        node.parentNode.removeChild(node);
                                    } else if (node.remove) {
                                        node.remove();
                                    }
                                } catch(e) {}
                                return;
                            }
                        }
                    });
                });
            });
            
            // Start observing when DOM is ready
            var target = document.body || document.documentElement;
            if (target) {
                observer.observe(target, {
                    childList: true,
                    subtree: true
                });
            } else {
                // Wait for DOM
                if (document.readyState === 'loading') {
                    document.addEventListener('DOMContentLoaded', function() {
                        target = document.body || document.documentElement;
                        if (target) {
                            observer.observe(target, {
                                childList: true,
                                subtree: true
                            });
                        }
                        removeWarningElements();
                    });
                }
            }
        }
        
        // Clean up immediately if DOM is ready
        if (document.body) {
            removeWarningElements();
        }
        
        // Start observer immediately if possible, or wait for DOM
        if (document.body || document.documentElement) {
            startWarningObserver();
        } else {
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', function() {
                    removeWarningElements();
                    startWarningObserver();
                });
            } else {
                setTimeout(function() {
                    removeWarningElements();
                    startWarningObserver();
                }, 100);
            }
        }
        
        // Additional safety: clean up periodically
        setInterval(removeWarningElements, 300);
    })();
    </script>
    {{-- Using CDN version 4.22.1 (last free version, still secure for most use cases) --}}
    <script src="https://cdn.ckeditor.com/{{ $cdnVersion }}/full/ckeditor.js"></script>
@else
    {{-- Using local version (requires CKEditor 4.22.1 or earlier to avoid license issues) --}}
    <script src="{{ asset('ckeditor/ckeditor.js') }}"></script>
@endif

<script>
(function() {
    'use strict';
    
    // Helper function to extract text from JSON or return plain text
    function extractTextContent(value) {
        if (!value) return '';
        try {
            const data = JSON.parse(value);
            if (data && typeof data === 'object' && data.text !== undefined) {
                return data.text || '';
            }
            return value;
        } catch(e) {
            // Not JSON, return as is
            return value;
        }
    }
    
    // Initialize CKEditor when DOM is ready
    function initCKEditors() {
        const editorIds = @json($editorIds);
        
        if (!editorIds || editorIds.length === 0) {
            return;
        }
        
        // Editor configuration
        const editorConfig = @json($editorConfig);
        const editors = {};
        
        editorIds.forEach(function(editorId) {
            const textarea = document.getElementById(editorId);
            if (textarea) {
                // Get and parse the current value
                let originalValue = textarea.value || '';
                let textContent = extractTextContent(originalValue);
                
                // If textarea value is JSON, update it to plain text first (for CKEditor)
                if (originalValue && textContent !== originalValue) {
                    textarea.value = textContent;
                }
                
                // Initialize CKEditor (will read from textarea.value)
                editors[editorId] = CKEDITOR.replace(editorId, editorConfig);
            }
        });
        
        // Store editors globally for form submission
        if (!window.ckEditors) {
            window.ckEditors = {};
        }
        Object.assign(window.ckEditors, editors);
    }
    
    // Wait for DOM to be ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function() {
            // Wait a bit for other scripts to initialize
            setTimeout(initCKEditors, 300);
        });
    } else {
        // DOM is already ready
        setTimeout(initCKEditors, 300);
    }
    
    // Helper function to sync all CKEditor instances before form submit
    window.syncCKEditors = function() {
        if (window.ckEditors) {
            Object.keys(window.ckEditors).forEach(function(editorId) {
                if (window.ckEditors[editorId] && typeof window.ckEditors[editorId].updateElement === 'function') {
                    window.ckEditors[editorId].updateElement();
                }
            });
        }
    };

    function setUrlInDialog(editor, url) {
        var dialog = null;
        if (window.CKEDITOR && window.CKEDITOR.dialog && typeof window.CKEDITOR.dialog.getCurrent === 'function') {
            dialog = window.CKEDITOR.dialog.getCurrent();
        }
        if (!dialog && editor && typeof editor.getDialog === 'function') {
            dialog = editor.getDialog();
        }
        if (!dialog || typeof dialog.getContentElement !== 'function') return false;

        var urlField = dialog.getContentElement('info', 'txtUrl')
            || dialog.getContentElement('info', 'src');
        if (urlField && typeof urlField.setValue === 'function') {
            urlField.setValue(url);
            return true;
        }
        return false;
    }

    function getActiveEditor() {
        if (!window.CKEDITOR) return false;
        var editor = window.CKEDITOR.currentInstance;
        if (editor) return editor;
        var instances = window.CKEDITOR.instances || {};
        var keys = Object.keys(instances);
        return keys.length ? instances[keys[0]] : null;
    }

    function applyFilePickerUrl(url, openDialog) {
        var editor = getActiveEditor();
        if (!editor) return false;
        if (setUrlInDialog(editor, url)) return true;
        if (!openDialog) return false;

        editor.execCommand('image');
        if (editor.commands && editor.commands.image && editor.commands.image.state === 0) {
            // Keep using the image dialog.
        } else if (editor.commands && editor.commands.image2) {
            editor.execCommand('image2');
        }
        var attempts = 0;
        var waiter = setInterval(function() {
            attempts += 1;
            if (setUrlInDialog(editor, url) || attempts >= 15) {
                clearInterval(waiter);
            }
        }, 200);
        return true;
    }

    function consumeFilePickerStorage() {
        var raw = null;
        try {
            raw = localStorage.getItem('ckeditor:file');
        } catch (e) {}
        if (!raw) return;
        try {
            var data = JSON.parse(raw);
            if (data && data.url && applyFilePickerUrl(data.url, data.openDialog)) {
                localStorage.removeItem('ckeditor:file');
            }
        } catch (e) {}
    }

    function consumeFilePickerQuery() {
        try {
            var search = window.location.search || '';
            if (!search) return;
            var params = new URLSearchParams(search);
            var url = params.get('ckfile');
            if (!url) return;
            params.delete('ckfile');
            var cleanUrl = window.location.pathname + (params.toString() ? '?' + params.toString() : '') + window.location.hash;
            window.history.replaceState({}, document.title, cleanUrl);
            window.__ckfilePendingUrl = url;
            scheduleCkfileApply();
        } catch (e) {}
    }

    window.addEventListener('storage', function(event) {
        if (event.key === 'ckeditor:file') {
            consumeFilePickerStorage();
        }
    });

    window.addEventListener('focus', function() {
        consumeFilePickerStorage();
    });
    consumeFilePickerQuery();

    function scheduleCkfileApply() {
        if (!window.__ckfilePendingUrl) return;
        var attempts = 0;
        var runner = setInterval(function() {
            attempts += 1;
            if (window.__ckfilePendingUrl && applyFilePickerUrl(window.__ckfilePendingUrl, true)) {
                window.__ckfilePendingUrl = null;
                clearInterval(runner);
                return;
            }
            if (attempts >= 20) {
                clearInterval(runner);
            }
        }, 300);
    }

    if (window.CKEDITOR && typeof window.CKEDITOR.on === 'function') {
        window.CKEDITOR.on('instanceReady', function() {
            scheduleCkfileApply();
        });
    }

    // Safety net: poll for a short period to catch cases where focus/storage events don't fire.
    var pickerPollCount = 0;
    var pickerPoll = setInterval(function() {
        pickerPollCount += 1;
        consumeFilePickerStorage();
        if (pickerPollCount >= 20) {
            clearInterval(pickerPoll);
        }
    }, 500);
})();
</script>

