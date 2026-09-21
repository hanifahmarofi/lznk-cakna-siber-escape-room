<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Poster // S.H.I.E.L.D</title>
    @vite('resources/css/app.css')
    <link href="https://fonts.googleapis.com/css2?family=Anton&family=Poppins:wght@700;900&family=Share+Tech+Mono&display=swap" rel="stylesheet">
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.8.2/tinymce.min.js" referrerpolicy="origin"></script>

    <style>
        :root {
            --bg-color: #050510; --text-color: #d1d5db; --card-bg: rgba(15, 10, 20, 0.85);
            --input-bg: #100020; --input-border: #7e22ce; --input-text: #e9d5ff;
            --vid-opacity: 0.4; --vid-overlay: rgba(0,0,0,0.7);
        }
        .light-mode {
            --bg-color: #f3f4f6; --text-color: #0f172a; --card-bg: rgba(255, 255, 255, 0.9);
            --input-bg: #f5f3ff; --input-border: #9333ea; --input-text: #3b0764;
            --vid-opacity: 0.1; --vid-overlay: rgba(255,255,255,0.8);
        }
        
        body { font-family: 'Share Tech Mono', monospace; background-color: var(--bg-color); color: var(--text-color); transition: background-color 0.3s ease; overflow-x: hidden; }
        .title-font { font-family: 'Poppins', sans-serif; text-shadow: 0 0 10px rgba(168,85,247,0.7); }
        .theme-card { background: var(--card-bg) !important; backdrop-filter: blur(8px); transition: background 0.3s ease; }
        .lz-input { background-color: var(--input-bg); border: 1px solid var(--input-border); color: var(--input-text); width: 100%; padding: 12px; border-radius: 6px; font-family: 'Share Tech Mono', monospace; transition: all 0.3s ease; }
        .lz-input:focus { outline: none; border-color: #a855f7; box-shadow: 0 0 15px rgba(168,85,247,0.4); }
        
        .theme-video { opacity: var(--vid-opacity); transition: opacity 0.5s ease; }
        
        /* Light Mode Text Overrides */
        .light-mode .text-purple-400 { color: #6b21a8 !important; }
        .light-mode .text-white { color: #000000 !important; }
        .light-mode .text-gray-400, .light-mode .text-gray-500 { color: #475569 !important; }
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
        body.tox-fullscreen .theme-card { backdrop-filter: none !important; z-index: 999999 !important; }
        .tox.tox-tinymce-fullscreen { position: fixed !important; z-index: 999999 !important; top: 0 !important; left: 0 !important; right: 0 !important; bottom: 0 !important; width: 100vw !important; height: 100vh !important; }
        .tox-tinymce-aux { z-index: 9999999 !important; }
        .tox-tinymce { border: 1px solid #7e22ce !important; border-radius: 6px !important; box-shadow: inset 0 0 10px rgba(0,0,0,0.5) !important;}
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
        <div class="absolute inset-0 pointer-events-none" style="background: var(--vid-overlay); transition: background 0.5s ease;"></div>
    </div>

    <div class="fixed top-4 right-4 z-[9999] flex gap-3">
        <button id="lang-toggle" class="theme-card border border-purple-500/50 px-3 py-1.5 rounded-full text-xs font-bold tracking-wider hover:bg-purple-500 hover:text-white transition-colors text-purple-400 shadow-lg">
            🇬🇧 ENGLISH
        </button>
        <button id="theme-toggle" class="theme-card border border-purple-500/50 px-3 py-1.5 rounded-full text-xs font-bold tracking-wider hover:bg-purple-500 hover:text-white transition-colors text-purple-400 shadow-lg">
            ☀️ LIGHT MODE
        </button>
    </div>

    <div class="max-w-4xl mx-auto relative z-10 p-4 md:p-8 mt-12 md:mt-6">
        <div class="mb-6 flex justify-between items-center">
            <a href="{{ route('admin.gallery.index') }}" class="inline-flex items-center gap-2 text-purple-400 hover:text-white hover:bg-purple-600 transition-all uppercase tracking-widest text-xs font-bold theme-card px-4 py-2 rounded border border-purple-500/50">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                <span data-en="Cancel Edit" data-ms="Batal Edit">Cancel Edit</span>
            </a>
        </div>

        <div class="theme-card rounded-lg p-6 md:p-10 border border-purple-500/30 shadow-[0_0_30px_rgba(168,85,247,0.15)]">
            <h1 class="title-font font-bold text-2xl md:text-3xl tracking-wider uppercase mb-6 text-purple-400 border-b border-purple-500/30 pb-4" data-en="EDIT INTEL POSTER" data-ms="SUNTING POSTER INTEL">
                EDIT INTEL POSTER
            </h1>

            <form action="{{ route('admin.gallery.update', $gallery->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PATCH')
                
                <div>
                    <label class="text-xs text-purple-400 font-bold uppercase tracking-widest mb-2 block" data-en="Poster Title" data-ms="Tajuk Poster">Poster Title</label>
                    <input type="text" name="title" value="{{ old('title', $gallery->title) }}" required class="lz-input notranslate">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-start">
                    <div class="md:col-span-1">
                        <label class="text-xs text-purple-400 font-bold uppercase tracking-widest mb-2 block" data-en="Current Poster" data-ms="Poster Semasa">Current Poster</label>
                        <div class="border border-purple-500/30 rounded p-2 bg-black">
                            <img src="{{ asset('storage/' . $gallery->image_path) }}" alt="Current Poster" class="w-full h-auto object-contain rounded">
                        </div>
                    </div>
                    
                    <div class="md:col-span-2">
                        <label class="text-xs text-purple-400 font-bold uppercase tracking-widest mb-2 block" data-en="Replace Image (Optional - Max 20MB)" data-ms="Ganti Imej (Pilihan - Max 20MB)">Replace Image (Optional - Max 20MB)</label>
                        <input type="file" name="image" accept="image/*" class="w-full text-sm text-purple-400 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-xs file:font-bold file:bg-purple-900/50 file:text-purple-300 hover:file:bg-purple-800 transition cursor-pointer border border-purple-500/30 rounded p-2 bg-[#100020]">
                        <p class="text-[10px] text-gray-500 mt-2 italic uppercase tracking-widest" data-en="Leave empty to keep the current image." data-ms="Biarkan kosong untuk mengekalkan imej semasa.">Leave empty to keep the current image.</p>
                    </div>
                </div>

                <div class="flex flex-col">
                    <label class="text-xs text-purple-400 font-bold uppercase tracking-widest mb-2 block" data-en="Intel Description" data-ms="Penerangan Intel">Intel Description</label>
                    <textarea id="intel-editor" name="description" class="notranslate">{{ old('description', $gallery->description) }}</textarea>
                </div>

                <div class="pt-4">
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-500 text-white font-bold py-4 px-4 rounded text-sm uppercase tracking-widest flex justify-center items-center gap-2 transition-all shadow-[0_0_15px_rgba(37,99,235,0.4)] hover:shadow-[0_0_25px_rgba(37,99,235,0.6)]">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                        <span data-en="Save Changes" data-ms="Simpan Perubahan">Save Changes</span>
                    </button>
                </div>
            </form>
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
        const translatables = document.querySelectorAll('[data-en]');

        let currentTheme = localStorage.getItem('shield_theme') || 'dark';
        let currentLang = localStorage.getItem('shield_lang') || 'ms';

        if (currentTheme === 'light') {
            bodyEl.classList.add('light-mode');
            themeBtn.innerHTML = '🌙 DARK MODE';
        }

        function applyLanguage(lang) {
            langBtn.innerHTML = lang === 'en' ? '🇲🇾 BAHASA' : '🇬🇧 ENGLISH';
            translatables.forEach(el => {
                if(el.getAttribute(`data-${lang}`)) {
                    const textSpan = el.querySelector('span');
                    if(textSpan) textSpan.innerText = el.getAttribute(`data-${lang}`);
                    else el.innerText = el.getAttribute(`data-${lang}`);
                }
            });
        }
        applyLanguage(currentLang);

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