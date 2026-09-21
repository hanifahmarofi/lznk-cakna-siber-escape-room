<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>S.H.I.E.L.D // Intel Gallery Manager</title>
    @vite('resources/css/app.css')
    <link href="https://fonts.googleapis.com/css2?family=Anton&family=Poppins:wght@700;900&family=Share+Tech+Mono&display=swap" rel="stylesheet">
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.8.2/tinymce.min.js" referrerpolicy="origin"></script>

    <style>
        :root {
            --bg-color: #050510;
            --text-color: #d1d5db;
            --card-bg: rgba(15, 10, 20, 0.85);
            --input-bg: #100020;
            --input-border: #7e22ce;
            --input-text: #e9d5ff;
            --vid-opacity: 0.4;
            --vid-overlay: rgba(0,0,0,0.7);
        }

        .light-mode {
            --bg-color: #f3f4f6;
            --text-color: #0f172a;
            --card-bg: rgba(255, 255, 255, 0.9);
            --input-bg: #f5f3ff;
            --input-border: #9333ea;
            --input-text: #3b0764;
            --vid-opacity: 0.1;
            --vid-overlay: rgba(255,255,255,0.8);
        }

        body { font-family: 'Share Tech Mono', monospace; background-color: var(--bg-color); color: var(--text-color); overflow-x: hidden; transition: background-color 0.3s ease; }
        .title-font { font-family: 'Poppins', sans-serif; text-shadow: 0 0 10px rgba(168,85,247,0.7); }
        .theme-card { background: var(--card-bg) !important; backdrop-filter: blur(8px); transition: background 0.3s ease; }
        .lz-input { background-color: var(--input-bg); border: 1px solid var(--input-border); color: var(--input-text); width: 100%; padding: 12px; border-radius: 6px; font-family: 'Share Tech Mono', monospace; letter-spacing: 0.1em; transition: all 0.3s ease; }
        .lz-input:focus { outline: none; border-color: #a855f7; box-shadow: 0 0 15px rgba(168,85,247,0.4); }
        
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: rgba(0,0,0,0.1); border-radius: 4px; }
        ::-webkit-scrollbar-thumb { background: rgba(168,85,247,0.5); border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: rgba(168,85,247,0.8); }

        .theme-video { opacity: var(--vid-opacity); transition: opacity 0.5s ease; }
        .theme-overlay { background: var(--vid-overlay); transition: background 0.5s ease; }

        /* Light Mode Text Overrides */
        .light-mode .text-purple-400 { color: #6b21a8 !important; }
        .light-mode .text-white { color: #000000 !important; }
        .light-mode .text-gray-400 { color: #475569 !important; }
        .light-mode .text-purple-500 { color: #7e22ce !important; }
        .light-mode .bg-black { background-color: #cbd5e1 !important; }

        /* Force Black Text on Toggles in Light Mode */
        .light-mode #lang-toggle, .light-mode #theme-toggle {
            color: #000000 !important;
            border-color: #7e22ce !important;
            background-color: rgba(255, 255, 255, 0.9) !important;
        }

        /* 🔥 HIDE DEFAULT GOOGLE TRANSLATE UI ELEMENTS 🔥 */
        iframe.skiptranslate { display: none !important; } 
        .goog-te-banner-frame { display: none !important; }
        .goog-te-menu-value { display: none !important; }
        .VIpgJd-Zvi9od-ORHb-OEVmcd { display: none !important; } 
        body { top: 0px !important; margin-top: 0px !important; position: static !important; } 
        html { top: 0px !important; margin-top: 0px !important; position: static !important; }
        #google_translate_element { display: none !important; }
        .goog-tooltip { display: none !important; }
        .goog-tooltip:hover { display: none !important; }
        .goog-text-highlight { background-color: transparent !important; border: none !important; box-shadow: none !important; }

        /* 🔥 FIX: TINYMCE FULLSCREEN OVERLAP & Z-INDEX TRAPPING 🔥 */
        body.tox-fullscreen { overflow: hidden !important; }
        body.tox-fullscreen .theme-card { backdrop-filter: none !important; }
        body.tox-fullscreen .sticky { position: static !important; z-index: 999999 !important; }
        body.tox-fullscreen .z-10 { z-index: 999999 !important; }

        .tox.tox-tinymce-fullscreen {
            position: fixed !important; z-index: 999999 !important; 
            top: 0 !important; left: 0 !important; right: 0 !important; bottom: 0 !important;
            width: 100vw !important; height: 100vh !important;
        }
        
        .tox-tinymce-aux {
            z-index: 9999999 !important;
        }

        .tox-tinymce { border: 1px solid #7e22ce !important; border-radius: 6px !important; box-shadow: inset 0 0 10px rgba(0,0,0,0.5) !important; }
        .tox .tox-toolbar__primary { background: #150525 !important; }
        .tox .tox-tbtn { color: #e9d5ff !important; }
        .tox .tox-tbtn:hover { background: #7e22ce !important; color: white !important; }
    </style>
</head>
<body class="min-h-screen relative pb-10">

    <div id="google_translate_element"></div>

    <div class="fixed inset-0 z-0 overflow-hidden pointer-events-none">
        <video autoplay loop muted playsinline class="theme-video w-full h-full object-cover" style="filter: grayscale(100%) brightness(150%) contrast(150%);">
            <source src="{{ asset('video/videoplayback.webm') }}" type="video/webm">
        </video>
        <div class="absolute inset-0 bg-purple-900 mix-blend-color opacity-30 pointer-events-none"></div>
        <div class="theme-overlay absolute inset-0 pointer-events-none"></div>
    </div>

    <div class="fixed top-4 right-4 z-[9999] flex gap-3">
        <button id="lang-toggle" class="theme-card border border-purple-500/50 px-3 py-1.5 rounded-full text-xs font-bold tracking-wider hover:bg-purple-500 hover:text-white transition-colors text-purple-400 shadow-lg">
            🇬🇧 ENGLISH
        </button>
        <button id="theme-toggle" class="theme-card border border-purple-500/50 px-3 py-1.5 rounded-full text-xs font-bold tracking-wider hover:bg-purple-500 hover:text-white transition-colors text-purple-400 shadow-lg">
            ☀️ LIGHT MODE
        </button>
    </div>

    <div class="max-w-7xl mx-auto relative z-10 p-4 md:p-8 mt-12 md:mt-4">
        
        <header class="flex flex-col md:flex-row justify-between items-start md:items-end mb-8 pb-4 border-b border-purple-500/30">
            <div class="flex items-center gap-5">
                <div class="w-12 h-12 bg-purple-900/50 border-2 border-purple-500 rounded flex items-center justify-center text-purple-400 shadow-[0_0_15px_rgba(168,85,247,0.5)]">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </div>
                <div>
                    <h1 class="title-font font-bold text-3xl tracking-wider uppercase mb-1 text-purple-400">INTEL GALLERY MANAGER</h1>
                    <p class="text-purple-500 font-bold tracking-widest text-xs uppercase">S.H.I.E.L.D KNOWLEDGE BASE</p>
                </div>
            </div>
            <a href="{{ route('admin.dashboard') }}" class="mt-4 md:mt-0 px-5 py-2 rounded theme-card border border-purple-500/50 text-purple-400 hover:bg-purple-500 hover:text-white transition-colors text-xs font-bold tracking-widest uppercase flex items-center gap-2 shadow-[0_0_10px_rgba(168,85,247,0.2)]">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Back to Dashboard
            </a>
        </header>

        @if(session('success'))
            <div class="mb-6 bg-emerald-900/30 border border-emerald-500/50 text-emerald-400 px-4 py-3 rounded flex items-center gap-3 font-bold text-sm uppercase tracking-widest shadow-[0_0_15px_rgba(16,185,129,0.2)]">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="mb-6 bg-red-900/30 border border-red-500/50 text-red-400 px-4 py-3 rounded font-bold text-sm uppercase tracking-widest shadow-[0_0_15px_rgba(239,68,68,0.2)]">
                <ul class="list-disc list-inside ml-4">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <div class="lg:col-span-1">
                <div class="theme-card rounded-lg p-6 border border-purple-500/30 shadow-[0_0_20px_rgba(168,85,247,0.15)] sticky top-6">
                    <h2 class="text-xl text-purple-400 font-bold uppercase tracking-widest mb-6 border-b border-purple-500/20 pb-3">Upload New Poster</h2>
                    
                    <form action="{{ route('admin.gallery.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                        @csrf
                        
                        <div>
                            <label class="text-[10px] text-purple-400 font-bold uppercase tracking-widest mb-2 block">Poster Title</label>
                            <input type="text" name="title" required placeholder="e.g. Beware of Phishing" class="lz-input notranslate">
                        </div>

                        <div>
                            <label class="text-[10px] text-purple-400 font-bold uppercase tracking-widest mb-2 block">Image File (JPG, PNG - Max 20MB)</label>
                            <input type="file" name="image" required accept="image/*" class="w-full text-sm text-purple-400 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-xs file:font-bold file:bg-purple-900/50 file:text-purple-300 hover:file:bg-purple-800 transition cursor-pointer border border-purple-500/30 rounded p-2 bg-[#100020]">
                        </div>

                        <div class="flex flex-col">
                            <label class="text-[10px] text-purple-400 font-bold uppercase tracking-widest mb-2 block">Intel Description</label>
                            <textarea id="intel-editor" name="description" class="notranslate" placeholder="Enter the detailed information here..."></textarea>
                        </div>

                        <button type="submit" class="w-full bg-purple-600 hover:bg-purple-500 text-white font-bold py-3 px-4 rounded text-sm uppercase tracking-widest flex justify-center items-center gap-2 transition-all shadow-[0_0_15px_rgba(168,85,247,0.4)] hover:shadow-[0_0_25px_rgba(168,85,247,0.6)]">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                            Upload to Server
                        </button>
                    </form>
                </div>
            </div>

            <div class="lg:col-span-2">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @forelse($galleries as $gallery)
                        <div class="theme-card border border-purple-500/30 rounded-lg overflow-hidden flex flex-col group hover:border-purple-400 transition-colors shadow-[0_0_15px_rgba(0,0,0,0.5)] h-[400px]">
                            <div class="h-48 w-full overflow-hidden bg-black relative border-b border-purple-500/30 shrink-0">
                                <img src="{{ asset('storage/' . $gallery->image_path) }}" alt="{{ $gallery->title }}" class="w-full h-full object-cover opacity-80 group-hover:opacity-100 group-hover:scale-105 transition-all duration-500">
                            </div>
                            
                            <div class="p-5 flex-grow flex flex-col overflow-hidden">
                                <h3 class="text-lg font-bold text-white uppercase tracking-wider mb-2 truncate">{{ $gallery->title }}</h3>
                                
                                <p class="text-sm text-gray-400 mb-4 overflow-hidden" style="display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical;">
                                    {{ \Illuminate\Support\Str::limit(html_entity_decode(strip_tags($gallery->description), ENT_QUOTES, 'UTF-8'), 120, '...') }}
                                </p>
                                
                                <div class="flex justify-between items-center mt-auto pt-4 border-t border-purple-500/20 shrink-0">
                                    <span class="text-[10px] text-purple-500 font-bold uppercase tracking-widest">{{ $gallery->created_at->format('d M Y') }}</span>
                                    
                                    <div class="flex items-center gap-2">
                                        <a href="{{ url('/admin/gallery/' . $gallery->id . '/edit') }}" class="p-2 bg-blue-900/30 hover:bg-blue-500 text-blue-400 hover:text-white rounded border border-blue-500/50 transition-colors" title="Edit Poster">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        </a>

                                        <form action="{{ route('admin.gallery.destroy', $gallery->id) }}" method="POST" onsubmit="return confirm('WARNING: Are you sure you want to delete this intel poster?');" class="m-0">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 bg-red-900/30 hover:bg-red-500 text-red-400 hover:text-white rounded border border-red-500/50 transition-colors" title="Delete Poster">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="md:col-span-2 py-12 flex flex-col items-center justify-center text-purple-500/50 border-2 border-dashed border-purple-500/20 rounded-lg theme-card">
                            <svg class="w-16 h-16 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            <p class="text-sm font-bold tracking-widest uppercase">No intel posters found in the database.</p>
                        </div>
                    @endforelse
                </div>
            </div>
            
        </div>
    </div>

    <script type="text/javascript">
        function googleTranslateElementInit() {
            new google.translate.TranslateElement({
                pageLanguage: 'ms', // Asal (Malay)
                includedLanguages: 'en,ms', 
                autoDisplay: false
            }, 'google_translate_element');
        }
    </script>
    <script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>

    <script>
        tinymce.init({
            selector: '#intel-editor',
            height: 400,
            skin: 'oxide-dark',
            content_css: 'dark',
            menubar: false,
            plugins: [
                'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
                'anchor', 'searchreplace', 'visualblocks', 'fullscreen',
                'insertdatetime', 'media', 'table', 'code', 'help', 'wordcount'
            ],
            toolbar: 'fullscreen | blocks fontfamily | ' +
            'bold italic underline forecolor backcolor | alignleft aligncenter ' +
            'alignright alignjustify | bullist numlist outdent indent | ' +
            'removeformat',
            content_style: 'body { font-family: "Share Tech Mono", monospace; font-size:14px; background-color: #100020; color: #e9d5ff; padding: 10px; } p { margin: 0 0 10px 0; }',
            setup: function (editor) {
                editor.on('change', function () {
                    editor.save();
                });
            }
        });
    </script>

    <script>
        const bodyEl = document.body;
        const themeBtn = document.getElementById('theme-toggle');
        const langBtn = document.getElementById('lang-toggle');

        let currentTheme = localStorage.getItem('shield_theme') || 'dark';
        let currentLang = localStorage.getItem('shield_lang') || 'ms';

        if (currentTheme === 'light') {
            bodyEl.classList.add('light-mode');
            themeBtn.innerHTML = '🌙 DARK MODE';
        }

        langBtn.innerHTML = currentLang === 'en' ? '🇲🇾 BAHASA' : '🇬🇧 ENGLISH';

        themeBtn.addEventListener('click', () => {
            currentTheme = currentTheme === 'dark' ? 'light' : 'dark';
            localStorage.setItem('shield_theme', currentTheme);
            if(currentTheme === 'light') { bodyEl.classList.add('light-mode'); themeBtn.innerHTML = '🌙 DARK MODE'; } 
            else { bodyEl.classList.remove('light-mode'); themeBtn.innerHTML = '☀️ LIGHT MODE'; }
        });

        // Seamless Google Translate Toggle Logic
        langBtn.addEventListener('click', () => {
            if (currentLang === 'ms') {
                localStorage.setItem('shield_lang', 'en');
                document.cookie = "googtrans=/ms/en; path=/";
                document.cookie = `googtrans=/ms/en; path=/; domain=${location.hostname}`;
            } else {
                localStorage.setItem('shield_lang', 'ms');
                document.cookie = "googtrans=/ms/ms; path=/";
                document.cookie = `googtrans=/ms/ms; path=/; domain=${location.hostname}`;
            }
            window.location.reload(); 
        });
    </script>
    @include('partials.cursor')
</body>
</html>