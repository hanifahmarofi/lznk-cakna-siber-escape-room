<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Peta Visual Penuh - Cyber Escape Room</title>
    <script src="https://cdn.tailwindcss.com/3.4.17"></script>
    <script src="https://unpkg.com/panzoom@9.4.0/dist/panzoom.min.js"></script>

    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        /* 🛑 HALANG ANIMASI SEMASA PAGE RELOAD */
        .preload * { transition: none !important; }

        /* 🎨 SISTEM TEMA PINTAR */
        :root {
            --bg-1: #0f2818;
            --bg-2: #1a3a2a;
            --text-main: #e5e7eb;
            --text-muted: #a8d5ba;
            --text-gold: #f0d56f;
            --text-gold-dark: #d4af37;
            --glass-bg: rgba(10, 40, 25, 0.7);
            --glass-card: rgba(10, 40, 25, 0.6);
            --glass-border: rgba(212, 175, 55, 0.3);
            --glass-border-hover: rgba(212, 175, 55, 0.6);
            --glass-shadow: rgba(212, 175, 55, 0.15);
            --btn-outline-bg: rgba(212, 175, 55, 0.1);
            --btn-outline-hover: rgba(212, 175, 55, 0.2);
            --map-bg: rgba(0, 0, 0, 0.4);
            --map-instruction-bg: rgba(0, 0, 0, 0.6);
            --map-instruction-text: #f0d56f;
        }

        body.light-mode {
            --bg-1: #f2fcf5; 
            --bg-2: #e6f7ec; 
            --text-main: #064e3b; 
            --text-muted: #047857;
            --text-gold: #b45309; 
            --text-gold-dark: #92400e;
            --glass-bg: rgba(255, 255, 255, 0.85);
            --glass-card: rgba(255, 255, 255, 0.7);
            --glass-border: rgba(16, 185, 129, 0.4);
            --glass-border-hover: rgba(16, 185, 129, 0.8);
            --glass-shadow: rgba(16, 185, 129, 0.2);
            --btn-outline-bg: rgba(16, 185, 129, 0.1);
            --btn-outline-hover: rgba(16, 185, 129, 0.2);
            --map-bg: rgba(255, 255, 255, 0.5);
            --map-instruction-bg: rgba(255, 255, 255, 0.8);
            --map-instruction-text: #b45309;
        }

        html, body { height: 100%; width: 100%; overflow: hidden; } 
        body { 
            font-family: 'Segoe UI', sans-serif; 
            background: linear-gradient(135deg, var(--bg-1) 0%, var(--bg-2) 50%, var(--bg-1) 100%); 
            color: var(--text-main); 
            background-attachment: fixed; 
            transition: background 0.5s ease, color 0.5s ease;
        }

        .glass-panel { background: var(--glass-bg); backdrop-filter: blur(10px); border: 1px solid var(--glass-border); box-shadow: 0 8px 32px rgba(0, 0, 0, 0.15); transition: all 0.5s ease; }
        .glass-card { background: var(--glass-card); backdrop-filter: blur(8px); border: 1px solid var(--glass-border); border-radius: 16px; transition: all 0.5s ease; }
        
        .text-gold-gradient { background: linear-gradient(135deg, #d4af37 0%, #f0d56f 50%, #d4af37 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }
        body.light-mode .text-gold-gradient { background: linear-gradient(135deg, #b45309 0%, #d97706 50%, #b45309 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }
        
        .btn-outline-gold { border: 1px solid var(--text-gold-dark); color: var(--text-gold); background: var(--btn-outline-bg); transition: all 0.3s; }
        .btn-outline-gold:hover { background: var(--btn-outline-hover); box-shadow: 0 0 15px var(--glass-shadow); color: var(--text-main); cursor: pointer;}
        
        #map-container { position: absolute; top: 0; left: 0; width: 100%; height: 100%; overflow: hidden; }
        #map-container svg { width: 100% !important; height: 100% !important; max-width: none !important; cursor: grab; }
        #map-container svg:active { cursor: grabbing; }

        #instruction {
            background: var(--map-instruction-bg);
            border-color: var(--glass-border);
            color: var(--map-instruction-text);
            transition: all 0.5s ease, opacity 1s ease;
        }
        
        /* CLEAN UP TEXT PADDING FOR MERMAID BOXES */
        .nodeLabel {
            padding: 15px;
            line-height: 1.5;
        }
    </style>
</head>
<body class="relative preload">
    <script>
        if (localStorage.getItem('theme') === 'light') {
            document.body.classList.add('light-mode');
        }
    </script>

    <nav class="glass-panel fixed top-0 left-0 w-full z-50 border-b-2 border-t-0 border-l-0 border-r-0 theme-border">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20 items-center">
                <div class="flex items-center gap-4">
                    <img src="{{ asset('img/logo2.png') }}" onerror="this.style.display='none'" alt="LZNK" class="h-12 drop-shadow-[0_0_8px_rgba(212,175,55,0.6)]">
                    <div class="border-l theme-border h-10 pl-4 hidden sm:block">
                        <h1 class="text-xl font-bold text-gold-gradient tracking-wide translation-target">Peta Visual Penuh (Branching)</h1>
                        <p class="text-xs theme-text-muted tracking-widest uppercase truncate max-w-[200px] md:max-w-xs"><span class="translation-target">Modul:</span> <span class="db-translate" data-ms="{{ $room->title }}" data-en="{{ $room->title_en ?: $room->title }}">{{ $room->title }}</span></p>
                    </div>
                </div>
                
                <div class="flex items-center gap-3">
                    <div class="flex items-center bg-black/20 p-1 rounded-lg border theme-border mr-1 md:mr-2 backdrop-blur-sm shadow-inner z-50 relative">
                        <button onclick="switchLanguage('ms')" id="lang-ms" class="px-2.5 py-1 text-[10px] font-bold rounded bg-[#10b981] text-white shadow-sm transition-all">BM</button>
                        <button onclick="switchLanguage('en')" id="lang-en" class="px-2.5 py-1 text-[10px] font-bold rounded text-gray-400 hover:text-white transition-all">EN</button>
                    </div>

                    <label class="relative inline-flex items-center cursor-pointer mr-2" title="Tukar Tema">
                        <input type="checkbox" id="theme-toggle" class="sr-only peer" autocomplete="off">
                        <div class="w-12 h-6 bg-gray-700 peer-focus:outline-none rounded-full peer peer-checked:bg-[#10b981] transition-colors duration-300 relative border border-gray-500 peer-checked:border-green-400">
                            <div class="absolute top-[1px] left-[2px] bg-white rounded-full h-5 w-5 transition-transform duration-300 peer-checked:translate-x-full flex items-center justify-center shadow-sm" id="toggle-circle">
                                <svg class="w-3 h-3 text-gray-800" fill="currentColor" viewBox="0 0 20 20"><path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path></svg>
                            </div>
                        </div>
                    </label>
                </div>
            </div>
        </div>
    </nav>

    <main class="w-full h-full relative z-10 p-4 pt-28 pb-24">
        <div class="w-full h-full glass-card border-2 border-dotted theme-border theme-map-bg rounded-xl overflow-hidden relative shadow-lg">
            
            <div id="instruction" class="absolute top-4 left-4 z-20 border px-3 py-2 rounded-lg text-xs font-bold uppercase tracking-wider flex items-center gap-2 shadow-md">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span class="hidden sm:inline translation-target">Guna Tetikus untuk Tarik (Drag), Guna Roda Tetikus untuk Zum (Zoom).</span>
                <span class="sm:hidden translation-target">Tarik untuk gerak, Cubit (Pinch) untuk Zum.</span>
            </div>

            <pre id="raw-mermaid-data-ms" class="hidden">{!! $mermaidMs !!}</pre>
            <pre id="raw-mermaid-data-en" class="hidden">{!! $mermaidEn !!}</pre>

            <div id="map-container" class="mermaid flex justify-center items-center">
                </div>
        </div>
    </main>

    <div class="fixed bottom-0 left-0 w-full glass-panel border-t-2 border-b-0 border-l-0 border-r-0 theme-border py-4 px-6 z-50 flex justify-center sm:justify-end items-center shadow-[0_-8px_32px_rgba(0,0,0,0.15)]">
        <div class="max-w-4xl mx-auto w-full flex justify-center sm:justify-end">
            <a href="{{ route('admin.builder.show', $room->id) }}" class="btn-outline-gold px-8 py-3 rounded-lg text-sm font-bold uppercase tracking-widest w-full sm:w-auto text-center flex justify-center items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                <span class="translation-target">Kembali ke Soalan</span>
            </a>
        </div>
    </div>

    <script type="module">
        import mermaid from 'https://cdn.jsdelivr.net/npm/mermaid@10/dist/mermaid.esm.min.mjs';

        const enDictionary = {
            "Peta Visual Penuh (Branching)": "Full Visual Map (Branching)",
            "Modul:": "Module:",
            "Guna Tetikus untuk Tarik (Drag), Guna Roda Tetikus untuk Zum (Zoom).": "Use Mouse to Drag, Use Scroll Wheel to Zoom.",
            "Tarik untuk gerak, Cubit (Pinch) untuk Zum.": "Drag to move, Pinch to Zoom.",
            "Kembali ke Soalan": "Back to Questions",
        };

        const currentLang = localStorage.getItem('lang') || 'ms';

        function applyTranslation() {
            const btnEn = document.getElementById('lang-en');
            const btnMs = document.getElementById('lang-ms');

            if (currentLang === 'en') {
                if(btnEn) btnEn.className = "px-2.5 py-1 text-[10px] font-bold rounded bg-[#10b981] text-white shadow-sm transition-all";
                if(btnMs) btnMs.className = "px-2.5 py-1 text-[10px] font-bold rounded text-gray-400 hover:text-white transition-all";
                
                document.querySelectorAll('.translation-target').forEach(el => {
                    const text = el.textContent.trim();
                    if (text && enDictionary[text]) {
                        el.textContent = enDictionary[text];
                    }
                });

                document.querySelectorAll('.db-translate').forEach(el => {
                    el.textContent = el.getAttribute('data-en') || el.getAttribute('data-ms');
                });
            } else {
                if(btnMs) btnMs.className = "px-2.5 py-1 text-[10px] font-bold rounded bg-[#10b981] text-white shadow-sm transition-all";
                if(btnEn) btnEn.className = "px-2.5 py-1 text-[10px] font-bold rounded text-gray-400 hover:text-white transition-all";
                
                document.querySelectorAll('.db-translate').forEach(el => {
                    el.textContent = el.getAttribute('data-ms');
                });
            }
        }

        window.switchLanguage = function(lang) {
            localStorage.setItem('lang', lang);
            window.location.reload(); 
        }

        const body = document.body;
        const themeToggle = document.getElementById('theme-toggle');
        const toggleCircle = document.getElementById('toggle-circle');
        
        const moonIcon = '<svg class="w-3 h-3 text-gray-800" fill="currentColor" viewBox="0 0 20 20"><path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path></svg>';
        const sunIcon = '<svg class="w-4 h-4 text-yellow-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" clip-rule="evenodd"></path></svg>';

        function getMermaidConfig() {
            const isLight = body.classList.contains('light-mode');
            return {
                startOnLoad: false,
                theme: 'base',
                securityLevel: 'loose',
                flowchart: {
                    htmlLabels: true,
                    useMaxWidth: false,
                    nodeSpacing: 100, 
                    rankSpacing: 150  
                },
                themeVariables: {
                    primaryColor: isLight ? '#fef08a' : '#3f2c09',           
                    primaryTextColor: isLight ? '#78350f' : '#f0d56f',       
                    primaryBorderColor: isLight ? '#b45309' : '#d4af37',     
                    fontFamily: "'Segoe UI', sans-serif"
                }
            };
        }

        let panZoomInstance = null;

        async function renderAndAttachPanZoom() {
            const container = document.getElementById('map-container');
            
            const dataId = currentLang === 'en' ? 'raw-mermaid-data-en' : 'raw-mermaid-data-ms';
            const data = document.getElementById(dataId).textContent.trim();
            const uniqueId = `mermaid-${Date.now()}`;
            
            mermaid.initialize(getMermaidConfig());
            
            try {
                if(panZoomInstance) {
                    panZoomInstance.dispose();
                    panZoomInstance = null;
                }

                const { svg } = await mermaid.render(uniqueId, data, container);
                container.innerHTML = svg;

                const svgElement = container.querySelector('svg');
                if (svgElement && typeof window.panzoom === 'function') {
                    panZoomInstance = window.panzoom(svgElement, {
                        minZoom: 0.1, maxZoom: 10, smoothScroll: true,
                        transformOrigin: { x: 0.5, y: 0.5 },
                    });
                }
            } catch (error) {
                console.error('Mermaid rendering error:', error);
                container.innerHTML = `<p class="text-red-500 font-bold p-6">Gagal memuatkan peta visual penuh: Sila semak struktur soalan anda.</p>`;
            }
        }

        if (body.classList.contains('light-mode')) {
            themeToggle.checked = true;
            toggleCircle.innerHTML = sunIcon;
        } else {
            toggleCircle.innerHTML = moonIcon;
        }

        themeToggle.addEventListener('change', () => {
            if (themeToggle.checked) {
                body.classList.add('light-mode');
                localStorage.setItem('theme', 'light');
                toggleCircle.innerHTML = sunIcon;
            } else {
                body.classList.remove('light-mode');
                localStorage.setItem('theme', 'dark');
                toggleCircle.innerHTML = moonIcon;
            }
            renderAndAttachPanZoom();
        });

        document.addEventListener('DOMContentLoaded', () => {
            applyTranslation();
            renderAndAttachPanZoom();
            setTimeout(() => { document.body.classList.remove('preload'); }, 100);
            
            setTimeout(() => {
                const ins = document.getElementById('instruction');
                if(ins) { ins.style.opacity = '0'; }
            }, 5000);
        });
    </script>
    @include('partials.cursor')
</body>
</html>