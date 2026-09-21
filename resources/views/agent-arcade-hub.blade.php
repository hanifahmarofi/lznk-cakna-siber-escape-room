<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>S.H.I.E.L.D // Mini-Game Arcade</title>
    @vite('resources/css/app.css')
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@700&family=Share+Tech+Mono&display=swap" rel="stylesheet">
    <style>
        /* HIGH CONTRAST THEME SYSTEM */
        :root {
            /* DARK MODE */
            --bg-color: #7c00f8;
            --card-bg: #111111; /* Solid Dark Gray */
            --text-main: #ffffff; /* Pure White */
            --text-muted: #d1d5db; /* Light Gray */
            --title-color: #c084fc; /* Neon Purple */
            --border-color: rgba(168, 85, 247, 0.5);
            
            --btn-bg: #9333ea;
            --btn-hover: #a855f7;
            --btn-text: #ffffff;

            /* Video Integration */
            --video-overlay: rgba(5, 0, 10, 0.85); 
            --video-filter: saturate(120%) brightness(90%);
        }

        .light-mode {
            /* LIGHT MODE */
            --bg-color: #006eff;
            --card-bg: #ffffff; /* Pure White */
            --text-main: #000000; /* Pure Black */
            --text-muted: #374151; /* Dark Gray */
            --title-color: #6b21a8; /* Deep Royal Purple */
            --border-color: rgba(107, 33, 168, 0.4);

            --btn-bg: #7e22ce;
            --btn-hover: #581c87;
            --btn-text: #ffffff;

            /* Video Integration */
            --video-overlay: rgba(239, 239, 239, 0.85); 
            --video-filter: invert(100%) hue-rotate(180deg) saturate(150%) brightness(120%);
        }

        body { 
            font-family: 'Share Tech Mono', monospace; 
            background-color: var(--bg-color); 
            color: var(--text-main); 
            transition: background-color 0.3s ease, color 0.3s ease;
        }
        
        .title-font { font-family: 'Poppins', sans-serif; font-weight: 700; }
        
        /* Dynamic Themed Classes */
        .theme-card { background-color: var(--card-bg); border-color: var(--border-color); transition: all 0.3s ease; }
        .theme-text-main { color: var(--text-main); transition: color 0.3s ease; }
        .theme-text-muted { color: var(--text-muted); transition: color 0.3s ease; font-weight: bold; }
        .theme-title { color: var(--title-color); transition: color 0.3s ease; }
        .theme-border { border-color: var(--border-color); transition: border-color 0.3s ease; }

        .theme-btn { background-color: var(--btn-bg); color: var(--btn-text); border-color: var(--title-color); transition: all 0.3s ease; }
        .theme-btn:hover { background-color: var(--btn-hover); }

        /* Video Background */
        .fixed-video-container { position: fixed; inset: 0; z-index: 0; overflow: hidden; }
        .theme-video { width: 100%; height: 100%; object-fit: cover; filter: var(--video-filter); transition: filter 0.5s ease; }
        .video-overlay { position: absolute; inset: 0; background-color: var(--video-overlay); transition: background-color 0.5s ease; }
        .scanlines { background: linear-gradient(to bottom, rgba(255,255,255,0), rgba(255,255,255,0) 50%, rgba(0,0,0,0.1) 50%, rgba(0,0,0,0.1)); background-size: 100% 4px; }
        
        /* Animations */
        .sc-anim { opacity: 0; animation: slideInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        @keyframes slideInUp { from { transform: translateY(50px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
    </style>
</head>
<body class="min-h-screen relative overflow-x-hidden pb-10">

    <div class="fixed-video-container">
        <video autoplay loop muted playsinline class="theme-video">
            <source src="{{ asset('video/videoplayback.webm') }}" type="video/webm">
        </video>
        <div class="video-overlay pointer-events-none"></div>
    </div>

    <div class="fixed top-4 right-4 z-50 flex gap-3 sc-anim" style="animation-delay: 0.1s;">
        <button id="lang-toggle" class="theme-card border-2 px-4 py-2 text-xs font-bold tracking-wider theme-text-main rounded hover:bg-purple-600 hover:text-white transition">
            🇲🇾 BAHASA
        </button>
        <button id="theme-toggle" class="theme-card border-2 px-4 py-2 text-xs font-bold tracking-wider theme-text-main rounded hover:bg-purple-600 hover:text-white transition">
            ☀️ LIGHT MODE
        </button>
    </div>

    <div class="max-w-6xl mx-auto relative z-10 p-4 md:p-8 mt-12">
        
        <header class="flex flex-col md:flex-row justify-between items-start md:items-end mb-10 pb-4 border-b-4 theme-border sc-anim" style="animation-delay: 0.2s;">
            <div class="flex items-center gap-5">
                <div class="w-16 h-16 rounded-full theme-btn flex items-center justify-center shadow-lg animate-pulse border-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
                <div>
                    <h1 class="title-font text-3xl tracking-wider uppercase mb-1 theme-title" data-en="ARCADE TERMINAL" data-ms="TERMINAL ARKED">ARCADE TERMINAL</h1>
                    <p class="font-bold tracking-widest text-sm uppercase theme-text-muted" data-en="Module 06 Diagnostics" data-ms="Diagnostik Modul 06">Module 06 Diagnostics</p>
                </div>
            </div>
            
            <a href="{{ route('agent.mission') }}" class="mt-6 md:mt-0 px-6 py-3 rounded theme-card border-2 theme-title hover:bg-purple-600 hover:text-white transition-colors text-xs font-bold tracking-widest uppercase flex items-center gap-2 shadow-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                <span data-en="Return to Mission Hub" data-ms="Kembali ke Hab Misi">Return to Mission Hub</span>
            </a>
        </header>

        @if(session('success'))
            <div class="mb-6 bg-emerald-500/20 border-2 border-emerald-500 text-emerald-500 px-4 py-3 rounded font-bold uppercase tracking-widest text-center text-sm shadow-lg">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($miniGames as $index => $game)
                @php
                    // SECURE LOGIC: Check if the user has completed this specific module
                    $completedGamesArray = is_string($progress->completed_minigames) 
                                            ? json_decode($progress->completed_minigames, true) 
                                            : ($progress->completed_minigames ?? []);
                                            
                    $isCompleted = is_array($completedGamesArray) && in_array($game->id, $completedGamesArray);
                @endphp

                <div class="theme-card rounded-lg p-6 border-2 shadow-xl flex flex-col justify-between sc-anim relative overflow-hidden group" style="animation-delay: {{ 0.3 + ($index * 0.1) }}s;">
                    
                    <div class="absolute top-0 right-0 bg-purple-600 border-b border-l border-purple-800 text-white text-[10px] font-bold px-3 py-1 uppercase tracking-widest rounded-bl dynamic-translation" data-original="{{ str_replace('_', ' ', $game->game_type) }}">
                        {{ str_replace('_', ' ', $game->game_type) }}
                    </div>
                    
                    <div class="relative z-10 mb-6 mt-4">
                        <h3 class="title-font text-xl theme-text-main font-bold mb-2 uppercase dynamic-translation" data-original="{{ $game->title }}">{{ $game->title }}</h3>
                        @if($game->instruction)
                            <p class="theme-text-muted text-xs font-mono mb-4 min-h-[40px] dynamic-translation" data-original="{{ $game->instruction }}">{{ $game->instruction }}</p>
                        @endif
                        
                        <div class="inline-block bg-purple-500/20 border-2 theme-border theme-title px-3 py-1 rounded text-xs font-bold tracking-widest uppercase">
                            <span data-en="Reward" data-ms="Ganjaran">Reward</span>: {{ $game->base_score }} PTS
                        </div>

                        @if($isCompleted)
                            <div class="inline-block ml-2 bg-emerald-500/20 border-2 border-emerald-500 text-emerald-500 px-3 py-1 rounded text-xs font-bold tracking-widest uppercase">
                                <span data-en="CLEARED" data-ms="SELESAI">CLEARED</span>
                            </div>
                        @endif
                    </div>

                    <div class="flex flex-col gap-2 relative z-10">
                        <a href="{{ route('agent.arcade.play', $game->id) }}" class="w-full theme-btn font-bold py-3 px-4 rounded text-center uppercase tracking-widest text-xs transition-colors border-2 flex justify-center items-center gap-2 shadow-md">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            <span data-en="{{ $isCompleted ? 'REPLAY MODULE' : 'INITIATE MODULE' }}" data-ms="{{ $isCompleted ? 'MAIN SEMULA MODUL' : 'MULAKAN MODUL' }}">
                                {{ $isCompleted ? 'REPLAY MODULE' : 'INITIATE MODULE' }}
                            </span>
                        </a>

                        @if($isCompleted)
                            <a href="{{ route('agent.arcade.certificate', $game->id) }}" class="w-full bg-emerald-600 hover:bg-emerald-500 text-white font-bold py-3 px-4 rounded text-center uppercase tracking-widest text-xs transition-colors border-2 border-emerald-400 flex justify-center items-center gap-2 shadow-[0_0_15px_rgba(16,185,129,0.5)]">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <span data-en="PRINT CERTIFICATE" data-ms="CETAK SIJIL">PRINT CERTIFICATE</span>
                            </a>
                        @endif
                    </div>
                </div>
            @empty
                <div class="col-span-full theme-card rounded-lg p-10 border-4 border-red-500 text-center sc-anim" style="animation-delay: 0.3s;">
                    <h3 class="title-font text-2xl text-red-600 font-bold mb-2 uppercase" data-en="NO MODULES DETECTED" data-ms="TIADA MODUL DIKESAN">NO MODULES DETECTED</h3>
                    <p class="theme-text-main font-bold font-mono" data-en="The arcade mainframe is currently empty. Await admin deployment." data-ms="Sistem arked utama sedang kosong. Sila tunggu pelancaran admin.">The arcade mainframe is currently empty. Await admin deployment.</p>
                </div>
            @endforelse
        </div>
    </div>

    <audio id="ui-click-sound" src="{{ asset('audio/mixkit-sci-fi-click-900.wav') }}" preload="auto"></audio>

    <script>
        // --- SOUND INTERCEPTOR ---
        document.addEventListener("DOMContentLoaded", function() {
            const clickSound = document.getElementById('ui-click-sound');
            if(clickSound) {
                clickSound.volume = 0.6; 
                
                document.querySelectorAll('button, a').forEach(element => {
                    element.addEventListener('click', function(e) {
                        // 1. Play the sound immediately
                        clickSound.currentTime = 0; 
                        clickSound.play().catch(err => console.log("Click sound blocked:", err));

                        // 2. Intercept anchor tags to allow sound to play before navigating
                        if (this.tagName.toLowerCase() === 'a' && this.hasAttribute('href')) {
                            let target = this.getAttribute('href');
                            
                            // Don't intercept if it's a hash link
                            if (target.startsWith('#') || target.startsWith('javascript:')) return;
                            
                            e.preventDefault();
                            
                            // Safely handle "Open in New Tab" (like the Certificate button)
                            if (this.getAttribute('target') === '_blank') {
                                setTimeout(() => { window.open(target, '_blank'); }, 250);
                            } else {
                                // Standard same-page navigation
                                setTimeout(() => { window.location.href = target; }, 250);
                            }
                        }
                    });
                });
            }
        });

        // --- Global Dictionary Cache for Translations ---
        window.translationCache = {};

        // --- GOOGLE API TWO-WAY AUTO-TRANSLATOR ---
        async function translateGoogleAPI(text, targetLang) {
            if(targetLang === 'en') return text; 
            let url = `https://translate.googleapis.com/translate_a/single?client=gtx&sl=en&tl=${targetLang}&dt=t&q=${encodeURIComponent(text)}`;
            try {
                let response = await fetch(url);
                let data = await response.json();
                
                let fullText = "";
                if (data && data[0]) {
                    for (let i = 0; i < data[0].length; i++) {
                        if (data[0][i][0]) {
                            fullText += data[0][i][0];
                        }
                    }
                }
                return fullText || text; 
            } catch(e) {
                console.error("Translation Failed:", e);
                return text; 
            }
        }

        async function processDynamicTranslations(lang) {
            const dynamicElements = document.querySelectorAll('.dynamic-translation');
            
            for(let el of dynamicElements) {
                let originalText = el.getAttribute('data-original');
                
                // Skip if no original text is set
                if(!originalText || originalText === '--') continue;

                // English Fallbacks
                if (lang === 'en') {
                    if (originalText.toUpperCase().includes('SCRAMBLE')) {
                        el.innerText = 'SCRAMBLE';
                    } else if (originalText.toUpperCase().includes('CONNECT')) {
                        el.innerText = 'CONNECT';
                    } else {
                        el.innerText = originalText;
                    }
                    continue;
                }

                // 🔥 MANUAL HARDCODED OVERRIDES FOR MALAY 🔥
                // We intercept it here so it skips the Google Translate API entirely
                if (lang === 'ms') {
                    if (originalText.toUpperCase().includes('SCRAMBLE')) {
                        el.innerText = 'SCRAMBLE'; // Forces it to stay SCRAMBLE
                        continue; // Skip the API call completely
                    } else if (originalText.toUpperCase().includes('CONNECT')) {
                        el.innerText = 'SAMBUNG GARISAN'; 
                        continue; // Skip the API call completely
                    }
                }

                // If it's not a hardcoded word, proceed with Google Translate
                let cacheKey = `${lang}_${originalText}`;
                
                if(window.translationCache[cacheKey]) {
                    el.innerText = window.translationCache[cacheKey];
                } else {
                    let translation = await translateGoogleAPI(originalText, lang);
                    window.translationCache[cacheKey] = translation; 
                    el.innerText = translation;
                }
            }
        }

        const bodyEl = document.body;
        const themeBtn = document.getElementById('theme-toggle');
        const langBtn = document.getElementById('lang-toggle');
        const translatables = document.querySelectorAll('[data-en]');

        let currentTheme = localStorage.getItem('shield_theme') || 'dark';
        let currentLang = localStorage.getItem('shield_lang') || 'en';

        // Apply Language on Load
       function applyLanguage(lang) {
            if(langBtn) langBtn.innerHTML = lang === 'en' ? '🇲🇾 BAHASA' : '🇬🇧 ENGLISH';
            
            translatables.forEach(el => {
                if(el.getAttribute(`data-${lang}`)) {
                    el.innerHTML = el.getAttribute(`data-${lang}`);
                }
            });

            // We removed the old manual loop from here because 
            // processDynamicTranslations now safely handles the overrides.
            processDynamicTranslations(lang);
        }
        applyLanguage(currentLang);

        // Theme Toggle Click
        themeBtn.addEventListener('click', () => {
            currentTheme = currentTheme === 'dark' ? 'light' : 'dark';
            localStorage.setItem('shield_theme', currentTheme);
            if (currentTheme === 'light') { 
                bodyEl.classList.add('light-mode'); 
                themeBtn.innerHTML = '🌙 DARK MODE'; 
            } else { 
                bodyEl.classList.remove('light-mode'); 
                themeBtn.innerHTML = '☀️ LIGHT MODE'; 
            }
        });

        // Language Toggle Click
        langBtn.addEventListener('click', () => {
            currentLang = currentLang === 'en' ? 'ms' : 'en';
            localStorage.setItem('shield_lang', currentLang);
            applyLanguage(currentLang);
        });
    </script>
    @include('partials.cursor')
</body>
</html>