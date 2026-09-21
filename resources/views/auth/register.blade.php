<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LZNK Cakna Siber // Request Clearance</title>
    @vite('resources/css/app.css')
    <link href="https://fonts.googleapis.com/css2?family=Anton&family=Poppins:wght@700&family=Share+Tech+Mono&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Share Tech Mono', monospace; }
        
        /* HOVERING LOGO CSS */
        .animate-logo-hover { animation: logo-hover 3s ease-in-out infinite; }
        @keyframes logo-hover {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }
        
        /* HEAVY TITLE FONT */
        .title-font {
            font-family: 'Anton', sans-serif;
            text-shadow: 0 0 15px rgba(16,185,129,0.8);
            letter-spacing: 0.05em;
        }

        /* ********************************************************** */
        /* *** PURE CSS THEME ENGINE (SYNCED WITH DASHBOARD) *** */
        /* ********************************************************** */
        :root {
            --bg-color: #000000;
            --text-color: #d1d5db;
            --vid-opacity: 0.8;
            --vid-overlay: linear-gradient(to bottom, rgba(0,0,0,0.8), rgba(0,0,0,0.2), rgba(0,0,0,0.9));
            --card-bg: rgba(5, 5, 5, 0.6);
            --title-color: #34d399; /* emerald-400 */
            --value-color: #ffffff;
            --btn-bg: rgba(255, 255, 255, 0.9);
            --glossy-sheen: none;
        }

        .light-mode {
            --bg-color: #d1d5db;
            --text-color: #0f172a;
            --vid-opacity: 0.15;
            --vid-overlay: linear-gradient(to bottom, rgba(31, 41, 55, 0.9), rgba(156, 163, 175, 0.5), rgba(17, 24, 39, 0.95));
            --card-bg: linear-gradient(135deg, rgba(229, 231, 235, 0.95) 0%, rgba(209, 213, 219, 0.95) 50%, rgba(156, 163, 175, 0.9) 100%);
            --title-color: #064e3b;
            --value-color: #0f172a;
            --btn-bg: rgba(15, 23, 42, 0.9);
            --glossy-sheen: inset 0 1px 0 rgba(255,255,255,0.7), 0 10px 15px -3px rgba(0, 0, 0, 0.15);
        }

        /* --- DYNAMIC LOGO GLOW --- */
        .theme-logo { filter: drop-shadow(0 0 8px rgba(16, 185, 129, 0.9)); transition: filter 0.5s ease; }
        .light-mode .theme-logo { filter: drop-shadow(0 0 8px rgba(255, 215, 0, 0.7)); } /* Reduced Gold Glow */

        /* --- DYNAMIC TEXT READABILITY FOR LIGHT MODE --- */
        .light-mode .dynamic-dark-green {
            color: #064e3b !important; /* Very Dark Emerald Green */
            filter: drop-shadow(0 0 0 rgba(0,0,0,0)) !important; /* Removes the blurry neon shadow */
            text-shadow: none !important;
        }
        .light-mode a.dynamic-dark-green:hover {
            color: #022c22 !important; /* Even darker on hover for links */
        }

        body { background-color: var(--bg-color); color: var(--text-color); transition: background-color 0.5s ease; }
        .theme-video { opacity: var(--vid-opacity); transition: opacity 0.5s ease; }
        .theme-overlay { background: var(--vid-overlay); transition: background 0.5s ease; }
        .theme-card { background: var(--card-bg) !important; transition: background 0.3s ease, box-shadow 0.3s ease; box-shadow: var(--glossy-sheen); }
        .theme-title { color: var(--title-color) !important; }
        /* ********************************************************** */
    </style>
</head>

