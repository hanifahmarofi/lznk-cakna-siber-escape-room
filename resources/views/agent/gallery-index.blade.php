<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>S.H.I.E.L.D // Intel Gallery</title>
    @vite('resources/css/app.css')
    <link href="https://fonts.googleapis.com/css2?family=Anton&family=Poppins:wght@700;900&family=Share+Tech+Mono&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Share Tech Mono', monospace; background-color: #050505; color: #d1d5db; overflow-x: hidden; transition: background-color 0.3s ease; }
        .title-font { font-family: 'Poppins', sans-serif; text-shadow: 0 0 10px rgba(16,185,129,0.7); }
        
        :root {
            --bg-color: #050505; --text-color: #d1d5db; 
            --card-bg: rgba(21, 21, 21, 0.85); --card-hover: rgba(31, 31, 31, 0.9);
            --vid-opacity: 0.6; --vid-overlay: linear-gradient(to bottom, rgba(0,0,0,0.8), rgba(0,0,0,0.4), rgba(0,0,0,0.9));
        }
        
        .light-mode {
            --bg-color: #f8fafc; --text-color: #0f172a;
            --card-bg: rgba(229, 231, 235, 0.95); --card-hover: rgba(243, 244, 246, 0.98);
            --vid-opacity: 0.15; --vid-overlay: linear-gradient(to bottom, rgba(31, 41, 55, 0.9), rgba(156, 163, 175, 0.5), rgba(17, 24, 39, 0.95));
        }
        
        .light-mode .text-gray-300, .light-mode .text-gray-400, .light-mode .text-gray-500 { color: #000000 !important; }
        .light-mode .text-emerald-500 { color: #047857 !important; }
        .light-mode .text-emerald-400 { color: #059669 !important; } 

        /* 🔥 Requirement 1: Force Black Text on Toggles in Light Mode */
        .light-mode #lang-toggle, .light-mode #theme-toggle {
            color: #000000 !important;
            border-color: #059669 !important;
            background-color: rgba(255, 255, 255, 0.9) !important;
        }
        
        .theme-video { opacity: var(--vid-opacity); transition: opacity 0.5s ease; }
        .theme-overlay { background: var(--vid-overlay); transition: background 0.5s ease; }
        .theme-card { background: var(--card-bg) !important; backdrop-filter: blur(8px); transition: background 0.3s ease, box-shadow 0.3s ease; }
        .theme-card:hover { background: var(--card-hover) !important; box-shadow: 0 15px 25px -5px rgba(0, 0, 0, 0.2); }

        .poster-card-title {
            font-family: 'Anton', sans-serif;
            color: #facc15 !important; 
            -webkit-text-stroke: 1.5px #111827; 
            paint-order: stroke fill;
            text-shadow: 2px 3px 5px rgba(0, 0, 0, 0.3);
            letter-spacing: 0.05em;
            line-height: 1.2;
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
    </style>
</head>
<body class="min-h-screen relative pb-10 transition-colors duration-500">

    <div id="google_translate_element"></div>

    <div class="fixed inset-0 z-0 overflow-hidden pointer-events-none">
        <video autoplay loop muted playsinline class="theme-video w-full h-full object-cover">
            <source src="{{ asset('video/circuitboard.mp4') }}" type="video/mp4">
        </video>
        <div class="absolute inset-0 bg-emerald-600 mix-blend-color opacity-30 pointer-events-none"></div>
        <div class="theme-overlay absolute inset-0 pointer-events-none transition-background duration-500"></div>
    </div>

    <div class="fixed top-4 right-4 z-[9999] flex gap-3">
        <button id="lang-toggle" class="theme-card border border-emerald-500/50 px-3 py-1.5 rounded-full text-xs font-bold tracking-wider hover:bg-emerald-500 hover:text-white transition-colors text-emerald-400 shadow-lg">
            🇬🇧 ENGLISH
        </button>
        <button id="theme-toggle" class="theme-card border border-emerald-500/50 px-3 py-1.5 rounded-full text-xs font-bold tracking-wider hover:bg-emerald-500 hover:text-white transition-colors text-emerald-400 shadow-lg">
            ☀️ LIGHT MODE
        </button>
    </div>

    <div class="max-w-7xl mx-auto relative z-10 p-4 md:p-8 mt-12 md:mt-4">
        <header class="flex flex-col md:flex-row justify-between items-start md:items-center mb-10 border-b border-emerald-500/30 pb-4 gap-4">
            <div>
                <h1 class="title-font text-3xl md:text-4xl text-emerald-400 font-bold tracking-wider uppercase">Galeri Intel</h1>
                <p class="text-sm text-gray-400 tracking-widest uppercase mt-1 font-bold">Pangkalan Pengetahuan S.H.I.E.L.D</p>
            </div>
            <a href="{{ route('dashboard') }}" class="px-5 py-2 rounded theme-card border border-emerald-500/50 text-emerald-400 hover:bg-emerald-500 hover:text-black transition-colors font-bold uppercase tracking-widest text-xs flex items-center gap-2 shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali ke Pusat Kawalan
            </a>
        </header>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            @forelse($galleries as $gallery)
                <a href="{{ route('agent.gallery.show', $gallery->id) }}" class="flex flex-col border border-emerald-500/30 rounded-lg overflow-hidden group hover:border-emerald-400 transition-all shadow-[0_0_15px_rgba(0,0,0,0.5)] hover:shadow-[0_0_20px_rgba(16,185,129,0.4)] transform hover:-translate-y-1">
                    
                    <div class="aspect-[3/4] overflow-hidden relative bg-black shrink-0">
                        <img src="{{ asset('storage/' . $gallery->image_path) }}" alt="{{ $gallery->title }}" class="w-full h-full object-cover opacity-80 group-hover:opacity-100 group-hover:scale-105 transition-all duration-500">
                    </div>

                    <div class="bg-gray-100 flex-grow flex items-center justify-center p-3 md:p-4 border-t-2 border-emerald-500/50 group-hover:bg-white transition-colors">
                        <h4 class="text-xl md:text-2xl uppercase text-center poster-card-title">{{ $gallery->title }}</h4>
                    </div>
                </a>
            @empty
                <div class="col-span-2 md:col-span-4 py-12 text-center text-gray-500 font-bold tracking-widest uppercase border border-dashed border-gray-600 rounded-lg theme-card">
                    Tiada intel tersedia.
                </div>
            @endforelse
        </div>
    </div>

    <audio id="ui-click-sound" src="{{ asset('audio/mixkit-sci-fi-click-900.wav') }}" preload="auto"></audio>

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
        const bodyEl = document.body;
        const themeBtn = document.getElementById('theme-toggle');
        const langBtn = document.getElementById('lang-toggle');

        let currentTheme = localStorage.getItem('shield_theme') || 'dark';
        let currentLang = localStorage.getItem('shield_lang') || 'ms'; // Default is Malay

        if (currentTheme === 'light') {
            bodyEl.classList.add('light-mode');
            themeBtn.innerHTML = '🌙 DARK MODE';
        }

        // Set initial button text based on current language
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