<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LZNK Cakna Siber // System Recovery</title>
    @vite('resources/css/app.css')
    <link href="https://fonts.googleapis.com/css2?family=Anton&family=Poppins:wght@700&family=Share+Tech+Mono&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Share Tech Mono', monospace; }
        
        .animate-logo-hover { animation: logo-hover 3s ease-in-out infinite; }
        @keyframes logo-hover {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }
        
        .title-font {
            font-family: 'Anton', sans-serif;
            text-shadow: 0 0 15px rgba(16,185,129,0.8);
            letter-spacing: 0.05em;
        }

        :root {
            --bg-color: #000000;
            --text-color: #d1d5db;
            --vid-opacity: 0.8;
            --vid-overlay: linear-gradient(to bottom, rgba(0,0,0,0.8), rgba(0,0,0,0.2), rgba(0,0,0,0.9));
            --card-bg: rgba(5, 5, 5, 0.6); 
            --title-color: #34d399; 
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

        body { background-color: var(--bg-color); color: var(--text-color); transition: background-color 0.5s ease; }
        .theme-video { opacity: var(--vid-opacity); transition: opacity 0.5s ease; }
        .theme-overlay { background: var(--vid-overlay); transition: background 0.5s ease; }
        .theme-card { background: var(--card-bg) !important; transition: background 0.3s ease, box-shadow 0.3s ease; box-shadow: var(--glossy-sheen); }
        .theme-title { color: var(--title-color) !important; }
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
        
        <div class="mb-6 flex flex-col items-center">
            <img src="{{ asset('img/logo2.png') }}" alt="LZNK Logo" class="w-48 md:w-56 h-auto mb-6 animate-logo-hover object-contain drop-shadow-[0_0_15px_rgba(16,185,129,0.9)]">
            
            <h1 class="theme-title title-font text-4xl md:text-5xl text-center leading-tight uppercase mb-2">
                <span class="text-white drop-shadow-md">SYSTEM RECOVERY</span>
            </h1>
        </div>

        <div class="theme-card w-full max-w-md backdrop-blur-xl border border-emerald-500/50 rounded-lg p-8 relative overflow-hidden">
            <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI0IiBoZWlnaHQ9IjQiIHZpZXdCb3g9IjAgMCA0IDQiPjxnIGZpbGwtcnVsZT0iZXZlbm9kZCI+PGcgZmlsbD0iIzIyYTU1ZSIgZmlsbC1vcGFjaXR5PSIwLjA1Ij48cGF0aCBkPSJNMCAwaDR2MUgwVjB6bTAgM2g0djFIMFYzek0wIDFoMXYyaC0xVjF6bTMgMGgxdjJoLTFWMXoiLz48L2c+PC9nPjwvc3ZnPg==')] opacity-30"></div>

            <div class="relative z-10 flex items-center text-emerald-500 text-sm mb-4 border-b border-emerald-500/30 pb-3 font-bold tracking-wider" data-en="AUTHORIZATION OVERRIDE" data-ms="PINTASAN PENGESAHAN">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                </svg>
                AUTHORIZATION OVERRIDE
            </div>

            <div class="relative z-10 mb-6 text-sm text-gray-400 font-bold leading-relaxed" data-en="Forgot your clearance key? Enter your encrypted comm link (email) below and the mainframe will transmit a secure recovery link." data-ms="Lupa kunci akses anda? Masukkan pautan komunikasi sulit (e-mel) anda di bawah dan sistem akan menghantar pautan pemulihan yang selamat.">
                Forgot your clearance key? Enter your encrypted comm link (email) below and the mainframe will transmit a secure recovery link.
            </div>

            @if (session('status'))
                <div class="relative z-10 mb-6 p-4 bg-emerald-500/20 border border-emerald-500/50 rounded text-emerald-400 text-xs font-bold tracking-widest uppercase text-center shadow-[0_0_10px_rgba(16,185,129,0.3)] animate-pulse">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}" class="relative z-10 w-full flex flex-col gap-5">
                @csrf
                
                <div class="w-full">
                    <label class="block text-xs text-emerald-600 dark:text-emerald-400 mb-1 uppercase tracking-widest font-bold" data-en="Encrypted Comm Link (Email)" data-ms="Pautan Komunikasi Sulit (E-mel)">Encrypted Comm Link (Email)</label>
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus class="w-full bg-black/50 border border-emerald-500/50 rounded px-4 py-2.5 text-sm text-emerald-100 focus:outline-none focus:border-emerald-400 focus:ring-1 focus:ring-emerald-400 transition-all placeholder:text-gray-500">
                    
                    @error('email')
                        <span class="text-red-500 text-xs font-bold tracking-widest drop-shadow-[0_0_5px_rgba(239,68,68,0.8)] mt-2 block">
                            {{ $message }}
                        </span>
                    @enderror
                </div>

                <button type="submit" class="w-full mt-2 bg-emerald-600 hover:bg-emerald-500 text-white font-bold py-3 px-4 rounded shadow-[0_0_20px_rgba(16,185,129,0.4)] hover:shadow-[0_0_30px_rgba(16,185,129,0.7)] transition-all transform hover:-translate-y-1 tracking-widest border border-emerald-400/50 text-sm">
                    <span data-en="TRANSMIT RECOVERY PROTOCOL" data-ms="HANTAR PROTOKOL PEMULIHAN">TRANSMIT RECOVERY PROTOCOL</span>
                </button>
            </form>

            <a href="{{ route('login') }}" class="block relative z-10 mt-6 text-xs text-emerald-600 dark:text-emerald-500 hover:text-emerald-400 transition-colors text-center tracking-wider font-bold flex items-center justify-center gap-2" data-en="Abort / Return to Login" data-ms="Batal / Kembali ke Log Masuk">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                <span>Abort / Return to Login</span>
            </a>
        </div>

        <div class="mt-8 pb-8 text-[10px] text-emerald-700/80 tracking-widest uppercase font-bold text-center">
            Clearance Level: Restricted // S.H.I.E.L.D Intranet<br>
            Powered by LZNK Cakna Siber
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
                    if (el.tagName === 'SPAN' || el.tagName === 'P' || el.tagName === 'DIV' || el.tagName === 'LABEL' || el.tagName === 'A') {
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