<body class="min-h-screen flex flex-col items-center justify-center relative overflow-y-auto overflow-x-hidden transition-colors duration-500 py-10">

    <div class="fixed inset-0 z-0">
        <video autoplay loop muted playsinline class="theme-video w-full h-full object-cover">
            <source src="{{ asset('video/videoplayback.webm') }}" type="video/webm">
        </video>
        <div class="absolute inset-0 bg-emerald-600 mix-blend-color opacity-90 pointer-events-none"></div>
        <div class="theme-overlay absolute inset-0 pointer-events-none transition-background duration-500"></div>
    </div>

    <div class="fixed top-4 right-4 z-50 flex gap-3">
        <button id="lang-toggle" class="theme-card backdrop-blur border border-emerald-500/50 px-3 py-1.5 rounded-full text-xs font-bold tracking-wider hover:bg-emerald-500 hover:text-white transition-colors text-emerald-500">
            🇲🇾 BAHASA
        </button>
        <button id="theme-toggle" class="theme-card backdrop-blur border border-emerald-500/50 px-3 py-1.5 rounded-full text-xs font-bold tracking-wider hover:bg-emerald-500 hover:text-white transition-colors text-emerald-500">
            ☀️ LIGHT MODE
        </button>
    </div>

    <div class="z-10 w-full max-w-4xl flex flex-col items-center px-4 mt-12 md:mt-0">
        
        <div class="mb-4 flex flex-col items-center">
            <img src="{{ asset('img/logo2.png') }}" alt="LZNK Logo" style="max-width: 250px; height: auto;" class="mb-3 animate-logo-hover object-contain theme-logo drop-shadow-[0_0_8px_rgba(16,185,129,0.8)]">
            
            <h1 class="theme-title title-font text-5xl md:text-6xl text-center leading-tight uppercase mb-2">
                LZNK Cakna Siber<br>
                <span class="text-white drop-shadow-md">Escape Room : S.H.I.E.L.D</span>
            </h1>
            
            <p class="text-emerald-500 font-bold tracking-widest uppercase mt-2 drop-shadow-[0_0_5px_rgba(16,185,129,0.8)] dynamic-dark-green" 
                data-en="Awaiting Agent Initialization..." 
                data-ms="Menunggu Pengesahan Ejen...">
                Awaiting Agent Initialization...
            </p>
        </div>

        <div class="theme-card w-full max-w-md backdrop-blur-xl border border-emerald-500/50 rounded-lg p-6 relative overflow-hidden">
            <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI0IiBoZWlnaHQ9IjQiIHZpZXdCb3g9IjAgMCA0IDQiPjxnIGZpbGwtcnVsZT0iZXZlbm9kZCI+PGcgZmlsbD0iIzIyYTU1ZSIgZmlsbC1vcGFjaXR5PSIwLjA1Ij48cGF0aCBkPSJNMCAwaDR2MUgwVjB6bTAgM2g0djFIMFYzek0wIDFoMXYyaC0xVjF6bTMgMGgxdjJoLTFWMXoiLz48L2c+PC9nPjwvc3ZnPg==')] opacity-30"></div>

            <div class="relative z-10 flex items-center text-emerald-500 text-sm mb-5 border-b border-emerald-500/30 pb-2 font-bold tracking-wider dynamic-dark-green" data-en="REQUEST AGENT CLEARANCE" data-ms="MOHON AKSES EJEN">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                </svg>
                REQUEST AGENT CLEARANCE
            </div>

            <form method="POST" action="{{ route('register.post') }}" class="relative z-10 w-full flex flex-col gap-3">
                @csrf
                
                <div class="w-full">
                    <label class="block text-[10px] text-emerald-600 mb-1 uppercase tracking-widest font-bold dynamic-dark-green" data-en="Full Name" data-ms="Nama Penuh">Full Name</label>
                    <input type="text" name="name" required class="w-full bg-black/50 border border-emerald-500/50 rounded px-3 py-2 text-sm text-emerald-100 focus:outline-none focus:border-emerald-400 focus:ring-1 focus:ring-emerald-400 transition-all placeholder:text-gray-500">
                </div>

                <div class="w-full">
                    <label class="block text-[10px] text-emerald-600 mb-1 uppercase tracking-widest font-bold dynamic-dark-green" data-en="Official Email" data-ms="E-mel Rasmi">Official Email</label>
                    <input type="email" name="email" required class="w-full bg-black/50 border border-emerald-500/50 rounded px-3 py-2 text-sm text-emerald-100 focus:outline-none focus:border-emerald-400 focus:ring-1 focus:ring-emerald-400 transition-all placeholder:text-gray-500">
                </div>

                <div class="w-full">
                    <label class="block text-[10px] text-emerald-600 mb-1 uppercase tracking-widest font-bold dynamic-dark-green" data-en="Desired Agent ID (For Login)" data-ms="ID Ejen Pilihan (Untuk Log Masuk)">Desired Agent ID (For Login)</label>
                    <input type="text" name="agent_id" required class="w-full bg-black/50 border border-emerald-500/50 rounded px-3 py-2 text-sm text-emerald-100 focus:outline-none focus:border-emerald-400 focus:ring-1 focus:ring-emerald-400 transition-all placeholder:text-gray-500">
                </div>

                <div class="grid grid-cols-2 gap-3 w-full">
                    <div>
                        <label class="block text-[10px] text-emerald-600 mb-1 uppercase tracking-widest font-bold dynamic-dark-green" data-en="Auth Key" data-ms="Kata Laluan">Auth Key</label>
                        <div class="relative">
                            <input type="password" id="password" name="password" required class="w-full bg-black/50 border border-emerald-500/50 rounded px-3 py-2 text-sm text-emerald-100 focus:outline-none focus:border-emerald-400 focus:ring-1 focus:ring-emerald-400 transition-all placeholder:text-gray-500 pr-8">
                            <button type="button" onclick="toggleVisibility('password', 'eye-open-1', 'eye-closed-1')" class="absolute inset-y-0 right-0 pr-2 flex items-center text-emerald-600 hover:text-emerald-400 focus:outline-none transition-colors">
                                <svg id="eye-open-1" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                <svg id="eye-closed-1" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" /></svg>
                            </button>
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-[10px] text-emerald-600 mb-1 uppercase tracking-widest font-bold dynamic-dark-green" data-en="Confirm Key" data-ms="Sahkan Laluan">Confirm Key</label>
                        <div class="relative">
                            <input type="password" id="password_confirmation" name="password_confirmation" required class="w-full bg-black/50 border border-emerald-500/50 rounded px-3 py-2 text-sm text-emerald-100 focus:outline-none focus:border-emerald-400 focus:ring-1 focus:ring-emerald-400 transition-all placeholder:text-gray-500 pr-8">
                            <button type="button" onclick="toggleVisibility('password_confirmation', 'eye-open-2', 'eye-closed-2')" class="absolute inset-y-0 right-0 pr-2 flex items-center text-emerald-600 hover:text-emerald-400 focus:outline-none transition-colors">
                                <svg id="eye-open-2" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                <svg id="eye-closed-2" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" /></svg>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="mt-4 pb-1">
                    <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-500 text-white font-bold py-2.5 px-4 rounded shadow-[0_0_20px_rgba(16,185,129,0.4)] hover:shadow-[0_0_30px_rgba(16,185,129,0.7)] transition-all transform hover:-translate-y-1 tracking-widest border border-emerald-400/50 text-sm">
                        <span data-en="SUBMIT CLEARANCE" data-ms="HANTAR PERMOHONAN">SUBMIT CLEARANCE</span>
                    </button>
                </div>

                @if ($errors->any())
                <div class="w-full text-center mt-2 pb-2">
                    <span class="text-red-500 text-xs font-bold tracking-widest drop-shadow-[0_0_5px_rgba(239,68,68,0.8)]">
                        {{ $errors->first() }}
                    </span>
                </div>
                @endif
                
            </form>

            <a href="{{ route('login') }}" class="block relative z-10 mt-4 text-[11px] text-emerald-600 hover:text-emerald-400 transition-colors text-center tracking-wider font-bold dynamic-dark-green" data-en="Existing Agent? Initialize Access" data-ms="Ejen Sedia Ada? Akses Masuk">
                Existing Agent? Initialize Access
            </a>
        </div>
        
        <div class="mt-8 pb-8 text-[10px] text-emerald-700/80 tracking-widest uppercase font-bold text-center">
            Clearance Level: Restricted // S.H.I.E.L.D Intranet<br>
            Powered by LZNK Cakna Siber
        </div>
    </div>

    <script>
        function toggleVisibility(inputId, openIconId, closedIconId) {
            const input = document.getElementById(inputId);
            const eyeOpen = document.getElementById(openIconId);
            const eyeClosed = document.getElementById(closedIconId);
            
            if (input.type === 'password') {
                input.type = 'text';
                eyeOpen.classList.add('hidden');
                eyeClosed.classList.remove('hidden');
            } else {
                input.type = 'password';
                eyeOpen.classList.remove('hidden');
                eyeClosed.classList.add('hidden');
            }
        }
    </script>

    <script>
        const bodyEl = document.body;
        const themeBtn = document.getElementById('theme-toggle');
        const langBtn = document.getElementById('lang-toggle');
        const translatables = document.querySelectorAll('[data-en]');

        // 1. Check Local Storage on Load
        let currentTheme = localStorage.getItem('shield_theme') || 'dark';
        let currentLang = localStorage.getItem('shield_lang') || 'en';

        // Apply saved theme
        if (currentTheme === 'light') {
            bodyEl.classList.add('light-mode');
            if(themeBtn) themeBtn.innerHTML = '🌙 DARK MODE';
        }

        // Apply saved language
        function applyLanguage(lang) {
            if(langBtn) langBtn.innerHTML = lang === 'en' ? '🇲🇾 BAHASA' : '🇬🇧 ENGLISH';
            translatables.forEach(el => {
                if (el.tagName === 'SPAN' || el.tagName === 'P' || el.tagName === 'H1' || el.tagName === 'DIV' || el.tagName === 'LABEL' || el.tagName === 'A' || el.tagName === 'BUTTON') {
                    if(el.getAttribute(`data-${lang}`)) {
                        const textSpan = el.querySelector('span');
                        if(textSpan && !el.hasAttribute('data-en')) textSpan.innerText = el.getAttribute(`data-${lang}`);
                        else el.innerText = el.getAttribute(`data-${lang}`);
                    }
                }
            });
        }
        applyLanguage(currentLang);

        // 2. Click Listeners
        if(themeBtn) {
            themeBtn.addEventListener('click', () => {
                currentTheme = currentTheme === 'dark' ? 'light' : 'dark';
                localStorage.setItem('shield_theme', currentTheme);
                
                if(currentTheme === 'light') {
                    bodyEl.classList.add('light-mode');
                    themeBtn.innerHTML = '🌙 DARK MODE';
                } else {
                    bodyEl.classList.remove('light-mode');
                    themeBtn.innerHTML = '☀️ LIGHT MODE';
                }
            });
        }

        if(langBtn) {
            langBtn.addEventListener('click', () => {
                currentLang = currentLang === 'en' ? 'ms' : 'en';
                localStorage.setItem('shield_lang', currentLang);
                applyLanguage(currentLang);
            });
        }
    </script>
    @include('partials.cursor')
</body>
</html>