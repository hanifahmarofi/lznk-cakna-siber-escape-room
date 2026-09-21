<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Active Operation - {{ $room->title }}</title>
    @vite('resources/css/app.css')
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@700&family=Share+Tech+Mono&display=swap" rel="stylesheet">
    
    <style>
        body { font-family: 'Share Tech Mono', monospace; background-color: #050a08; color: #d1d5db; overflow-x: hidden; transition: background-color 0.5s ease, color 0.5s ease; }
        .title-font { font-family: 'Poppins', sans-serif; font-weight: 700; }
        .scanlines { background: linear-gradient(to bottom, rgba(255,255,255,0), rgba(255,255,255,0) 50%, rgba(0,0,0,0.1) 50%, rgba(0,0,0,0.1)); background-size: 100% 4px; pointer-events: none; }
        
        .glass-panel { background: rgba(10, 25, 15, 0.85); backdrop-filter: blur(12px); border: 1px solid rgba(16, 185, 129, 0.3); box-shadow: 0 8px 32px rgba(0, 0, 0, 0.5), inset 0 1px 0 rgba(16, 185, 129, 0.2); transition: all 0.5s ease; }
        .glass-card { background: rgba(15, 30, 20, 0.7); backdrop-filter: blur(8px); border: 1px solid rgba(16, 185, 129, 0.2); border-radius: 12px; transition: all 0.5s ease; }
        
        .text-emerald-glow { color: #34d399; text-shadow: 0 0 10px rgba(52, 211, 153, 0.6); }
        .border-emerald-glow { border-color: #10b981; box-shadow: 0 0 15px rgba(16, 185, 129, 0.3); }

        .btn-option { transition: all 0.3s ease; border: 1px solid rgba(16, 185, 129, 0.4); background: rgba(16, 185, 129, 0.05); }
        .btn-option:hover { background: rgba(16, 185, 129, 0.2); border-color: #34d399; box-shadow: 0 0 15px rgba(52, 211, 153, 0.4); transform: translateX(5px); cursor: pointer; }

        .video-container { position: relative; padding-bottom: 56.25%; height: 0; overflow: hidden; border-radius: 8px; border: 2px solid rgba(16, 185, 129, 0.4); }
        .video-container iframe, .video-container video { position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover; }

        /* ☀️ LIGHT MODE BLEND STYLES */
        body.light-mode { background-color: #f8fafc; color: #1e293b; }
        body.light-mode .scanlines { opacity: 0.1; }
        body.light-mode .glass-panel { background: rgba(255, 255, 255, 0.95); border-color: rgba(16, 185, 129, 0.5); box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05); }
        body.light-mode .glass-card { background: rgba(255, 255, 255, 0.9); border-color: rgba(16, 185, 129, 0.4); color: #0f172a; box-shadow: 0 8px 20px rgba(0, 0, 0, 0.03); }
        body.light-mode .text-emerald-glow { color: #047857; text-shadow: none; }
        body.light-mode .text-gray-200, body.light-mode .text-gray-300 { color: #1e293b; font-weight: 600; }
        body.light-mode .text-gray-400 { color: #475569; font-weight: bold; }
        body.light-mode .btn-option { background: rgba(16, 185, 129, 0.08); border-color: rgba(16, 185, 129, 0.5); }
        body.light-mode .btn-option:hover { background: rgba(16, 185, 129, 0.2); border-color: #059669; }
        body.light-mode .bg-black\/50 { background: rgba(241, 245, 249, 1); border-color: rgba(16, 185, 129, 0.3); }
        body.light-mode .bg-\[radial-gradient\(ellipse_at_center\,_var\(--tw-gradient-stops\)\)\] { background: radial-gradient(ellipse at center, rgba(16,185,129,0.1) 0%, transparent 80%); }

        /* 🤖 GOOGLE TRANSLATE OVERRIDES */
        .goog-te-banner-frame.skiptranslate { display: none !important; }
        body { top: 0px !important; position: relative; }
        #google_translate_element { display: none !important; }
        .goog-tooltip { display: none !important; }
        .goog-tooltip:hover { display: none !important; }
        .goog-text-highlight { background-color: transparent !important; border: none !important; box-shadow: none !important; }
        
        .custom-scrollbar::-webkit-scrollbar { width: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: rgba(0,0,0,0.1); border-radius: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(16,185,129,0.5); border-radius: 4px; }

        /* 🔥 NEW: PERFECTED COUNTDOWN ANIMATION 🔥 */
        .count-pulse {
            animation: countPulse 1s ease-in-out forwards;
        }
        @keyframes countPulse {
            0% { transform: scale(0.3); opacity: 0; }
            20% { transform: scale(1.2); opacity: 1; }
            80% { transform: scale(1); opacity: 1; }
            100% { transform: scale(1.5); opacity: 0; }
        }
    </style>
</head>

<body class="min-h-screen relative flex flex-col pt-20 pb-10">
    <div id="google_translate_element"></div>

    <audio id="sfx-countdown" src="{{ asset('audio/countdown-epic.mp3') }}" preload="auto"></audio>
    <audio id="sfx-right" src="{{ asset('audio/right-answer.mp3') }}" preload="auto"></audio>
    <audio id="sfx-wrong" src="{{ asset('audio/wrong-answer.mp3') }}" preload="auto"></audio>
    <audio id="ui-click-sound" src="{{ asset('audio/mixkit-sci-fi-click-900.wav') }}" preload="auto"></audio>

    @if($isFirst)
    <div id="intro-modal" class="fixed inset-0 z-[200] flex items-center justify-center p-4 bg-black/90 backdrop-blur-md transition-all duration-500">
        <div class="bg-[#f8fafc] max-w-3xl w-full p-8 md:p-12 text-center border-t-8 border-t-emerald-500 shadow-2xl relative overflow-hidden rounded-xl">
            <h2 class="title-font text-3xl md:text-5xl font-extrabold text-emerald-700 tracking-widest uppercase mb-6 translation-target">Taklimat Misi</h2>
            
            <div class="bg-white rounded-lg p-6 mb-10 shadow border border-gray-200">
                <p class="text-gray-800 text-sm md:text-lg leading-relaxed text-justify font-mono dynamic-translation custom-scrollbar max-h-[45vh] overflow-y-auto pr-4" data-original="{{ $room->description }}">
                    {{ $room->description }}
                </p>
            </div>

            <button id="btn-start-countdown" class="w-full bg-emerald-600 hover:bg-emerald-500 text-white font-extrabold py-4 px-8 rounded uppercase tracking-widest text-lg shadow-lg transition-all transform hover:-translate-y-1 flex items-center justify-center gap-3">
                <span class="translation-target">Mulakan Misi</span>
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
            </button>
        </div>
    </div>

    <div id="countdown-overlay" class="fixed inset-0 z-[250] hidden items-center justify-center bg-black/95 backdrop-blur-sm">
        <div id="countdown-text" class="text-9xl md:text-[15rem] font-black text-emerald-500 tracking-tighter drop-shadow-[0_0_50px_rgba(16,185,129,0.8)] opacity-0"></div>
    </div>

    <div id="flash-overlay" class="fixed inset-0 z-[300] bg-white hidden opacity-0 transition-opacity duration-200 pointer-events-none"></div>
    @endif

    <div class="fixed inset-0 z-0 overflow-hidden pointer-events-none">
        <div class="absolute inset-0 scanlines opacity-50 z-10"></div>
        <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_center,_var(--tw-gradient-stops))] from-emerald-900/20 via-black to-black z-0"></div>
    </div>

    @if(session('mission_feedback'))
    @php
        $isWrong = session('feedback_is_wrong', false);
        
        $borderColor = $isWrong ? 'border-t-red-500' : 'border-t-emerald-500';
        $shadowBox = $isWrong ? 'shadow-[0_0_40px_rgba(239,68,68,0.3)]' : 'shadow-[0_0_40px_rgba(16,185,129,0.3)]';
        $iconBg = $isWrong ? 'bg-red-500/20' : 'bg-emerald-500/20';
        $iconText = $isWrong ? 'text-red-400' : 'text-emerald-400';
        $iconBorder = $isWrong ? 'border-red-500/50' : 'border-emerald-500/50';
        $titleColor = $isWrong ? 'text-red-500' : 'text-emerald-500';
        $titleGlow = $isWrong ? '[text-shadow:0_0_10px_rgba(239,68,68,0.6)]' : 'text-emerald-glow';
        $btnBg = $isWrong ? 'bg-red-600 hover:bg-red-500' : 'bg-emerald-600 hover:bg-emerald-500';
        $btnShadow = $isWrong ? 'shadow-[0_0_15px_rgba(239,68,68,0.5)]' : 'shadow-[0_0_15px_rgba(16,185,129,0.5)]';
        
        $iconPath = $isWrong 
            ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path>' 
            : '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>';
    @endphp
    
    <div id="feedback-modal" class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/80 backdrop-blur-md transition-all duration-500">
        <div class="glass-card max-w-lg w-full p-8 text-center border-t-4 {{ $borderColor }} {{ $shadowBox }}">
            <div class="w-16 h-16 mx-auto {{ $iconBg }} {{ $iconText }} rounded-full flex items-center justify-center mb-6 border {{ $iconBorder }}">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">{!! $iconPath !!}</svg>
            </div>
            <h2 class="text-2xl font-bold {{ $titleColor }} {{ $titleGlow }} tracking-widest uppercase mb-6 translation-target">ANALISIS SISTEM</h2>
            <p class="text-gray-200 text-lg mb-8 leading-relaxed dynamic-translation" data-original="{{ session('mission_feedback') }}">
                {{ session('mission_feedback') }}
            </p>
            <button id="btn-dismiss-feedback" class="{{ $btnBg }} text-white font-bold py-3 px-8 rounded uppercase tracking-widest text-sm {{ $btnShadow }} transition-all w-full">
                <span class="translation-target">Teruskan</span>
            </button>
        </div>
    </div>
    @endif

    <nav class="glass-panel fixed top-0 w-full z-50 border-b border-emerald-500/30">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-emerald-500/20 flex items-center justify-center border border-emerald-500/50 animate-pulse">
                        <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div>
                        <h1 class="title-font text-lg text-emerald-glow tracking-widest uppercase dynamic-translation" data-original="{{ $room->title }}">{{ $room->title }}</h1>
                        <p class="text-[10px] text-emerald-500/70 tracking-widest font-mono uppercase translation-target">Operasi Sedang Berjalan</p>
                    </div>
                </div>
                
                <div class="flex items-center gap-2 md:gap-3">
                    <button id="lang-toggle" class="px-3 py-1.5 rounded-full text-[10px] font-bold tracking-wider hover:bg-emerald-500 hover:text-white transition-colors border border-emerald-500/50 text-emerald-500 hidden sm:block shadow-sm">
                        🇲🇾 BM
                    </button>
                    <button id="theme-toggle" class="px-3 py-1.5 rounded-full text-[10px] font-bold tracking-wider hover:bg-emerald-500 hover:text-white transition-colors border border-emerald-500/50 text-emerald-500 hidden sm:block shadow-sm">
                        ☀️ LIGHT
                    </button>
                    <a href="{{ route('agent.branching-hub') }}" class="text-xs font-bold text-gray-400 hover:text-red-400 border border-gray-600 hover:border-red-500 px-4 py-1.5 rounded transition-colors uppercase tracking-widest flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        <span class="hidden sm:inline translation-target">Tarik Diri</span>
                    </a>
                </div>
            </div>
        </div>
    </nav>

    @php
        $activeVideoPath = null;
        $activeVideoUrl = null;
        $activeDuration = 0;
        $activeDesc = '';
        $activeTitle = '';
        $requireVideo = false;

        if ($isFirst && ($room->video_url || $room->video_path)) {
            $activeVideoPath = $room->video_path;
            $activeVideoUrl = $room->video_url;
            $activeDuration = $room->video_duration ?? 0;
            $activeDesc = $room->video_description ?? 'Sila teliti rakaman taklimat ini sebelum memulakan operasi.';
            $activeTitle = 'Taklimat Misi (Wajib Tonton)';
            $requireVideo = true;
        } 
        elseif ($question->video_url || $question->video_path) {
            $activeVideoPath = $question->video_path;
            $activeVideoUrl = $question->video_url;
            $activeDuration = $question->video_duration ?? 0;
            $activeDesc = 'Sila tonton lampiran visual ini untuk memahami situasi sebelum membuat keputusan.';
            $activeTitle = 'Lampiran Visual Misi';
            $requireVideo = true;
        }
    @endphp

    <main id="game-main-content" class="max-w-4xl mx-auto w-full px-4 relative z-10 flex-grow flex flex-col {{ $isFirst ? 'hidden' : '' }}">
        
        @if($requireVideo)
        <div id="video-section" class="glass-card p-6 md:p-8 shadow-[0_0_30px_rgba(16,185,129,0.15)] mb-8 transition-opacity duration-500">
            <div class="flex items-center gap-3 mb-6 border-b border-emerald-500/30 pb-4">
                <svg class="w-6 h-6 text-emerald-400 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                <h2 class="text-xl font-bold text-emerald-glow tracking-widest uppercase translation-target">{{ $activeTitle }}</h2>
            </div>
            
            <div class="video-container mb-6 relative group">
                @if($activeVideoPath)
                    <video id="mission-video" controls class="w-full rounded">
                        <source src="{{ asset('storage/' . $activeVideoPath) }}" type="video/mp4">
                    </video>
                @elseif($activeVideoUrl)
                    @php
                        $embedUrl = $activeVideoUrl;
                        if(str_contains($embedUrl, 'watch?v=')) $embedUrl = str_replace('watch?v=', 'embed/', $embedUrl);
                        elseif(str_contains($embedUrl, 'youtu.be/')) $embedUrl = str_replace('youtu.be/', 'youtube.com/embed/', $embedUrl);
                        $embedUrl .= str_contains($embedUrl, '?') ? '&controls=1&rel=0&enablejsapi=1' : '?controls=1&rel=0&enablejsapi=1';
                    @endphp
                    <iframe id="mission-video-frame" src="{{ $embedUrl }}" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
                @endif
            </div>

            <div class="bg-black/50 p-4 rounded border border-emerald-900 mb-6 font-mono text-sm text-gray-300 dynamic-translation" data-original="{{ $activeDesc }}">
                {{ $activeDesc }}
            </div>

            <div class="flex flex-col items-center justify-center p-4 border-t border-emerald-500/20">
                <div id="countdown-container" class="text-center">
                    <p class="text-xs text-gray-400 uppercase tracking-widest mb-2 translation-target">Akses misi dibuka dalam</p>
                    <p id="video-timer" class="text-3xl font-bold text-emerald-500 font-mono tracking-widest">{{ $activeDuration }}s</p>
                </div>
                
                <button id="btn-proceed" class="hidden bg-emerald-600 hover:bg-emerald-500 text-white font-bold py-3 px-10 rounded uppercase tracking-widest text-sm shadow-[0_0_15px_rgba(16,185,129,0.5)] transition-all flex items-center gap-2">
                    <span class="translation-target">Teruskan Misi</span>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                </button>
            </div>
        </div>
        @endif

        <div id="scenario-section" class="{{ $requireVideo ? 'hidden' : '' }} w-full max-w-4xl mx-auto flex-grow flex flex-col justify-center">
            
            <div class="glass-card p-6 md:p-10 shadow-[0_0_30px_rgba(16,185,129,0.15)] mb-6 border-t-4 border-t-emerald-500 relative overflow-hidden">
                <div class="absolute top-0 right-0 bg-emerald-500 text-black text-[10px] font-bold px-3 py-1 uppercase tracking-widest rounded-bl-lg z-10">
                    ID: {{ $question->id }}
                </div>
                
                <div class="flex items-start gap-4 relative z-10">
                    <div class="shrink-0 mt-1 hidden sm:block">
                        <div class="w-2 h-2 bg-emerald-500 rounded-full animate-ping"></div>
                    </div>
                    <div class="font-mono text-base md:text-lg leading-relaxed text-gray-200 dynamic-translation" data-original="{{ $question->text }}">
                        {{ $question->text }}
                    </div>
                </div>
            </div>

            <div class="flex flex-col gap-3">
                <p class="text-xs text-emerald-500 font-bold uppercase tracking-widest pl-2 mb-2 translation-target">Tentukan Tindakan Anda:</p>
                
                @foreach($question->options as $option)
                    <form action="{{ route('agent.play.room.submit', $room->id) }}" method="POST" class="w-full m-0 option-form">
                        @csrf
                        <input type="hidden" name="option_id" value="{{ $option->id }}">
                        
                        <button type="submit" class="btn-option w-full text-left p-5 rounded-lg flex items-center justify-between group">
                            <div class="flex items-center gap-4 pr-4">
                                <span class="text-emerald-500 font-mono font-bold text-sm transition-colors">>></span>
                                <span class="text-gray-300 font-mono text-sm md:text-base transition-colors dynamic-translation" data-original="{{ $option->text }}">
                                    {{ $option->text }}
                                </span>
                            </div>
                            <svg class="w-5 h-5 text-emerald-500/0 group-hover:text-emerald-500 transition-all transform -translate-x-4 group-hover:translate-x-0 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </button>
                    </form>
                @endforeach
            </div>
            
        </div>
    </main>

    <script type="text/javascript">
        function googleTranslateElementInit() {
            new google.translate.TranslateElement({ pageLanguage: 'ms', includedLanguages: 'ms,en', autoDisplay: false }, 'google_translate_element');
        }
    </script>
    <script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>

    <script>
        const enDictionary = {
            "Operasi Sedang Berjalan": "Operation In Progress",
            "Tarik Diri": "Abort",
            "Taklimat Misi (Wajib Tonton)": "Mission Briefing (Mandatory)",
            "Lampiran Visual Misi": "Mission Visual Attachment",
            "Akses misi dibuka dalam": "Mission access opens in",
            "Teruskan Misi": "Proceed Mission",
            "Tentukan Tindakan Anda:": "Determine Your Action:",
            "ANALISIS SISTEM": "SYSTEM ANALYSIS",
            "Teruskan": "Continue",
            "Taklimat Misi": "Mission Briefing",
            "Mulakan Misi": "Start Module",
            "Operasi Klasifikasi Tertinggi": "Highly Classified Operation"
        };

        const msDictionary = {
            "Operation In Progress": "Operasi Sedang Berjalan",
            "Abort": "Tarik Diri",
            "Mission Briefing (Mandatory)": "Taklimat Misi (Wajib Tonton)",
            "Mission Visual Attachment": "Lampiran Visual Misi",
            "Mission access opens in": "Akses misi dibuka dalam",
            "Proceed Mission": "Teruskan Misi",
            "Determine Your Action:": "Tentukan Tindakan Anda:",
            "SYSTEM ANALYSIS": "ANALISIS SISTEM",
            "Continue": "Teruskan",
            "Mission Briefing": "Taklimat Misi",
            "Start Module": "Mulakan Misi",
            "Highly Classified Operation": "Operasi Klasifikasi Tertinggi"
        };

        window.translationCache = {};

        async function translateGoogleAPI(text, targetLang) {
            let url = `https://translate.googleapis.com/translate_a/single?client=gtx&sl=auto&tl=${targetLang}&dt=t&q=${encodeURIComponent(text)}`;
            try {
                let response = await fetch(url);
                let data = await response.json();
                let fullText = "";
                if (data && data[0]) {
                    for (let i = 0; i < data[0].length; i++) {
                        if (data[0][i][0]) fullText += data[0][i][0];
                    }
                }
                return fullText || text; 
            } catch(e) { return text; }
        }

        async function processDynamicTranslations(lang) {
            const dynamicElements = document.querySelectorAll('.dynamic-translation');
            for(let el of dynamicElements) {
                let originalText = el.getAttribute('data-original');
                if(!originalText || originalText === '--') continue;

                if (lang === 'ms') {
                    el.innerText = originalText;
                    continue;
                }

                let cacheKey = `${lang}_${originalText}`;
                if(window.translationCache[cacheKey]) {
                    el.innerText = window.translationCache[cacheKey];
                } else {
                    el.innerText = "Translating..."; 
                    let translation = await translateGoogleAPI(originalText, lang);
                    window.translationCache[cacheKey] = translation; 
                    el.innerText = translation;
                }
            }
        }

        let currentLang = localStorage.getItem('shield_lang') || 'ms';
        const langBtn = document.getElementById('lang-toggle');

        function applyLanguage(lang) {
            if(langBtn) langBtn.innerHTML = lang === 'en' ? '🇲🇾 BM' : '🇬🇧 ENG';
            const dict = lang === 'en' ? enDictionary : msDictionary;
            
            document.querySelectorAll('.translation-target').forEach(el => {
                const text = el.textContent.trim();
                if (dict[text]) { el.textContent = dict[text]; }
            });
            
            processDynamicTranslations(lang);
        }
        
        applyLanguage(currentLang);

        if (langBtn) {
            langBtn.addEventListener('click', () => {
                currentLang = currentLang === 'en' ? 'ms' : 'en';
                localStorage.setItem('shield_lang', currentLang);
                applyLanguage(currentLang); 
            });
        }

        let currentTheme = localStorage.getItem('shield_theme') || 'dark';
        const themeBtn = document.getElementById('theme-toggle');
        
        if (currentTheme === 'light') { document.body.classList.add('light-mode'); if(themeBtn) themeBtn.innerHTML = '🌙 DARK'; }

        if(themeBtn) {
            themeBtn.addEventListener('click', () => {
                currentTheme = currentTheme === 'dark' ? 'light' : 'dark';
                localStorage.setItem('shield_theme', currentTheme);
                if(currentTheme === 'light') { document.body.classList.add('light-mode'); themeBtn.innerHTML = '🌙 DARK'; } 
                else { document.body.classList.remove('light-mode'); themeBtn.innerHTML = '☀️ LIGHT'; }
            });
        }

        // --- GAME FLOW (MODALS, ANIMATION & AUDIO) ---
        document.addEventListener('DOMContentLoaded', () => {
            
            // 1. Audio Elements
            const sfxCountdown = document.getElementById('sfx-countdown');
            const sfxRight = document.getElementById('sfx-right');
            const sfxWrong = document.getElementById('sfx-wrong');
            const clickSound = document.getElementById('ui-click-sound');

            // Play correct/wrong sound on Feedback Modal Load based on Controller logic
            @if(session('mission_feedback'))
                @if(session('feedback_is_wrong'))
                    if(sfxWrong) { sfxWrong.volume = 0.8; sfxWrong.play().catch(()=>{}); }
                @else
                    if(sfxRight) { sfxRight.volume = 0.8; sfxRight.play().catch(()=>{}); }
                @endif
            @endif

            // Play click sound when option is selected before form submits
            document.querySelectorAll('.option-form').forEach(form => {
                form.addEventListener('submit', (e) => {
                    if(clickSound) { clickSound.currentTime = 0; clickSound.play().catch(()=>{}); }
                    e.preventDefault();
                    // Small delay to let the click sound play
                    setTimeout(() => { form.submit(); }, 200);
                });
            });

            // 2. DOM Elements
            const feedbackModal = document.getElementById('feedback-modal');
            const btnDismissFeedback = document.getElementById('btn-dismiss-feedback');
            const introModal = document.getElementById('intro-modal');
            const btnStartIntro = document.getElementById('btn-start-countdown');
            const countdownOverlay = document.getElementById('countdown-overlay');
            const countdownText = document.getElementById('countdown-text');
            const flashOverlay = document.getElementById('flash-overlay');
            const mainContent = document.getElementById('game-main-content');
            
            const isFirstQuestion = {{ $isFirst ? 'true' : 'false' }};

            // 3. Logic Flow Tree
            if (feedbackModal && btnDismissFeedback) {
                // Scenario A: Just answered a question. Show feedback, then load next step.
                mainContent.classList.remove('hidden');
                btnDismissFeedback.addEventListener('click', () => {
                    if(clickSound) { clickSound.currentTime = 0; clickSound.play().catch(()=>{}); }
                    feedbackModal.classList.add('opacity-0', 'scale-95');
                    setTimeout(() => {
                        feedbackModal.remove();
                        startVideoTimerFlow();
                    }, 300);
                });
            } else if (isFirstQuestion && introModal) {
                // Scenario B: Brand new room. Show Intro Modal -> Countdown -> Flash -> Start
                btnStartIntro.addEventListener('click', () => {
                    if(clickSound) { clickSound.currentTime = 0; clickSound.play().catch(()=>{}); }
                    introModal.classList.add('opacity-0', 'scale-95');
                    
                    setTimeout(() => {
                        introModal.remove();
                        countdownOverlay.classList.remove('hidden');
                        countdownOverlay.classList.add('flex');
                        
                        // Fire Epic Countdown Audio!
                        if(sfxCountdown) {
                            sfxCountdown.volume = 1.0;
                            sfxCountdown.currentTime = 0;
                            sfxCountdown.play().catch(()=>{});
                        }

                        // Perfect CSS-synced Countdown Engine
                        const numbers = ["3", "2", "1", "GO!"];
                        let step = 0;

                        function runCountdownStep() {
                            if (step < numbers.length) {
                                countdownText.textContent = numbers[step];
                                
                                if (step === 3) {
                                    countdownText.classList.remove('text-emerald-500');
                                    countdownText.classList.add('text-yellow-400');
                                }
                                
                                // Retrigger CSS Animation
                                countdownText.classList.remove('count-pulse');
                                void countdownText.offsetWidth; // force DOM reflow
                                countdownText.classList.add('count-pulse');
                                
                                step++;
                                if (step <= numbers.length) {
                                    setTimeout(runCountdownStep, 1000); // Wait exactly 1 beat
                                }
                            } else {
                                // Final Step: Trigger Flashbang
                                flashOverlay.classList.remove('hidden');
                                setTimeout(() => flashOverlay.classList.add('opacity-100'), 20);
                                
                                setTimeout(() => {
                                    countdownOverlay.remove();
                                    flashOverlay.classList.remove('opacity-100');
                                    flashOverlay.classList.add('opacity-0');
                                    
                                    // Show Game
                                    mainContent.classList.remove('hidden');
                                    startVideoTimerFlow();
                                    
                                    setTimeout(() => flashOverlay.remove(), 400);
                                }, 150);
                            }
                        }

                        // Start loop
                        runCountdownStep();
                        
                    }, 500);
                });
            } else {
                // Scenario C: Moving between normal questions with no feedback
                mainContent.classList.remove('hidden');
                startVideoTimerFlow();
            }

            function startVideoTimerFlow() {
                const videoSection = document.getElementById('video-section');
                const scenarioSection = document.getElementById('scenario-section');
                const timerDisplay = document.getElementById('video-timer');
                const btnProceed = document.getElementById('btn-proceed');
                const countdownContainer = document.getElementById('countdown-container');
                
                let timeLeft = {{ $activeDuration ?? 0 }};
                let requireVideo = {{ $requireVideo ? 'true' : 'false' }};

                if (requireVideo) {
                    try { window.parent.postMessage('pauseMusic', '*'); } catch(e) {}
                } else {
                    try { window.parent.postMessage('ensureMusicPlaying', '*'); } catch(e) {}
                }

                if (videoSection && timeLeft > 0) {
                    const timer = setInterval(() => {
                        timeLeft--;
                        timerDisplay.textContent = timeLeft + "s";
                        
                        if (timeLeft <= 0) {
                            clearInterval(timer);
                            countdownContainer.classList.add('hidden');
                            btnProceed.classList.remove('hidden');
                            btnProceed.classList.add('animate-pulse');
                        }
                    }, 1000);
                } else if (videoSection && timeLeft <= 0) {
                    if(countdownContainer) countdownContainer.classList.add('hidden');
                    if(btnProceed) btnProceed.classList.remove('hidden');
                }

                if(btnProceed) {
                    btnProceed.addEventListener('click', () => {
                        if(clickSound) { clickSound.currentTime = 0; clickSound.play().catch(()=>{}); }
                        
                        const vid = document.getElementById('mission-video');
                        if(vid) { vid.pause(); vid.currentTime = 0; }
                        
                        const iframe = document.getElementById('mission-video-frame');
                        if(iframe) {
                            let src = iframe.src;
                            iframe.src = '';
                            iframe.src = src;
                        }
                        
                        try { window.parent.postMessage('resumeMusic', '*'); } catch(e) {}
                        
                        videoSection.style.opacity = '0';
                        setTimeout(() => {
                            videoSection.classList.add('hidden');
                            scenarioSection.classList.remove('hidden');
                        }, 500);
                    });
                }
            }
        });
    </script>

    @include('partials.cursor')
    
</body>
</html>