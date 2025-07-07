<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Administration') - Sekaijin</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="/images/favicon.ico">
    
    <!-- Font Awesome CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- TinyMCE -->
    <script src="https://cdn.tiny.cloud/1/{{ config('services.tinymce.api_key') }}/tinymce/6/tinymce.min.js" referrerpolicy="origin" nonce="{{ $csp_nonce ?? '' }}"></script>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center space-x-4">
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center">
                        <span class="font-bold text-blue-600 text-xl">🛡️ Admin</span>
                    </a>
                    <div class="hidden md:flex space-x-4">
                        <a href="{{ route('admin.dashboard') }}" 
                           class="px-3 py-2 text-gray-600 hover:text-blue-600 transition duration-200 {{ request()->routeIs('admin.dashboard') ? 'text-blue-600 font-medium' : '' }}">
                            Dashboard
                        </a>
                        <a href="{{ route('admin.articles') }}" 
                           class="px-3 py-2 text-gray-600 hover:text-blue-600 transition duration-200 {{ request()->routeIs('admin.articles*') ? 'text-blue-600 font-medium' : '' }}">
                            Articles
                        </a>
                        <a href="{{ route('admin.news') }}" 
                           class="px-3 py-2 text-gray-600 hover:text-blue-600 transition duration-200 {{ request()->routeIs('admin.news*') ? 'text-blue-600 font-medium' : '' }}">
                            Actualités
                        </a>
                    </div>
                </div>
                
                <div class="flex items-center space-x-4">
                    <a href="{{ route('home') }}" 
                       class="text-gray-600 hover:text-blue-600 text-sm transition duration-200">
                        <i class="fas fa-arrow-left mr-1"></i>
                        Retour au site
                    </a>
                    <div class="flex items-center space-x-2">
                        <span class="text-sm text-gray-600">{{ Auth::user()->name }}</span>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" 
                                    class="bg-red-500 text-white px-3 py-1 rounded text-sm hover:bg-red-600 transition duration-200">
                                Déconnexion
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Content -->
    <main class="max-w-7xl mx-auto py-8 px-4">
        <!-- Messages de notification -->
        @if (session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6">
                <div class="flex items-center">
                    <i class="fas fa-check-circle mr-2"></i>
                    {{ session('success') }}
                </div>
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    {{ session('error') }}
                </div>
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    <span class="font-medium">Erreurs de validation :</span>
                </div>
                <ul class="mt-2 ml-6 list-disc">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </main>

    <script nonce="{{ $csp_nonce ?? '' }}">
        // Initialize TinyMCE
        document.addEventListener('DOMContentLoaded', function() {
            tinymce.init({
                selector: '.wysiwyg',
                plugins: [
                    'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
                    'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
                    'insertdatetime', 'media', 'table', 'help', 'wordcount', 'emoticons',
                    'autoresize', 'codesample', 'quickbars'
                ],
                toolbar: 'undo redo | formatselect | bold italic underline strikethrough | ' +
                         'alignleft aligncenter alignright alignjustify | ' +
                         'bullist numlist outdent indent | ' +
                         'removeformat | link image media table | ' +
                         'codesample emoticons | fullscreen preview help',
                menubar: 'file edit view insert format tools table help',
                height: 500,
                max_height: 800,
                language: 'fr_FR',
                branding: false,
                resize: true,
                autoresize_bottom_margin: 16,
                content_style: 'body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; font-size: 16px; line-height: 1.6; margin: 1rem; }',
                
                // Enhanced image handling
                images_upload_url: false,
                automatic_uploads: false,
                file_picker_types: 'image',
                
                // Link options
                link_assume_external_targets: true,
                link_context_toolbar: true,
                
                // Table options
                table_use_colgroups: true,
                table_responsive_width: true,
                
                // Advanced formatting
                formats: {
                    alignleft: { selector: 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li,table,img', classes: 'text-left' },
                    aligncenter: { selector: 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li,table,img', classes: 'text-center' },
                    alignright: { selector: 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li,table,img', classes: 'text-right' },
                    alignjustify: { selector: 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li,table,img', classes: 'text-justify' }
                },
                
                // Content filtering - Secure configuration
                valid_elements: 'p,br,strong/b,em/i,u,s,ul,ol,li,a[href|target|title],img[src|alt|width|height|class],h1,h2,h3,h4,h5,h6,blockquote,code,pre[class],table,thead,tbody,tr,td[colspan|rowspan],th[colspan|rowspan],div[class],span[class]',
                invalid_elements: 'script,object,embed,iframe,form,input,button,meta,link,style,base,applet,audio,video,canvas,svg',
                forced_root_block: 'p',
                convert_urls: false,
                
                setup: function (editor) {
                    editor.on('change', function () {
                        editor.save();
                    });
                    
                    // Auto-save when editor loses focus
                    editor.on('blur', function () {
                        editor.save();
                    });
                }
            });
        });

        // Confirm delete actions
        function confirmDelete(message = 'Êtes-vous sûr de vouloir supprimer cet élément ?') {
            return confirm(message);
        }

        // Bulk actions handler
        function handleBulkAction(formId) {
            const form = document.getElementById(formId);
            const checkboxes = form.querySelectorAll('input[type="checkbox"]:checked');
            const actionSelect = form.querySelector('select[name="action"]');
            
            if (checkboxes.length === 0) {
                alert('Veuillez sélectionner au moins un élément.');
                return false;
            }
            
            if (!actionSelect.value) {
                alert('Veuillez sélectionner une action.');
                return false;
            }
            
            const action = actionSelect.value;
            const count = checkboxes.length;
            
            let message = '';
            switch(action) {
                case 'delete':
                    message = `Êtes-vous sûr de vouloir supprimer ${count} élément(s) ?`;
                    break;
                case 'publish':
                    message = `Êtes-vous sûr de vouloir publier ${count} élément(s) ?`;
                    break;
                case 'unpublish':
                    message = `Êtes-vous sûr de vouloir dépublier ${count} élément(s) ?`;
                    break;
            }
            
            return confirm(message);
        }

        // Select all checkbox functionality
        function toggleSelectAll(checkbox, targetClass) {
            const checkboxes = document.querySelectorAll(`.${targetClass}`);
            checkboxes.forEach(cb => cb.checked = checkbox.checked);
        }
    </script>
</body>
</html>