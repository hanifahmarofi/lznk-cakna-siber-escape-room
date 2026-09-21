<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mission Report - {{ $room->title }}</title>
    @vite('resources/css/app.css')
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@700&family=Share+Tech+Mono&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-color: #050a08; 
            --text-color: #d1d5db;
            --card-bg: rgba(15, 30, 20, 0.85);
            --card-border: rgba(255, 255, 255, 0.1);
        }

        .light-mode {
            --bg-color: #f0fdf4; /* Light emerald tint */
            --text-color: #0f172a;
            --card-bg: rgba(255, 255, 255, 0.95);
            --card-border: rgba(16, 185, 129, 0.3);
        }

        body { font-family: 'Share Tech Mono', monospace; background-color: var(--bg-color); color: var(--text-color); overflow-x: hidden; transition: background-color 0.3s ease; }
        .title-font { font-family: 'Poppins', sans-serif; font-weight: 700; }
        .scanlines { background: linear-gradient(to bottom, rgba(255,255,255,0), rgba(255,255,255,0) 50%, rgba(0,0,0,0.1) 50%, rgba(0,0,0,0.1)); background-size: 100% 4px; pointer-events: none; }
        
        .glass-card { background: var(--card-bg); backdrop-filter: blur(12px); border: 1px solid var(--card-border); border-radius: 12px; transition: background 0.3s ease, border-color 0.3s ease; }
        
        .success-theme { border-color: #10b981; box-shadow: 0 0 40px rgba(16, 185, 129, 0.2); }
        .success-text { color: #34d399; text-shadow: 0 0 10px rgba(52, 211, 153, 0.6); }
        
        .failed-theme { border-color: #ef4444; box-shadow: 0 0 40px rgba(239, 68, 68, 0.2); }
        .failed-text { color: #fca5a5; text-shadow: 0 0 10px rgba(239, 68, 68, 0.6); }

        /* Dynamic Background Gradients */
        .theme-bg-success { background: radial-gradient(circle at center, rgba(16, 185, 129, 0.25), var(--bg-color) 60%); }
        .theme-bg-failed { background: radial-gradient(circle at center, rgba(239, 68, 68, 0.25), var(--bg-color) 60%); }

        /* Light Mode Text Overrides */
        .light-mode .text-gray-400, .light-mode .text-gray-500 { color: #475569 !important; }
        .light-mode .text-gray-200, .light-mode .text-gray-300 { color: #1e293b !important; }
        .light-mode .bg-black\/60 { background-color: rgba(241, 245, 249, 0.9) !important; }
        .light-mode .bg-black\/50 { background-color: rgba(226, 232, 240, 0.9) !important; }

        /* Force Black Text on Toggles in Light Mode */
        .light-mode #lang-toggle, .light-mode #theme-toggle {
            color: #000000 !important;
            border-color: #059669 !important;
            background-color: rgba(255, 255, 255, 0.9) !important;
        }

        /* HIDE DEFAULT GOOGLE TRANSLATE UI ELEMENTS */
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

        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: rgba(0,0,0,0.2); border-radius: 4px; }
        ::-webkit-scrollbar-thumb { background: rgba(16,185,129,0.5); border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: rgba(16,185,129,0.8); }
    </style>
</head>
<body class="min-h-screen relative flex flex-col items-center justify-center p-4 md:py-10 transition-colors duration-500">
    
    <div id="google_translate_element"></div>

    <div class="absolute inset-0 scanlines opacity-50 z-10"></div>
    <div class="absolute inset-0 z-0 fixed {{ $finalScore >= $room->pass_mark ? 'theme-bg-success' : 'theme-bg-failed' }} transition-colors duration-500"></div>

    <div class="fixed top-4 right-4 z-[9999] flex gap-3">
        <button id="lang-toggle" class="glass-card border border-emerald-500/50 px-3 py-1.5 rounded-full text-xs font-bold tracking-wider hover:bg-emerald-500 hover:text-white transition-colors text-emerald-400 shadow-lg">
            🇬🇧 ENGLISH
        </button>
        <button id="theme-toggle" class="glass-card border border-emerald-500/50 px-3 py-1.5 rounded-full text-xs font-bold tracking-wider hover:bg-emerald-500 hover:text-white transition-colors text-emerald-400 shadow-lg">
            ☀️ LIGHT MODE
        </button>
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

    <div class="w-full max-w-2xl flex flex-col gap-6 relative z-20 mt-10 md:mt-0">
        <div id="score-card" class="glass-card w-full p-8 md:p-12 text-center {{ $finalScore >= $room->pass_mark ? 'success-theme' : 'failed-theme' }} {{ session('mission_feedback') ? 'hidden' : '' }}">
            
            @if($finalScore >= $room->pass_mark)
                <div class="w-20 h-20 mx-auto bg-emerald-500/20 text-emerald-400 rounded-full flex items-center justify-center mb-6 border-2 border-emerald-500 shadow-[0_0_20px_rgba(16,185,129,0.5)]">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                </div>
                <h1 class="title-font text-3xl md:text-4xl success-text tracking-widest uppercase mb-2 translation-target">Operasi Selesai</h1>
                <p class="text-gray-400 mb-8 tracking-widest uppercase text-sm translation-target">Tindakan anda telah merekodkan impak positif.</p>
            @else
                <div class="w-20 h-20 mx-auto bg-red-500/20 text-red-500 rounded-full flex items-center justify-center mb-6 border-2 border-red-500 shadow-[0_0_20px_rgba(239,68,68,0.5)]">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path></svg>
                </div>
                <h1 class="title-font text-3xl md:text-4xl failed-text tracking-widest uppercase mb-2 translation-target">Operasi Gagal</h1>
                <p class="text-gray-400 mb-8 tracking-widest uppercase text-sm translation-target">Kesilapan kritikal dikesan dalam rantaian keputusan.</p>
            @endif

            <div class="bg-black/60 rounded-xl p-6 mb-8 border {{ $finalScore >= $room->pass_mark ? 'border-emerald-500/30' : 'border-red-500/30' }}">
                <p class="text-xs text-gray-500 uppercase tracking-widest mb-1 translation-target">Skor Penilaian Akhir</p>
                <div class="text-6xl font-bold font-mono tracking-tighter {{ $finalScore >= $room->pass_mark ? 'text-emerald-500' : 'text-red-500' }}">
                    {{ $finalScore }}<span class="text-2xl text-gray-600">pts</span>
                </div>
                <p class="text-xs text-gray-500 uppercase tracking-widest mt-4"><span class="translation-target">Keperluan Lulus:</span> {{ $room->pass_mark }}pts</p>
            </div>

            <a href="{{ route('agent.branching-hub') }}" class="inline-flex items-center justify-center gap-2 border-2 {{ $finalScore >= $room->pass_mark ? 'border-emerald-500 text-emerald-400 hover:bg-emerald-500 hover:text-black' : 'border-red-500 text-red-400 hover:bg-red-500 hover:text-black' }} font-bold py-3 px-8 rounded transition-all uppercase tracking-widest text-sm w-full md:w-auto">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                <span class="translation-target">Kembali ke Hab</span>
            </a>
        </div>

        @if(isset($reportData) && count($reportData) > 0)
        <div id="report-log" class="glass-card w-full p-6 md:p-8 text-left {{ session('mission_feedback') ? 'hidden' : '' }}">
            <h3 class="text-lg font-bold text-gray-300 border-b border-gray-600 pb-3 mb-6 uppercase tracking-widest flex items-center gap-2">
                <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                <span class="translation-target">Log Misi Terperinci</span>
            </h3>
            
            <div class="space-y-4 max-h-96 overflow-y-auto pr-2 custom-scrollbar">
                @foreach($reportData as $log)
                    <div class="p-4 rounded-lg border {{ $log['is_correct'] ? 'border-emerald-500/30 bg-emerald-900/10' : 'border-red-500/30 bg-red-900/10' }}">
                        <p class="text-sm md:text-base text-gray-200 mb-3 font-bold dynamic-translation" data-original="{{ $log['question'] }}">{{ $log['question'] }}</p>
                        
                        <div class="flex justify-between items-end gap-4">
                            <div class="text-xs">
                                <span class="text-gray-500 uppercase tracking-widest translation-target">Pilihan Ejen:</span><br>
                                <span class="{{ $log['is_correct'] ? 'text-emerald-500' : 'text-red-500' }} font-bold dynamic-translation" data-original="{{ $log['answer'] }}">
                                    {{ $log['answer'] }}
                                </span>
                            </div>
                            <div class="text-right shrink-0 bg-black/50 px-3 py-1 rounded border {{ $log['is_correct'] ? 'border-emerald-500/20' : 'border-red-500/20' }}">
                                <span class="text-lg font-bold {{ $log['is_correct'] ? 'text-emerald-500' : 'text-red-500' }}">
                                    {{ $log['points'] > 0 ? '+' . $log['points'] : $log['points'] }}
                                </span>
                                <span class="text-[10px] text-gray-500 uppercase tracking-widest">pts</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>

    <script type="text/javascript">
        function googleTranslateElementInit() {
            new google.translate.TranslateElement({ pageLanguage: 'ms', includedLanguages: 'ms,en', autoDisplay: false }, 'google_translate_element');
        }
    </script>
    <script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>

    <script>
        const enDictionary = {
            "Operasi Selesai": "Operation Cleared",
            "Tindakan anda telah merekodkan impak positif.": "Your actions recorded a positive impact.",
            "Operasi Gagal": "Operation Failed",
            "Kesilapan kritikal dikesan dalam rantaian keputusan.": "Critical failure detected in decision chain.",
            "Skor Penilaian Akhir": "Final Evaluation Score",
            "Keperluan Lulus:": "Passing Requirement:",
            "Kembali ke Hab": "Return to Hub",
            "ANALISIS SISTEM": "SYSTEM ANALYSIS",
            "Teruskan": "Continue",
            "Log Misi Terperinci": "Detailed Mission Log",
            "Pilihan Ejen:": "Agent's Choice:"
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

        // Toggles Logic
        const bodyEl = document.body;
        const themeBtn = document.getElementById('theme-toggle');
        const langBtn = document.getElementById('lang-toggle');

        let currentTheme = localStorage.getItem('shield_theme') || 'dark';
        let currentLang = localStorage.getItem('shield_lang') || 'ms';

        if (currentTheme === 'light') {
            bodyEl.classList.add('light-mode');
            if (themeBtn) themeBtn.innerHTML = '🌙 DARK MODE';
        }

        if (langBtn) {
            langBtn.innerHTML = currentLang === 'en' ? '🇲🇾 BAHASA' : '🇬🇧 ENGLISH';
        }

        if (themeBtn) {
            themeBtn.addEventListener('click', () => {
                currentTheme = currentTheme === 'dark' ? 'light' : 'dark';
                localStorage.setItem('shield_theme', currentTheme);
                if(currentTheme === 'light') { bodyEl.classList.add('light-mode'); themeBtn.innerHTML = '🌙 DARK MODE'; } 
                else { bodyEl.classList.remove('light-mode'); themeBtn.innerHTML = '☀️ LIGHT MODE'; }
            });
        }

        if (langBtn) {
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
        }
        
        // Execute translations on load
        document.querySelectorAll('.translation-target').forEach(el => {
            const text = el.textContent.trim();
            if (currentLang === 'en' && enDictionary[text]) el.textContent = enDictionary[text];
        });
        
        processDynamicTranslations(currentLang);

        // FEEDBACK DISMISSAL LOGIC
        document.addEventListener('DOMContentLoaded', () => {
            const feedbackModal = document.getElementById('feedback-modal');
            const btnDismissFeedback = document.getElementById('btn-dismiss-feedback');
            const scoreCard = document.getElementById('score-card');
            const reportLog = document.getElementById('report-log');

            if (feedbackModal && btnDismissFeedback) {
                btnDismissFeedback.addEventListener('click', () => {
                    feedbackModal.classList.add('opacity-0', 'scale-95');
                    setTimeout(() => {
                        feedbackModal.remove();
                        if (scoreCard) scoreCard.classList.remove('hidden');
                        if (reportLog) reportLog.classList.remove('hidden');
                    }, 300);
                });
            }
        });
    </script>

    @include('partials.cursor')

</body>
</html>