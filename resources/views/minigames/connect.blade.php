<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>S.H.I.E.L.D // {{ $game->title }}</title>
    @vite('resources/css/app.css')
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@700&family=Share+Tech+Mono&display=swap" rel="stylesheet">
    <style>
        /* --- HIGH CONTRAST THEME SYSTEM --- */
        :root {
            --bg-color: #000000;
            --text-color: #d1d5db;
            --card-bg: rgba(21, 21, 21, 0.85);
            --border-color: rgba(168, 85, 247, 0.5);
            --title-color: #a855f7; /* Purple 500 */
            --vid-filter: none;
            --vid-overlay: rgba(88, 28, 135, 0.2);
            --node-bg: rgba(88, 28, 135, 0.2);
            --node-border: rgba(168, 85, 247, 0.3);
            --node-text: #e9d5ff;
        }

        .light-mode {
            --bg-color: #f3f4f6;
            --text-color: #1f2937;
            --card-bg: rgba(255, 255, 255, 0.95);
            --border-color: rgba(147, 51, 234, 0.6);
            --title-color: #7e22ce; /* Purple 700 */
            --vid-filter: invert(1) hue-rotate(180deg) brightness(1.5);
            --vid-overlay: rgba(255, 255, 255, 0.5);
            --node-bg: rgba(243, 232, 255, 0.8);
            --node-border: rgba(147, 51, 234, 0.6);
            --node-text: #4c1d95;
        }

        body { 
            font-family: 'Share Tech Mono', monospace; 
            background-color: var(--bg-color); 
            color: var(--text-color); 
            touch-action: none; /* Prevents scrolling while dragging */
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .theme-card { background-color: var(--card-bg) !important; border-color: var(--border-color) !important; transition: all 0.3s ease; }
        .theme-title { color: var(--title-color) !important; transition: color 0.3s ease; }
        .theme-video { filter: var(--vid-filter); transition: filter 0.5s ease; }
        .theme-vid-overlay { background-color: var(--vid-overlay); transition: background-color 0.5s ease; }

        /* Light Mode Overrides for Specific Elements */
        .light-mode .text-white { color: #000000 !important; }
        .light-mode .text-gray-400 { color: #4b5563 !important; }
        .light-mode .text-gray-500 { color: #374151 !important; }
        .light-mode .bg-black\/60 { background-color: rgba(255,255,255,0.85) !important; }
        .light-mode .bg-black\/80 { background-color: rgba(255,255,255,0.95) !important; }
        .light-mode .border-gray-800 { border-color: #d1d5db !important; }

        .title-font { font-family: 'Poppins', sans-serif; font-weight: 700; text-shadow: 0 0 10px rgba(168,85,247,0.7); }
        .scanlines { background: linear-gradient(to bottom, rgba(255,255,255,0), rgba(255,255,255,0) 50%, rgba(0,0,0,0.1) 50%, rgba(0,0,0,0.1)); background-size: 100% 4px; }
        
        .shake { animation: shake 0.5s cubic-bezier(.36,.07,.19,.97) both; }
        @keyframes shake { 10%, 90% { transform: translate3d(-1px, 0, 0); } 20%, 80% { transform: translate3d(2px, 0, 0); } 30%, 50%, 70% { transform: translate3d(-4px, 0, 0); } 40%, 60% { transform: translate3d(4px, 0, 0); } }

        /* DATA NODE BUTTONS */
        .node-btn {
            background-color: var(--node-bg);
            border: 2px solid var(--node-border);
            color: var(--node-text);
            border-radius: 0.5rem;
            padding: 1rem 2rem;
            font-size: 0.875rem;
            transition: all 0.3s ease;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 4.5rem;
            box-shadow: 0 0 10px rgba(168,85,247,0.1);
            user-select: none;
            touch-action: none;
            z-index: 20;
        }
        @media (min-width: 768px) {
            .node-btn { font-size: 1rem; min-height: 5.5rem; }
        }

        /* CONNECTION PORTS (THE DOTS) */
        .port {
            position: absolute;
            width: 20px;
            height: 20px;
            background-color: var(--bg-color);
            border: 4px solid var(--title-color);
            border-radius: 50%;
            top: 50%;
            transform: translateY(-50%);
            box-shadow: 0 0 10px var(--title-color);
            transition: all 0.3s;
            z-index: 30;
        }
        .port-left { right: -10px; } 
        .port-right { left: -10px; } 

        /* NODE STATES */
        .node-btn.draggable { cursor: grab; }
        .node-btn.draggable:active { cursor: grabbing; }
        .node-btn.droppable { cursor: crosshair; }

        .node-btn:hover:not(.locked) {
            background-color: rgba(168, 85, 247, 0.4);
            border-color: rgba(168, 85, 247, 0.8);
            box-shadow: 0 0 15px rgba(168,85,247,0.5);
            color: #ffffff;
        }
        .light-mode .node-btn:hover:not(.locked) { color: #ffffff; }

        /* Actively Dragging */
        .node-btn.active-drag {
            background-color: rgba(168, 85, 247, 0.6);
            border-color: #a855f7;
            color: #ffffff;
            box-shadow: 0 0 20px rgba(168,85,247,0.8);
        }
        .node-btn.active-drag .port { background-color: #a855f7; box-shadow: 0 0 15px #d8b4fe; border-width: 6px; }

        /* Locked / Solved */
        .node-btn.locked {
            background-color: rgba(16, 185, 129, 0.15) !important;
            border-color: #10b981 !important;
            color: #10b981 !important;
            box-shadow: inset 0 0 10px rgba(16, 185, 129, 0.2) !important;
            cursor: default;
        }
        .node-btn.locked .port { border-color: #10b981; background-color: #10b981; box-shadow: 0 0 10px #10b981; }

        /* Wrong match */
        .node-btn.wrong {
            background-color: rgba(239, 68, 68, 0.3) !important;
            border-color: #ef4444 !important;
            color: #ffffff !important;
            box-shadow: 0 0 20px rgba(239,68,68,0.6) !important;
        }
        .node-btn.wrong .port { border-color: #ef4444; background-color: #ef4444; box-shadow: 0 0 10px #ef4444; }

        /* SVG Wire Styles */
        .wire {
            fill: none;
            stroke: var(--title-color);
            stroke-width: 5;
            stroke-linecap: round;
            filter: drop-shadow(0 0 8px var(--title-color));
            transition: stroke 0.3s;
            pointer-events: none;
        }
        .wire.locked-wire {
            stroke: #10b981;
            filter: drop-shadow(0 0 8px rgba(16,185,129,0.9));
        }
        .wire.wrong-wire {
            stroke: #ef4444;
            filter: drop-shadow(0 0 8px rgba(239,68,68,0.9));
        }
    </style>
</head>
<body class="min-h-screen relative overflow-hidden flex flex-col items-center justify-center p-4">

    <div class="fixed inset-0 z-0 overflow-hidden pointer-events-none">
        <video autoplay loop muted playsinline class="w-full h-full object-cover opacity-30 theme-video">
            <source src="{{ asset('video/videoplayback.webm') }}" type="video/webm">
        </video>
        <div class="absolute inset-0 bg-purple-900 mix-blend-color opacity-20"></div>
        <div class="absolute inset-0 bg-gradient-to-b from-black/90 via-black/80 to-black/95 light-mode:hidden"></div>
        <div class="absolute inset-0 scanlines opacity-40"></div>
    </div>

    <div class="fixed top-0 left-0 w-full px-4 md:px-8 py-4 flex justify-between items-start md:items-center z-50 pointer-events-none">
        <div class="pointer-events-auto">
            <h1 class="title-font text-xl md:text-3xl theme-title uppercase drop-shadow-[0_0_10px_rgba(168,85,247,0.5)] dynamic-translation" data-original="{{ $game->title }}">{{ $game->title }}</h1>
            <p class="text-[10px] md:text-sm text-purple-500/70 font-bold tracking-widest uppercase" data-en="Hardware Patching Initiated" data-ms="Tampalan Perkakasan Dimulakan">Hardware Patching Initiated</p>
        </div>
        
        <div class="pointer-events-auto flex flex-col md:flex-row items-end md:items-center gap-2 md:gap-4">
            <div class="flex gap-2">
                <button id="lang-toggle" class="theme-card border border-purple-500/50 px-3 py-1.5 rounded-full text-[10px] md:text-xs font-bold tracking-wider hover:bg-purple-600 hover:text-white transition-colors shadow-[0_0_10px_rgba(168,85,247,0.3)] theme-title">
                    🇲🇾 BAHASA
                </button>
                <button id="theme-toggle" class="theme-card border border-purple-500/50 px-3 py-1.5 rounded-full text-[10px] md:text-xs font-bold tracking-wider hover:bg-purple-600 hover:text-white transition-colors shadow-[0_0_10px_rgba(168,85,247,0.3)] theme-title">
                    ☀️ LIGHT MODE
                </button>
            </div>

            <a href="{{ route('agent.arcade') }}" class="px-5 py-2 theme-card border border-purple-500/50 theme-title rounded hover:bg-purple-600 hover:text-white transition-all text-[10px] md:text-xs font-bold tracking-widest uppercase shadow-[0_0_15px_rgba(147,51,234,0.3)] flex items-center justify-center gap-2">
                <span data-en="ABORT" data-ms="BATAL">ABORT</span>
            </a>
        </div>
    </div>

    <div class="relative w-full max-w-6xl theme-card backdrop-blur-md rounded-xl p-6 md:p-10 shadow-[0_0_30px_rgba(147,51,234,0.2)] text-center mt-24 md:mt-20 mb-8 z-10 sc-anim" id="game-container">
        
        <div class="flex justify-between items-center mb-6 md:mb-8 border-b border-gray-800 pb-4 md:pb-6 relative z-20 transition-colors">
            <div class="text-left w-1/3">
                <p class="text-[10px] text-gray-500 uppercase tracking-widest font-bold text-left" data-en="Links Secured" data-ms="Pautan Diselamatkan">Links Secured</p>
                <p class="text-lg md:text-2xl text-white font-bold font-mono"><span id="current-round">0</span> / <span id="total-rounds">X</span></p>
            </div>
            
            <div class="text-center w-1/3">
                <div class="inline-flex items-center justify-center p-2 rounded-full bg-purple-900/30 border border-purple-500/30 shadow-[0_0_15px_rgba(168,85,247,0.2)]">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 theme-title" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.19 8.688a4.5 4.5 0 011.242 7.244l-4.5 4.5a4.5 4.5 0 01-6.364-6.364l1.757-1.757m13.35-.622l1.757-1.757a4.5 4.5 0 00-6.364-6.364l-4.5 4.5a4.5 4.5 0 001.242 7.244" /></svg>
                </div>
            </div>

            <div class="text-right w-1/3">
                <p class="text-[10px] text-gray-500 uppercase tracking-widest font-bold text-right" data-en="Max Reward" data-ms="Ganjaran Max">Max Reward</p>
                <p class="text-lg md:text-2xl text-emerald-400 font-bold font-mono">{{ $game->base_score }} PTS</p>
            </div>
        </div>

        @if($game->instruction)
            <p class="text-sm md:text-base text-gray-400 italic mb-8 px-4 relative z-20 dynamic-translation" data-original="{{ $game->instruction }}">"{{ $game->instruction }}"</p>
        @else
            <p class="text-sm md:text-base text-gray-400 italic mb-8 px-4 relative z-20" data-en="Drag a wire from the Origin Port to the Target Host to establish a secure link." data-ms="Tarik wayar dari Port Asal ke Hos Sasaran untuk membuat pautan selamat.">"Drag a wire from the Origin Port to the Target Host to establish a secure link."</p>
        @endif

        <svg id="wire-canvas" class="absolute inset-0 w-full h-full pointer-events-none z-10"></svg>

        <div class="flex flex-col md:flex-row justify-between w-full mt-4 relative z-20" id="nodes-wrapper">
            
            <div class="flex flex-col gap-6 w-full md:w-5/12" style="width: 100%; max-width: 40%;" id="left-column">
                <p class="text-[10px] theme-title uppercase tracking-widest font-bold mb-2 text-left border-b border-purple-500/30 pb-2" data-en="Origin Port (Terms)" data-ms="Port Asal (Terma)">Origin Port (Terms)</p>
            </div>

            <div class="hidden md:flex w-2/12 items-center justify-center pointer-events-none" style="width: 20%;">
                <div class="h-full w-px bg-gradient-to-b from-transparent via-purple-500/20 to-transparent"></div>
            </div>

            <div class="flex flex-col gap-6 w-full md:w-5/12 mt-12 md:mt-0" style="width: 100%; max-width: 40%;" id="right-column">
                <p class="text-[10px] theme-title uppercase tracking-widest font-bold mb-2 text-left md:text-right border-b border-purple-500/30 pb-2" data-en="Target Host (Matches)" data-ms="Hos Sasaran (Padanan)">Target Host (Matches)</p>
            </div>

        </div>

    </div>

    <div id="success-overlay" class="fixed inset-0 z-[100] bg-black/90 backdrop-blur-sm hidden flex-col items-center justify-center p-4 pointer-events-auto">
        <div class="w-20 h-20 md:w-24 md:h-24 rounded-full bg-emerald-500/20 flex items-center justify-center text-emerald-500 border border-emerald-500 mb-6 shadow-[0_0_30px_rgba(16,185,129,0.5)]">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 md:h-12 md:w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
        </div>
        <h2 class="title-font text-3xl md:text-4xl text-emerald-400 mb-2 tracking-widest uppercase text-center" data-en="Network Linked" data-ms="Rangkaian Dipautkan">Network Linked</h2>
        <p class="text-sm md:text-base text-gray-400 font-mono mb-8 text-center" data-en="All data nodes successfully routed." data-ms="Semua nod data berjaya dihalakan.">All data nodes successfully routed.</p>
        
        <form action="{{ route('agent.arcade.complete', $game->id) }}" method="POST">
            @csrf
            <input type="hidden" name="score" value="{{ $game->base_score }}">
            <input type="hidden" name="correct" id="track-correct" value="0">
            <input type="hidden" name="incorrect" id="track-incorrect" value="0">
            <input type="hidden" name="wrong_answers" id="track-wrong" value="[]">
            
            <button type="submit" class="px-8 py-3 md:px-10 md:py-4 bg-emerald-600 hover:bg-emerald-500 text-black rounded font-extrabold tracking-widest uppercase text-base md:text-lg shadow-[0_0_20px_rgba(16,185,129,0.6)] transition-all">
                <span data-en="CLAIM REWARD & RETURN" data-ms="TUNTUT GANJARAN">CLAIM REWARD & RETURN</span>
            </button>
        </form>
    </div>

    <audio id="ui-click-sound" src="{{ asset('audio/mixkit-sci-fi-click-900.wav') }}" preload="auto"></audio>
    <audio id="sfx-right" src="{{ asset('audio/right-answer.mp3') }}" preload="auto"></audio>
    <audio id="sfx-wrong" src="{{ asset('audio/wrong-answer.mp3') }}" preload="auto"></audio>

    <script>
        // --- GAME LOGIC & VARIABLES ---
        const gameData = @json($game->game_data);
        
        let rawPairs = [];
        if (gameData && gameData.pairs) {
            rawPairs = Array.isArray(gameData.pairs) ? gameData.pairs : Object.values(gameData.pairs);
        }

        // Clean empty entries
        rawPairs = rawPairs.filter(p => p && p.term && p.term.trim() !== '' && p.def && p.def.trim() !== '');

        // Structure the nodes
        let leftNodes = rawPairs.map((p, index) => ({ id: index, text: p.term }));
        let rightNodes = rawPairs.map((p, index) => ({ id: index, text: p.def }));

        // Shuffle arrays independently
        function shuffle(array) {
            let currentIndex = array.length, randomIndex;
            while (currentIndex > 0) {
                randomIndex = Math.floor(Math.random() * currentIndex);
                currentIndex--;
                [array[currentIndex], array[randomIndex]] = [array[randomIndex], array[currentIndex]];
            }
            return array;
        }
        leftNodes = shuffle(leftNodes);
        rightNodes = shuffle(rightNodes);

        // UI Elements
        const leftColumnEl = document.getElementById('left-column');
        const rightColumnEl = document.getElementById('right-column');
        const currentRoundEl = document.getElementById('current-round');
        const totalRoundsEl = document.getElementById('total-rounds');
        const svgCanvas = document.getElementById('wire-canvas');
        
        const clickSound = document.getElementById('ui-click-sound');
        const sfxRight = document.getElementById('sfx-right');
        const sfxWrong = document.getElementById('sfx-wrong');

        // State Tracking
        let matchedCount = 0;
        let isDragging = false;
        let startNodeId = null;
        let startPortPos = null;
        let activePath = null;
        let establishedLinks = []; 

        // 🚨 TRACKING VARIABLES
        let trackCorrect = 0;
        let trackIncorrect = 0;
        let trackWrongList = [];

        // Renders the nodes dynamically
        function renderNodes() {
            totalRoundsEl.innerText = rawPairs.length;
            currentRoundEl.innerText = matchedCount;

            if (rawPairs.length === 0) {
                leftColumnEl.innerHTML = `<p class="text-red-500">ERROR: NO VALID DATA NODES FOUND</p>`;
                return;
            }

            // Render Left Nodes
            leftNodes.forEach(node => {
                const btn = document.createElement('div');
                btn.className = `node-btn draggable shadow-[inset_4px_0_0_rgba(168,85,247,0.5)]`; 
                btn.id = `left-node-${node.id}`;
                btn.dataset.id = node.id;
                let safeText = node.text.replace(/"/g, '&quot;');
                btn.innerHTML = `<span class="px-4 z-10 pointer-events-none dynamic-translation" data-original="${safeText}">${node.text}</span><div class="port port-left" id="port-l-${node.id}"></div>`;
                
                btn.addEventListener('pointerdown', handleDragStart);
                leftColumnEl.appendChild(btn);
            });

            // Render Right Nodes
            rightNodes.forEach(node => {
                const btn = document.createElement('div');
                btn.className = `node-btn droppable shadow-[inset_-4px_0_0_rgba(168,85,247,0.5)]`;
                btn.id = `right-node-${node.id}`;
                btn.dataset.id = node.id;
                let safeText = node.text.replace(/"/g, '&quot;');
                btn.innerHTML = `<div class="port port-right" id="port-r-${node.id}"></div><span class="px-4 z-10 pointer-events-none dynamic-translation" data-original="${safeText}">${node.text}</span>`;
                rightColumnEl.appendChild(btn);
            });
        }

        // --- SOUND INTERCEPTOR ---
        document.addEventListener("DOMContentLoaded", function() {
            if(clickSound) {
                clickSound.volume = 0.6; 
                
                document.querySelectorAll('button, a').forEach(element => {
                    element.addEventListener('click', function(e) {
                        clickSound.currentTime = 0; 
                        clickSound.play().catch(err => console.log(err));
                        
                        if (this.tagName.toLowerCase() === 'a' && this.hasAttribute('href')) {
                            let target = this.getAttribute('href');
                            if (target.startsWith('#') || target.startsWith('javascript:')) return;
                            e.preventDefault();
                            setTimeout(() => { window.location.href = target; }, 250);
                        }
                    });
                });
            }
        });

        // --- THEME & LANGUAGE LOGIC ---
        window.translationCache = {};

        async function translateGoogleAPI(text, targetLang) {
            if(targetLang === 'en') return text; 
            let url = `https://translate.googleapis.com/translate_a/single?client=gtx&sl=en&tl=${targetLang}&dt=t&q=${encodeURIComponent(text)}`;
            try {
                let response = await fetch(url);
                let data = await response.json();
                let fullText = "";
                if (data && data[0]) {
                    for (let i = 0; i < data[0].length; i++) fullText += data[0][i][0];
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
                
                if(!originalText || originalText === '--') continue;

                if (lang === 'en') {
                    if (el.tagName === 'P' || el.tagName === 'SPAN' || el.tagName === 'H1' || el.tagName === 'H3') {
                        el.innerText = originalText;
                    }
                    continue;
                }

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

        if (currentTheme === 'light') { 
            bodyEl.classList.add('light-mode'); 
            themeBtn.innerHTML = '🌙 DARK MODE'; 
        }

        function applyLanguage(lang) {
            langBtn.innerHTML = lang === 'en' ? '🇲🇾 BAHASA' : '🇬🇧 ENGLISH';
            
            translatables.forEach(el => {
                const textSpan = el.querySelector('span:not(.dynamic-translation)');
                if (textSpan && !el.hasAttribute('data-en')) {
                    textSpan.innerHTML = el.getAttribute(`data-${lang}`);
                } else {
                    if (!el.classList.contains('dynamic-translation')) {
                        el.innerHTML = el.getAttribute(`data-${lang}`);
                    }
                }
            });

            processDynamicTranslations(lang);
        }

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

        langBtn.addEventListener('click', () => {
            currentLang = currentLang === 'en' ? 'ms' : 'en';
            localStorage.setItem('shield_lang', currentLang);
            applyLanguage(currentLang);
        });

        // --- WIRE DRAWING LOGIC ---
        function getPortCoordinates(portElement) {
            const svgRect = svgCanvas.getBoundingClientRect();
            const portRect = portElement.getBoundingClientRect();
            
            return {
                x: (portRect.left + portRect.width / 2) - svgRect.left,
                y: (portRect.top + portRect.height / 2) - svgRect.top
            };
        }

        function updatePath(pathElement, x1, y1, x2, y2) {
            const offset = Math.abs(x2 - x1) * 0.5;
            const d = `M ${x1} ${y1} C ${x1 + offset} ${y1}, ${x2 - offset} ${y2}, ${x2} ${y2}`;
            pathElement.setAttribute("d", d);
        }

        function handleDragStart(e) {
            const node = e.currentTarget;
            if (node.classList.contains('locked')) return;

            isDragging = true;
            startNodeId = node.dataset.id;
            node.classList.add('active-drag');
            
            if(clickSound) { clickSound.currentTime = 0; clickSound.play().catch(()=>{}); }

            const port = document.getElementById(`port-l-${startNodeId}`);
            startPortPos = getPortCoordinates(port);

            activePath = document.createElementNS("http://www.w3.org/2000/svg", "path");
            activePath.classList.add('wire');
            svgCanvas.appendChild(activePath);

            updatePath(activePath, startPortPos.x, startPortPos.y, startPortPos.x, startPortPos.y);

            window.addEventListener('pointermove', handleDragMove);
            window.addEventListener('pointerup', handleDragEnd);
            
            e.preventDefault();
        }

        function handleDragMove(e) {
            if (!isDragging || !activePath) return;

            const svgRect = svgCanvas.getBoundingClientRect();
            const currentX = e.clientX - svgRect.left;
            const currentY = e.clientY - svgRect.top;

            updatePath(activePath, startPortPos.x, startPortPos.y, currentX, currentY);
        }

        function handleDragEnd(e) {
            if (!isDragging) return;

            window.removeEventListener('pointermove', handleDragMove);
            window.removeEventListener('pointerup', handleDragEnd);

            isDragging = false;
            const startNode = document.getElementById(`left-node-${startNodeId}`);
            startNode.classList.remove('active-drag');

            activePath.style.display = 'none';
            const dropTarget = document.elementFromPoint(e.clientX, e.clientY);
            activePath.style.display = 'block';

            const rightNode = dropTarget ? dropTarget.closest('.droppable:not(.locked)') : null;

            if (rightNode) {
                const targetNodeId = rightNode.dataset.id;
                checkMatch(startNodeId, targetNodeId, startNode, rightNode, activePath);
            } else {
                activePath.remove();
                activePath = null;
                startNodeId = null;
            }
        }

        function checkMatch(leftId, rightId, leftNode, rightNode, wirePath) {
            if (leftId === rightId) {
                trackCorrect++; // 🚨 Tracking Check

                // MATCH CORRECT!
                if(sfxRight) { sfxRight.currentTime = 0; sfxRight.play().catch(()=>{}); }
                
                leftNode.classList.add('locked');
                rightNode.classList.add('locked');
                wirePath.classList.add('locked-wire');

                const rightPort = document.getElementById(`port-r-${rightId}`);
                const rightPos = getPortCoordinates(rightPort);
                updatePath(wirePath, startPortPos.x, startPortPos.y, rightPos.x, rightPos.y);

                establishedLinks.push({ leftId, rightId, path: wirePath });

                matchedCount++;
                currentRoundEl.innerText = matchedCount;

                if (matchedCount === rawPairs.length) {
                    setTimeout(() => {
                        // 🚨 POPULATE TRACKING DATA BEFORE SHOWING MODAL
                        document.getElementById('track-correct').value = trackCorrect;
                        document.getElementById('track-incorrect').value = trackIncorrect;
                        document.getElementById('track-wrong').value = JSON.stringify(trackWrongList);

                        document.getElementById('success-overlay').classList.remove('hidden');
                        document.getElementById('success-overlay').classList.add('flex');
                    }, 800);
                }

            } else {
                trackIncorrect++; // 🚨 Tracking Check
                let termText = leftNode.textContent.trim();
                let defText = rightNode.textContent.trim();
                trackWrongList.push(`Term: ${termText} | Mismatched to: ${defText}`); // 🚨 Tracking Logs

                // MATCH WRONG!
                if(sfxWrong) { sfxWrong.currentTime = 0; sfxWrong.play().catch(()=>{}); }
                
                wirePath.classList.add('wrong-wire');
                leftNode.classList.add('wrong', 'shake');
                rightNode.classList.add('wrong', 'shake');

                const rightPort = document.getElementById(`port-r-${rightId}`);
                const rightPos = getPortCoordinates(rightPort);
                updatePath(wirePath, startPortPos.x, startPortPos.y, rightPos.x, rightPos.y);

                setTimeout(() => {
                    leftNode.classList.remove('wrong', 'shake');
                    rightNode.classList.remove('wrong', 'shake');
                    wirePath.remove();
                }, 600);
            }

            activePath = null;
            startNodeId = null;
        }

        window.addEventListener('resize', () => {
            establishedLinks.forEach(link => {
                const portL = document.getElementById(`port-l-${link.leftId}`);
                const portR = document.getElementById(`port-r-${link.rightId}`);
                if(portL && portR) {
                    const posL = getPortCoordinates(portL);
                    const posR = getPortCoordinates(portR);
                    updatePath(link.path, posL.x, posL.y, posR.x, posR.y);
                }
            });
        });

        window.addEventListener('DOMContentLoaded', () => {
            renderNodes();
            applyLanguage(currentLang);
        });
    </script>
    @include('partials.cursor')
</body>
</html>