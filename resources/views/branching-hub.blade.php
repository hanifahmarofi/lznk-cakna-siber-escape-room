<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Branching Game Operations Hub - S.H.I.E.L.D</title>
    @vite('resources/css/app.css')
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@700&family=Share+Tech+Mono&display=swap" rel="stylesheet">
    
    <style>
        body { font-family: 'Share Tech Mono', monospace; }
        .title-font { font-family: 'Poppins', sans-serif; font-weight: 700; text-shadow: 0 0 10px rgba(16,185,129,0.7); }
        .scanlines { background: linear-gradient(to bottom, rgba(255,255,255,0), rgba(255,255,255,0) 50%, rgba(0,0,0,0.1) 50%, rgba(0,0,0,0.1)); background-size: 100% 4px; }

        :root {
            --bg-color: #000000; --text-color: #d1d5db; --vid-opacity: 0.8;
            --vid-overlay: linear-gradient(to bottom, rgba(0,0,0,0.8), rgba(0,0,0,0.3), rgba(0,0,0,0.9));
            --card-bg: rgba(21, 21, 21, 0.85); --card-hover: rgba(31, 31, 31, 0.9);
            --title-color: #10b981; --btn-bg: rgba(255, 255, 255, 0.9);
            --card-title: #ffffff;
            --card-desc: #9ca3af; 
        }

        .light-mode {
            --bg-color: #f3f4f6; --text-color: #0f172a; --vid-opacity: 0.2; 
            --vid-overlay: linear-gradient(to bottom, rgba(255, 255, 255, 0.85), rgba(243, 244, 246, 0.6), rgba(229, 231, 235, 0.95));
            --card-bg: rgba(255, 255, 255, 0.9); --card-hover: rgba(255, 255, 255, 1);
            --title-color: #047857; 
            --card-title: #064e3b; 
            --card-desc: #334155; 
        }

        /* 🔥 Force Black Text on Toggles in Light Mode */
        .light-mode #lang-toggle, .light-mode #theme-toggle {
            color: #000000 !important;
            border-color: #059669 !important;
            background-color: rgba(255, 255, 255, 0.9) !important;
        }

        .light-mode .scanlines { display: none; }

        .theme-card-title { color: var(--card-title); transition: color 0.5s ease; }
        .theme-card-desc { color: var(--card-desc); transition: color 0.5s ease; }

        body { background-color: var(--bg-color); color: var(--text-color); transition: background-color 0.5s ease; }
        .theme-video { opacity: var(--vid-opacity); transition: opacity 0.5s ease; }
        .theme-overlay { background: var(--vid-overlay); transition: background 0.5s ease; }
        .theme-card { background: var(--card-bg) !important; transition: all 0.3s ease; }
        .theme-card:hover { background: var(--card-hover) !important; transform: translateY(-3px); box-shadow: 0 10px 25px -5px rgba(16, 185, 129, 0.3); border-color: #10b981; }

        .sc-anim { opacity: 0; animation-fill-mode: forwards; animation-timing-function: cubic-bezier(0.16, 1, 0.3, 1); animation-duration: 0.6s; }
        .sc-in-up { animation-name: slideInUp; }
        .d-1 { animation-delay: 0.1s; } .d-2 { animation-delay: 0.2s; } .d-3 { animation-delay: 0.3s; }

        @keyframes slideInUp { 0% { transform: translateY(50px); opacity: 0; } 100% { transform: translateY(0); opacity: 1; } }
        
        .btn-blink { animation: slowBlink 2.5s ease-in-out infinite; }
        .btn-blink:hover { animation: none; opacity: 1; filter: brightness(1); }
        @keyframes slowBlink { 0%, 100% { opacity: 1; filter: brightness(1); } 50% { opacity: 0.5; filter: brightness(0.8); } }
    </style>
</head>

<body class="min-h-screen relative overflow-x-hidden transition-colors duration-500 pb-10">

    <div class="fixed inset-0 z-0 overflow-hidden">
        <video autoplay loop muted playsinline class="theme-video w-full h-full object-cover">
            <source src="{{ asset('video/videoplayback.webm') }}" type="video/webm">
        </video>
        <div class="absolute inset-0 bg-emerald-600 mix-blend-color opacity-50 pointer-events-none"></div>
        <div class="theme-overlay absolute inset-0 pointer-events-none transition-background duration-500"></div>
    </div>

    <div class="fixed top-4 right-4 z-[9999] flex gap-3">
        <button id="lang-toggle" class="theme-card border border-emerald-500/50 px-3 py-1.5 rounded-full text-xs font-bold tracking-wider hover:bg-emerald-500 hover:text-white transition-colors text-emerald-500 shadow-lg">
            🇬🇧 ENGLISH
        </button>
        <button id="theme-toggle" class="theme-card border border-emerald-500/50 px-3 py-1.5 rounded-full text-xs font-bold tracking-wider hover:bg-emerald-500 hover:text-white transition-colors text-emerald-500 shadow-lg">
            ☀️ LIGHT MODE
        </button>
    </div>

    <div class="max-w-7xl mx-auto relative z-10 p-4 md:p-8 mt-12 md:mt-4">
        
        <header class="flex flex-col md:flex-row justify-between items-start md:items-end mb-10 pb-4 border-b border-emerald-500/30 sc-anim sc-in-up d-1">
            <div class="flex items-center gap-5">
                <div class="w-16 h-16 rounded-full bg-gradient-to-br from-emerald-500 to-yellow-500 flex items-center justify-center shadow-[0_0_20px_rgba(16,185,129,0.6)]">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-[#0f2818]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                    </svg>
                </div>
                <div>
                    <h1 class="title-font text-3xl tracking-wider uppercase mb-1 text-emerald-500" data-en="Special Operations Hub" data-ms="Hab Operasi Khas">Hab Operasi Khas</h1>
                    <p class="font-bold tracking-widest text-sm uppercase text-gray-400" data-en="Classified Branching Scenarios" data-ms="Senario Bercabang Sulit">Senario Bercabang Sulit</p>
                </div>
            </div>
            
            <a href="{{ route('dashboard') }}" class="mt-4 md:mt-0 px-5 py-2 rounded-full theme-card border border-emerald-500/50 text-emerald-500 hover:bg-emerald-500 hover:text-emerald transition-colors text-xs font-bold tracking-widest uppercase flex items-center gap-2 cursor-pointer">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                <span data-en="Return to Mission Control" data-ms="Kembali ke Kawalan Misi">Kembali ke Kawalan Misi</span>
            </a>
        </header>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($customRooms as $index => $room)
                @php
                    $hasPlayed = isset($customScores[$room->id]);
                    $roomScore = $hasPlayed ? $customScores[$room->id] : 0;
                    
                    // 🚨 DYNAMIC LOGIC: Grab the pass mark set by the Admin
                    $passingMark = $room->pass_mark ?? 100; 
                    // Safely check if they have BOTH played it AND reached the passing score
                    $hasPassed = $hasPlayed && ($roomScore >= $passingMark);
                @endphp
                
                <div class="theme-card backdrop-blur-md rounded-lg p-6 border border-gray-600/30 relative overflow-hidden sc-anim sc-in-up" style="animation-delay: {{ 0.2 + ($index * 0.1) }}s">
                    <div class="absolute inset-0 scanlines pointer-events-none opacity-50 z-0"></div>
                    
                    <div class="relative z-10 flex flex-col h-full">
                        <div class="flex justify-between items-start mb-4">
                            <div class="w-12 h-12 rounded bg-emerald-500/20 text-emerald-500 border border-emerald-500/50 flex items-center justify-center shrink-0">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                            </div>
                            
                            <div class="text-right">
                                <!-- 🚨 BADGE FIX: Distinguish between Cleared, Failed, and Active -->
                                @if($hasPassed)
                                    <span class="px-2.5 py-1 text-[10px] font-bold rounded bg-yellow-500/20 text-yellow-500 border border-yellow-500/50 uppercase tracking-widest" data-en="CLEARED" data-ms="SELESAI">Selesai</span>
                                    <div class="mt-2 text-3xl font-black text-yellow-500 drop-shadow-[0_0_8px_rgba(234,179,8,0.5)]">{{ $roomScore }} PTS</div>
                                @elseif($hasPlayed && !$hasPassed)
                                    <span class="px-2.5 py-1 text-[10px] font-bold rounded bg-red-500/20 text-red-500 border border-red-500/50 uppercase tracking-widest" data-en="FAILED" data-ms="GAGAL">Gagal</span>
                                    <div class="mt-2 text-3xl font-black text-red-500 drop-shadow-[0_0_8px_rgba(239,68,68,0.5)]">{{ $roomScore }} PTS</div>
                                @else
                                    <span class="px-2.5 py-1 text-[10px] font-bold rounded bg-emerald-500/20 text-emerald-400 border border-emerald-500/50 uppercase tracking-widest" data-en="ACTIVE" data-ms="AKTIF">Aktif</span>
                                @endif
                            </div>
                        </div>
                        
                        <h3 class="title-font text-xl mb-2 theme-card-title">{{ $room->title }}</h3>
                        
                        <p class="text-sm font-bold theme-card-desc font-mono mb-6 flex-grow">
                            {{ Str::limit($room->description, 100) }}
                        </p>
                        
                        <div class="border-t border-emerald-500/30 pt-4 mt-auto space-y-3">
                            <!-- 🚨 BUTTON FIX: Only allow Certificate if they actually passed -->
                            @if($hasPassed)
                                <a href="{{ route('agent.certificate', ['custom_room' => $room->id]) }}" class="w-full bg-yellow-500/20 hover:bg-yellow-500 text-yellow-500 hover:text-black border border-yellow-500 font-black py-3 px-6 rounded transition-all uppercase tracking-widest text-sm shadow-[0_0_10px_rgba(234,179,8,0.3)] hover:shadow-[0_0_20px_rgba(234,179,8,0.6)] flex items-center justify-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                    <span data-en="VIEW CERTIFICATE" data-ms="LIHAT SIJIL">Lihat Sijil</span>
                                </a>
                                <a href="{{ url('/play/room/' . $room->id) }}" class="w-full bg-transparent hover:bg-emerald-500/10 text-emerald-500 border border-emerald-500/50 font-bold py-2.5 px-6 rounded transition-all uppercase tracking-widest text-xs flex items-center justify-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" /></svg>
                                    <span data-en="REPLAY" data-ms="MAIN SEMULA">Main Semula</span>
                                </a>
                            @elseif($hasPlayed && !$hasPassed)
                                <div class="text-center text-xs text-red-500/80 mb-2 font-bold tracking-widest" data-en="MINIMUM MARKS NOT ACHIEVED" data-ms="MARKAH MINIMUM TIDAK DICAPAI">Markah Minimum Tidak Dicapai</div>
                                <a href="{{ url('/play/room/' . $room->id) }}" class="w-full bg-transparent hover:bg-red-500/10 text-red-500 border border-red-500/50 font-bold py-2.5 px-6 rounded transition-all uppercase tracking-widest text-xs flex items-center justify-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" /></svg>
                                    <span data-en="RETRY MODULE" data-ms="CUBA SEMULA MODUL">Cuba Semula Modul</span>
                                </a>
                            @else
                                <a href="{{ url('/play/room/' . $room->id) }}" class="btn-blink w-full bg-emerald-500/20 hover:bg-emerald-500 text-emerald-500 hover:text-white border border-emerald-500 font-black py-3 px-6 rounded transition-all uppercase tracking-widest text-sm shadow-[0_0_10px_rgba(16,185,129,0.3)] hover:shadow-[0_0_20px_rgba(16,185,129,0.6)] flex items-center justify-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                    <span data-en="START MODULE" data-ms="MULAKAN MODUL">Mulakan Modul</span>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full theme-card backdrop-blur-md rounded-lg p-10 border border-gray-600/30 text-center sc-anim sc-in-up d-2">
                    <svg class="w-16 h-16 text-gray-500 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                    <h3 class="text-xl font-bold theme-card-title mb-2" data-en="No Active Operations" data-ms="Tiada Operasi Aktif">Tiada Operasi Aktif</h3>
                    <p class="text-sm theme-card-desc font-mono" data-en="Command has not deployed any special branching modules yet." data-ms="Pihak atasan belum mengerahkan sebarang modul bercabang khas lagi.">Pihak atasan belum mengerahkan sebarang modul bercabang khas lagi.</p>
                </div>
            @endforelse
        </div>
    </div>

    <audio id="ui-click-sound" src="{{ asset('audio/mixkit-sci-fi-click-900.wav') }}" preload="auto"></audio>

    <script>
        const bodyEl = document.body;
        const themeBtn = document.getElementById('theme-toggle');
        const langBtn = document.getElementById('lang-toggle');
        const translatables = document.querySelectorAll('[data-en]');

        window.addEventListener('DOMContentLoaded', () => { setTimeout(() => { document.body.classList.add('loaded'); }, 50); });

        let currentTheme = localStorage.getItem('shield_theme') || 'dark';
        let currentLang = localStorage.getItem('shield_lang') || 'ms'; // Default to Malay

        if (currentTheme === 'light') { 
            bodyEl.classList.add('light-mode'); 
            if(themeBtn) themeBtn.innerHTML = '🌙 DARK MODE'; 
        }

        function applyLanguage(lang) {
            if(langBtn) langBtn.innerHTML = lang === 'en' ? '🇲🇾 BAHASA' : '🇬🇧 ENGLISH';
            
            translatables.forEach(el => {
                if (el.tagName === 'SPAN' || el.tagName === 'P' || el.tagName === 'H1' || el.tagName === 'H2' || el.tagName === 'H3' || el.tagName === 'DIV' || el.tagName === 'LI') {
                    if(el.getAttribute(`data-${lang}`)) {
                        const textSpan = el.querySelector('span');
                        if(textSpan && !el.hasAttribute('data-en')) {
                            textSpan.innerText = el.getAttribute(`data-${lang}`);
                        } else {
                            // Only update text content if it doesn't mess with child SVGs
                            if(!el.innerHTML.includes('<svg')) {
                                el.innerHTML = el.getAttribute(`data-${lang}`);
                            } else {
                                // Find the text node specifically if there's an SVG inside
                                Array.from(el.childNodes).forEach(node => {
                                    if(node.nodeType === Node.TEXT_NODE && node.textContent.trim().length > 0) {
                                        node.textContent = " " + el.getAttribute(`data-${lang}`);
                                    }
                                });
                            }
                        }
                    }
                }
            });
        }
        
        applyLanguage(currentLang);

        // Theme Toggle Logic
        if(themeBtn) {
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
        }

        // Custom Translate Engine Toggle Logic
        if(langBtn) {
            langBtn.addEventListener('click', () => {
                currentLang = currentLang === 'en' ? 'ms' : 'en';
                localStorage.setItem('shield_lang', currentLang);
                applyLanguage(currentLang);
            });
        }

        // 🔥 GLOBAL AUDIO CLICK LISTENER 🔥
        const clickSound = document.getElementById('ui-click-sound');
        if(clickSound) {
            clickSound.volume = 0.6; 
            
            document.querySelectorAll('button, a').forEach(element => {
                element.addEventListener('click', function(e) {
                    clickSound.currentTime = 0; 
                    clickSound.play().catch(err => console.log("Click sound blocked by browser:", err));

                    if (this.tagName === 'A' && this.href && !this.getAttribute('target') && !this.href.includes('javascript:')) {
                        e.preventDefault();
                        let targetUrl = this.href;
                        setTimeout(() => { window.location.href = targetUrl; }, 200);
                    }
                });
            });
        }
    </script>
    @include('partials.cursor')
</body>
</html>