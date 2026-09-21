<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $gallery->title }} // S.H.I.E.L.D</title>
    @vite('resources/css/app.css')
    <link href="https://fonts.googleapis.com/css2?family=Anton&family=Poppins:wght@700;900&family=Share+Tech+Mono&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Share Tech Mono', monospace; background-color: #050505; color: #d1d5db; overflow-x: hidden; transition: background-color 0.3s ease; }
        .title-font { font-family: 'Poppins', sans-serif; text-shadow: 0 0 15px rgba(250, 204, 21, 0.5); }
        
        :root {
            --bg-color: #050505; --text-color: #d1d5db; 
            --card-bg: rgba(21, 21, 21, 0.85); 
            --vid-opacity: 0.6; --vid-overlay: linear-gradient(to bottom, rgba(0,0,0,0.8), rgba(0,0,0,0.4), rgba(0,0,0,0.9));
        }
        
        .light-mode {
            --bg-color: #f8fafc; --text-color: #0f172a;
            --card-bg: rgba(229, 231, 235, 0.95);
            --vid-opacity: 0.15; --vid-overlay: linear-gradient(to bottom, rgba(31, 41, 55, 0.9), rgba(156, 163, 175, 0.5), rgba(17, 24, 39, 0.95));
        }
        
        .light-mode .text-gray-300, .light-mode .text-gray-400 { color: #111827 !important; }
        
        /* 🔥 Requirement 1: Force Black Text on Toggles in Light Mode */
        .light-mode #lang-toggle, .light-mode #theme-toggle {
            color: #000000 !important;
            border-color: #059669 !important;
            background-color: rgba(255, 255, 255, 0.9) !important;
        }
        
        .theme-video { opacity: var(--vid-opacity); transition: opacity 0.5s ease; }
        .theme-overlay { background: var(--vid-overlay); transition: background 0.5s ease; }
        .theme-card { background: var(--card-bg) !important; backdrop-filter: blur(8px); }

        .custom-scrollbar::-webkit-scrollbar { width: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: rgba(0,0,0,0.1); border-radius: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(16,185,129,0.5); border-radius: 4px; }

        /* 🔥 HIDE DEFAULT GOOGLE TRANSLATE UI ELEMENTS 🔥 */
        .goog-te-banner-frame { display: none !important; }
        .goog-te-menu-value { display: none !important; }
        .goog-te-gadget-icon { display: none !important; }
        .goog-tooltip { display: none !important; }
        .goog-tooltip:hover { display: none !important; }
        .goog-text-highlight { background-color: transparent !important; border: none !important; box-shadow: none !important; }
        body { top: 0 !important; }
        #google_translate_element { display: none !important; }
    </style>
