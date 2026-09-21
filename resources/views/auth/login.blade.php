<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LZNK Cakna Siber // Terminal Akses</title>
    @vite('resources/css/app.css')
    <link href="https://fonts.googleapis.com/css2?family=Anton&family=Poppins:wght@700&family=Share+Tech+Mono&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Share Tech Mono', monospace; }
        /* This hides the default cursor inside your iframes/sub-pages */
    body, a, button, input, select, textarea {
    cursor: none !important;
}
        /* HOVERING LOGO CSS */
        .animate-logo-hover { animation: logo-hover 3s ease-in-out infinite; }
        @keyframes logo-hover {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }
        
        /* HEAVY TITLE FONT */
        .title-font {
            font-family: 'Anton', sans-serif;
            text-shadow: 0 0 5px rgba(16,185,129,0.8);
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
            /* 🚨 FIX: Solid pure white background instead of metallic gradient */
            --card-bg: rgba(255, 255, 255, 0.95); 
            --title-color: #064e3b;
            --value-color: #0f172a;
            --btn-bg: rgba(15, 23, 42, 0.9);
            /* 🚨 FIX: Clean drop shadow, removed the metallic edge inset */
            --glossy-sheen: 0 10px 25px -5px rgba(0, 0, 0, 0.1); 
        }

        /* --- DYNAMIC LOGO GLOW --- */
        .theme-logo { filter: drop-shadow(0 0 8px rgba(16, 185, 129, 0.9)); transition: filter 0.5s ease; }
        .light-mode .theme-logo { filter: drop-shadow(0 0 8px rgba(255, 215, 0, 0.7)); } /* Reduced Gold Glow */

        /* --- DYNAMIC TEXT READABILITY FOR LIGHT MODE --- */
        .light-mode .text-white { color: #000000 !important; text-shadow: none !important; }
        
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

<body class="min-h-screen flex flex-col items-center justify-center relative overflow-y-auto overflow-x-hidden transition-colors duration-500 py-12 md:py-0">

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

    <div class="z-10 w-full max-w-4xl flex flex-col items-center px-4 mt-10 md:mt-0">
        
        <img src="{{ asset('img/logo2.png') }}" alt="LZNK Logo" class="w-48 md:w-64 h-auto mb-6 animate-logo-hover object-contain theme-logo">
        
        <div class="theme-card backdrop-blur-md border border-emerald-500/30 rounded-2xl px-6 py-4 md:px-10 md:py-5 mb-8 shadow-[0_0_30px_rgba(16,185,129,0.15)] inline-flex flex-col items-center text-center max-w-[90%]">
            <h1 class="theme-title title-font text-4xl md:text-6xl leading-tight uppercase mb-1">
                LZNK Cakna Siber<br>
                <span class="text-white drop-shadow-md transition-colors duration-300">Escape Room : S.H.I.E.L.D</span>
            </h1>
            <p class="text-emerald-500 font-bold tracking-widest uppercase mt-2 drop-shadow-[0_0_5px_rgba(16,185,129,0.8)] dynamic-dark-green" 
                data-en="Awaiting Agent {{ auth()->check() ? auth()->user()->name : '' }} Initialization..." 
                data-ms="Menunggu Pengesahan Ejen {{ auth()->check() ? auth()->user()->name : '' }}...">
                Awaiting Agent {{ auth()->check() ? auth()->user()->name : '' }} Initialization...
            </p>
        </div>

        <div class="theme-card w-full max-w-md backdrop-blur-xl border border-emerald-500/50 rounded-lg p-8 relative overflow-hidden">
            <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI0IiBoZWlnaHQ9IjQiIHZpZXdCb3g9IjAgMCA0IDQiPjxnIGZpbGwtcnVsZT0iZXZlbm9kZCI+PGcgZmlsbD0iIzIyYTU1ZSIgZmlsbC1vcGFjaXR5PSIwLjA1Ij48cGF0aCBkPSJNMCAwaDR2MUgwVjB6bTAgM2g0djFIMFYzek0wIDFoMXYyaC0xVjF6bTMgMGgxdjJoLTFWMXoiLz48L2c+PC9nPjwvc3ZnPg==')] opacity-30"></div>

            <div class="relative z-10 flex items-center text-emerald-500 text-sm mb-6 border-b border-emerald-500/30 pb-3 font-bold tracking-wider dynamic-dark-green" data-en="SECURE ACCESS" data-ms="AKSES SELAMAT">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
                SECURE ACCESS
            </div>

            <form method="POST" action="{{ route('login.post') }}" class="relative z-10 w-full flex flex-col gap-4">
                @csrf
                
                <div class="w-full">
                    <label class="block text-xs text-emerald-600 mb-1 uppercase tracking-widest font-bold dynamic-dark-green" data-en="Agent ID" data-ms="ID Ejen">Agent ID</label>
                    <input type="text" name="agent_id" required class="w-full bg-black/50 border border-emerald-500/50 rounded px-4 py-2.5 text-sm text-emerald-100 focus:outline-none focus:border-emerald-400 focus:ring-1 focus:ring-emerald-400 transition-all placeholder:text-gray-500">
                </div>

                <div class="w-full">
                    <div class="flex justify-between items-end mb-1">
                        <label class="block text-xs text-emerald-600 uppercase tracking-widest font-bold dynamic-dark-green" data-en="Password Key" data-ms="Kata Laluan">Password Key</label>
                        
                        <a href="{{ url('/forgot-password') }}" class="text-[10px] text-emerald-500 hover:text-emerald-400 uppercase tracking-widest font-bold transition-colors dynamic-dark-green" data-en="Forgot Key?" data-ms="Lupa Kunci?">
                            Forgot Password?
                        </a>
                    </div>
                    
                    <div class="relative">
                        <input type="password" id="password-field" name="password" required class="w-full bg-black/50 border border-emerald-500/50 rounded px-4 py-2.5 text-sm text-emerald-100 focus:outline-none focus:border-emerald-400 focus:ring-1 focus:ring-emerald-400 transition-all placeholder:text-gray-500 pr-10">
                        
                        <button type="button" id="toggle-password" class="absolute inset-y-0 right-0 pr-3 flex items-center text-emerald-600 hover:text-emerald-400 focus:outline-none transition-colors" aria-label="Toggle Password Visibility">
                            <svg id="eye-open" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <svg id="eye-closed" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                            </svg>
                        </button>
                    </div>
                </div>

                @if ($errors->any())
                <div class="w-full text-center mt-2">
                    <span class="text-red-500 text-xs font-bold tracking-widest drop-shadow-[0_0_5px_rgba(239,68,68,0.8)]">
                        {{ $errors->first() }}
                    </span>
                </div>
                @endif

                <button type="submit" class="w-full mt-2 bg-emerald-600 hover:bg-emerald-500 text-white font-bold py-3 px-4 rounded shadow-[0_0_20px_rgba(16,185,129,0.4)] hover:shadow-[0_0_30px_rgba(16,185,129,0.7)] transition-all transform hover:-translate-y-1 tracking-widest border border-emerald-400/50">
                    <span data-en="ACCESS SYSTEM" data-ms="AKSES SISTEM">ACCESS SYSTEM</span>
                </button>
            </form>

            <a href="{{ route('register') }}" class="block relative z-10 mt-6 text-xs text-emerald-600 hover:text-emerald-400 transition-colors text-center tracking-wider font-bold dynamic-dark-green" data-en="New Agent? Request Clearance" data-ms="Ejen Baru? Mohon Akses">
                New Agent? Request Clearance
            </a>
        </div>

        <div class="mt-8 pb-8 text-[10px] text-emerald-700/80 tracking-widest uppercase font-bold text-center">
            Clearance Level: Restricted // S.H.I.E.L.D Intranet<br>
            Powered by LZNK Cakna Siber<br>
            Fully Developed by Ahmad Hanif (LI-0018)
        </div>
    </div>

    <audio id="ui-click-sound" src="{{ asset('audio/mixkit-sci-fi-click-900.wav') }}" preload="auto"></audio>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // --- CLICK SOUND EFFECT LOGIC ---
            const clickSound = document.getElementById('ui-click-sound');
            if (clickSound) {
                clickSound.volume = 0.6;
                const clickableElements = document.querySelectorAll('button, a');
                
                clickableElements.forEach(element => {
                    element.addEventListener('click', () => {
                        clickSound.currentTime = 0; 
                        clickSound.play().catch(e => console.log("Click sound blocked:", e));
                    });
                });
            }

            // --- PASSWORD VISIBILITY TOGGLE ---
            const togglePasswordBtn = document.getElementById('toggle-password');
            const passwordField = document.getElementById('password-field');
            const eyeOpen = document.getElementById('eye-open');
            const eyeClosed = document.getElementById('eye-closed');

            if (togglePasswordBtn && passwordField) {
                togglePasswordBtn.addEventListener('click', function(e) {
                    e.preventDefault(); 
                    
                    const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
                    passwordField.setAttribute('type', type);
                    
                    if (type === 'text') {
                        eyeOpen.classList.add('hidden');
                        eyeClosed.classList.remove('hidden');
                    } else {
                        eyeOpen.classList.remove('hidden');
                        eyeClosed.classList.add('hidden');
                    }
                });
            }

            // --- THEME & LANGUAGE LOGIC ---
            const bodyEl = document.body;
            const themeBtn = document.getElementById('theme-toggle');
            const langBtn = document.getElementById('lang-toggle');
            const translatables = document.querySelectorAll('[data-en]');

            let currentTheme = localStorage.getItem('shield_theme') || 'dark';
            let currentLang = localStorage.getItem('shield_lang') || 'en';

            if (currentTheme === 'light') {
                bodyEl.classList.add('light-mode');
                if (themeBtn) themeBtn.innerHTML = '🌙 DARK MODE';
            }

            function applyLanguage(lang) {
                if(langBtn) langBtn.innerHTML = lang === 'en' ? '🇲🇾 BAHASA' : '🇬🇧 ENGLISH';
                translatables.forEach(el => {
                    if (el.tagName === 'SPAN' || el.tagName === 'P' || el.tagName === 'H1' || el.tagName === 'DIV' || el.tagName === 'LABEL' || el.tagName === 'A') {
                        if(el.getAttribute(`data-${lang}`)) {
                            const textSpan = el.querySelector('span');
                            if(textSpan && !el.hasAttribute('data-en')) textSpan.innerText = el.getAttribute(`data-${lang}`);
                            else el.innerText = el.getAttribute(`data-${lang}`);
                        }
                    }
                });
            }
            applyLanguage(currentLang);

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
        });
    </script>
    @include('partials.cursor')
</body>
</html>