</head>
<body class="min-h-screen relative pb-16 transition-colors duration-500">

    <div id="google_translate_element"></div>

    <div class="fixed inset-0 z-0 overflow-hidden pointer-events-none">
        <video autoplay loop muted playsinline class="theme-video w-full h-full object-cover">
            <source src="{{ asset('video/circuitboard.mp4') }}" type="video/mp4">
        </video>
        <div class="absolute inset-0 bg-emerald-600 mix-blend-color opacity-30 pointer-events-none"></div>
        <div class="theme-overlay absolute inset-0 pointer-events-none transition-background duration-500"></div>
    </div>

    <div class="fixed top-4 right-4 z-50 flex gap-3">
        <button id="lang-toggle" class="theme-card border border-emerald-500/50 px-3 py-1.5 rounded-full text-xs font-bold tracking-wider hover:bg-emerald-500 hover:text-white transition-colors">
            🇬🇧 ENGLISH
        </button>
        <button id="theme-toggle" class="theme-card border border-emerald-500/50 px-3 py-1.5 rounded-full text-xs font-bold tracking-wider hover:bg-emerald-500 hover:text-white transition-colors">
            ☀️ LIGHT MODE
        </button>
    </div>

    <div class="max-w-7xl mx-auto relative z-10 p-4 md:p-8 mt-12 md:mt-6">
        
        <div class="mb-6">
            <a href="{{ route('agent.gallery') }}" class="inline-flex items-center gap-2 text-emerald-400 hover:text-black hover:bg-emerald-500 transition-all uppercase tracking-widest text-xs font-bold theme-card px-4 py-2 rounded-full border border-emerald-500/50">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali ke Galeri
            </a>
        </div>

        <div class="theme-card border border-emerald-500/30 rounded-xl overflow-hidden shadow-[0_0_30px_rgba(16,185,129,0.15)] flex flex-col items-center p-6 md:p-12">
            
            <div class="relative inline-block w-full max-w-md rounded-lg overflow-hidden border-2 border-emerald-500/30 shadow-lg bg-black group mb-8">
                <img src="{{ asset('storage/' . $gallery->image_path) }}" alt="{{ $gallery->title }}" class="w-full h-auto object-contain max-h-[50vh]">
                
                <button onclick="openLightbox()" class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center backdrop-blur-sm cursor-pointer">
                    <div class="bg-emerald-500 text-black px-4 py-2 rounded-full font-bold uppercase tracking-widest text-xs flex items-center gap-2 shadow-[0_0_15px_rgba(16,185,129,0.8)]">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"></path></svg>
                        Besarkan Imej
                    </div>
                </button>
            </div>
            
            <div class="w-full border-2 border-white rounded-lg p-4 md:p-6 mb-8 bg-black/60 shadow-[0_0_15px_rgba(255,255,255,0.1)]">
                <h1 class="title-font text-3xl md:text-5xl font-black text-yellow-400 uppercase tracking-wider text-center drop-shadow-md">{{ $gallery->title }}</h1>
            </div>
            
            <div class="w-full text-gray-300 text-base md:text-lg leading-relaxed space-y-6 custom-scrollbar font-bold tracking-wide text-justify md:text-left">
                {!! $gallery->description !!}
            </div>
            
        </div>
    </div>

    <div id="lightbox-modal" class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/95 backdrop-blur-md opacity-0 transition-opacity duration-300 p-4">
        <button onclick="closeLightbox()" class="absolute top-4 right-4 z-50 text-gray-400 hover:text-white bg-gray-900/50 hover:bg-red-500 rounded-full p-3 transition-colors border border-gray-600">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
        <div class="relative w-full h-full flex items-center justify-center" onclick="closeLightbox()">
            <img src="{{ asset('storage/' . $gallery->image_path) }}" alt="{{ $gallery->title }}" class="max-w-full max-h-full object-contain rounded drop-shadow-2xl" onclick="event.stopPropagation();">
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
        // --- Lightbox Logic ---
        const lightbox = document.getElementById('lightbox-modal');
        function openLightbox() {
            lightbox.classList.remove('hidden'); lightbox.classList.add('flex');
            document.body.style.overflow = 'hidden';
            setTimeout(() => { lightbox.classList.remove('opacity-0'); }, 10);
        }
        function closeLightbox() {
            lightbox.classList.add('opacity-0'); document.body.style.overflow = '';
            setTimeout(() => { lightbox.classList.add('hidden'); lightbox.classList.remove('flex'); }, 300);
        }

        // --- Theme & Google Translate Logic ---
        const bodyEl = document.body;
        const themeBtn = document.getElementById('theme-toggle');
        const langBtn = document.getElementById('lang-toggle');

        let currentTheme = localStorage.getItem('shield_theme') || 'dark';
        let currentLang = localStorage.getItem('shield_lang') || 'ms'; // Default is Malay

        if (currentTheme === 'light') {
            bodyEl.classList.add('light-mode');
            themeBtn.innerHTML = '🌙 DARK MODE';
        }

        // Initialize Language Button Text
        langBtn.innerHTML = currentLang === 'en' ? '🇲🇾 BAHASA' : '🇬🇧 ENGLISH';

        themeBtn.addEventListener('click', () => {
            currentTheme = currentTheme === 'dark' ? 'light' : 'dark';
            localStorage.setItem('shield_theme', currentTheme);
            if(currentTheme === 'light') { bodyEl.classList.add('light-mode'); themeBtn.innerHTML = '🌙 DARK MODE'; } 
            else { bodyEl.classList.remove('light-mode'); themeBtn.innerHTML = '☀️ LIGHT MODE'; }
        });

        // 🔥 Seamless Google Translate Toggle Logic
        langBtn.addEventListener('click', () => {
            if (currentLang === 'ms') {
                // Change to English
                localStorage.setItem('shield_lang', 'en');
                document.cookie = "googtrans=/ms/en; path=/";
                document.cookie = `googtrans=/ms/en; path=/; domain=${location.hostname}`;
            } else {
                // Revert to Malay
                localStorage.setItem('shield_lang', 'ms');
                document.cookie = "googtrans=/ms/ms; path=/";
                document.cookie = `googtrans=/ms/ms; path=/; domain=${location.hostname}`;
            }
            window.location.reload(); // Refresh the page to apply the Google translation instantly
        });
    </script>
    @include('partials.cursor')
</body>
</html>