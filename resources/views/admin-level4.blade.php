<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>S.H.I.E.L.D // Level 04 Configuration</title>
    @vite('resources/css/app.css')
    <link href="https://fonts.googleapis.com/css2?family=Anton&family=Poppins:wght@700&family=Share+Tech+Mono&display=swap" rel="stylesheet">
    
    <style>
        html, body { overflow-x: hidden; }
        body { font-family: 'Share Tech Mono', monospace; }
        .title-font { font-family: 'Poppins', sans-serif; font-weight: 700; text-shadow: 0 0 10px rgba(168,85,247,0.7); }
        .scanlines { background: linear-gradient(to bottom, rgba(255,255,255,0), rgba(255,255,255,0) 50%, rgba(0,0,0,0.1) 50%, rgba(0,0,0,0.1)); background-size: 100% 4px; }

        :root {
            --bg-color: #000000; --text-color: #d1d5db; --vid-opacity: 0.8;
            --vid-overlay: linear-gradient(to bottom, rgba(0,0,0,0.85), rgba(0,0,0,0.4), rgba(0,0,0,0.95));
            --card-bg: rgba(15, 10, 20, 0.85);
            --title-color: #ffffff;
        }

        /* --- UNIVERSAL LIGHT MODE OVERRIDES --- */
        .light-mode {
            --bg-color: #f8fafc;
            --text-color: #0f172a;
            --vid-opacity: 0.15; 
            --vid-overlay: linear-gradient(to bottom, rgba(255,255,255,0.7), rgba(255,255,255,0.4), rgba(255,255,255,0.8)); 
            --card-bg: rgba(255, 255, 255, 0.65); 
            --title-color: #1e293b;
        }
        .light-mode .lz-input { background-color: #ffffff; color: #0f172a; border-color: #94a3b8; }
        .light-mode .lz-input:focus { border-color: #06b6d4; box-shadow: 0 0 10px rgba(6, 182, 212, 0.3); }
        .light-mode table { color: #0f172a; }
        
        .light-mode th { border-bottom-color: #cbd5e1; color: #ffffff; }
        
        .light-mode td { border-color: #e2e8f0; }
        .light-mode .text-gray-300, .light-mode .text-gray-400, .light-mode .text-gray-500 { color: #334155; }
        
        body { background-color: var(--bg-color); color: var(--text-color); transition: background-color 0.3s ease; }
        .theme-video { opacity: var(--vid-opacity); }
        .theme-overlay { background: var(--vid-overlay); }
        .theme-card { background: var(--card-bg) !important; transition: background 0.3s ease; }
        .theme-title { color: var(--title-color) !important; }
        
        /* SHAKE-FREE ANIMATIONS */
        .sc-anim { animation-fill-mode: both; transform: translate3d(0,0,0); }
        .sc-in-left { animation: slideInLeft 0.6s cubic-bezier(0.16, 1, 0.3, 1); } 
        .sc-in-right { animation: slideInRight 0.6s cubic-bezier(0.16, 1, 0.3, 1); } 
        
        .d-1 { animation-delay: 0.1s; } .d-2 { animation-delay: 0.2s; } .d-3 { animation-delay: 0.3s; }

        @keyframes slideInLeft { 0% { transform: translateX(-100vw); opacity: 0; } 100% { transform: translateX(0); opacity: 1; } }
        @keyframes slideInRight { 0% { transform: translateX(100vw); opacity: 0; } 100% { transform: translateX(0); opacity: 1; } }

        body.exiting .sc-anim {
            animation: none !important; 
            transition: opacity 0.5s ease-out, transform 0.5s cubic-bezier(0.7, 0, 0.84, 0) !important;
            opacity: 0 !important; pointer-events: none;
        }
        body.exiting .sc-in-left { transform: translateX(-100vw) !important; }
        body.exiting .sc-in-right { transform: translateX(100vw) !important; }

        /* Custom Form Input Styling */
        .lz-input {
            background-color: #001a1a; border: 1px solid #0891b2; 
            color: #ffffff; 
            width: 100%; 
            padding: 14px 16px; 
            border-radius: 4px;
            font-family: 'Share Tech Mono', monospace; 
            font-size: 1rem; 
            font-weight: bold;
            transition: all 0.2s;
        }
        .lz-input:focus { outline: none; border-color: #06b6d4; box-shadow: 0 0 10px rgba(6, 182, 212, 0.3); }

        /* File Input Styling */
        input[type=file]::file-selector-button {
            border: 1px solid #0891b2; background: #164e63; color: #67e8f9; padding: 6px 12px;
            border-radius: 4px; cursor: pointer; transition: all 0.2s; font-family: 'Share Tech Mono', monospace; text-transform: uppercase; font-size: 10px;
        }
        input[type=file]::file-selector-button:hover { background: #0891b2; color: #000; }
        .light-mode input[type=file]::file-selector-button { background: #e0f2fe; color: #0f172a; border-color: #38bdf8; }
        .light-mode input[type=file]::file-selector-button:hover { background: #bae6fd; }

        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: rgba(0,0,0,0.2); }
        ::-webkit-scrollbar-thumb { background: rgba(6,182,212,0.5); border-radius: 3px; }
    </style>
</head>
<body class="min-h-screen relative overflow-x-hidden pb-10">

    <div class="fixed inset-0 z-0 overflow-hidden">
        <video autoplay loop muted playsinline class="theme-video w-full h-full object-cover">
            <source src="{{ asset('video/videoplayback.webm') }}" type="video/webm">
        </video>
        <div class="absolute inset-0 bg-cyan-900 mix-blend-color opacity-30 pointer-events-none"></div>
        <div class="theme-overlay absolute inset-0 pointer-events-none"></div>
    </div>

    <div class="fixed top-4 right-4 z-50 flex gap-3 sc-anim sc-in-right d-5">
        <button id="lang-toggle" class="theme-card backdrop-blur border border-cyan-500/50 px-3 py-1.5 rounded-full text-xs font-bold tracking-wider hover:bg-cyan-500 hover:text-white transition-colors text-cyan-400 shadow-[0_0_10px_rgba(6,182,212,0.3)]">
            🇲🇾 BAHASA
        </button>
        <button id="theme-toggle" class="theme-card backdrop-blur border border-cyan-500/50 px-3 py-1.5 rounded-full text-xs font-bold tracking-wider hover:bg-cyan-500 hover:text-white transition-colors text-cyan-400 shadow-[0_0_10px_rgba(6,182,212,0.3)]">
            ☀️ LIGHT MODE
        </button>
    </div>

    <div class="max-w-7xl mx-auto relative z-10 p-4 md:p-8 mt-4 md:mt-0">
        
        <header class="flex flex-col md:flex-row justify-between items-start md:items-end mb-8 pb-4 border-b border-cyan-500/30 sc-anim sc-in-left d-1">
            <div class="flex items-center gap-5">
                <div class="w-16 h-16 rounded-full bg-cyan-600 flex items-center justify-center shadow-[0_0_20px_rgba(6,182,212,0.6)]">
                    <span class="font-bold text-2xl text-black">04</span>
                </div>
                <div>
                    <h1 class="theme-title title-font text-3xl tracking-wider uppercase mb-1 text-cyan-500" data-en="THE MIRROR WEB" data-ms="WEB CERMIN">THE MIRROR WEB</h1>
                    <p class="text-cyan-400 font-bold tracking-widest text-sm uppercase" data-en="Website Analysis Engine" data-ms="Enjin Analisis Laman Web">Website Analysis Engine</p>
                </div>
            </div>
            
            <a href="{{ route('admin.levels') }}" class="nav-trigger mt-4 md:mt-0 px-5 py-2 rounded-full theme-card border border-cyan-500/50 text-cyan-400 hover:bg-cyan-500 hover:text-white transition-colors text-xs font-bold tracking-widest uppercase flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                <span data-en="Back to Hub" data-ms="Kembali ke Hab">Back to Hub</span>
            </a>
        </header>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <div class="sc-anim sc-in-left d-2">
                <div class="theme-card backdrop-blur-md rounded-lg p-6 border border-cyan-900/50 shadow-[0_0_20px_rgba(8,145,178,0.3)]">
                    
                    <div class="flex items-center text-cyan-400 text-sm mb-5 border-b border-cyan-900/50 pb-3 font-bold tracking-widest uppercase">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" /></svg>
                        <span id="form-header-text" data-en="New Site Mockup" data-ms="Mockup Laman Baru">New Site Mockup</span>
                    </div>

                    @if(session('success'))
                        <div class="bg-cyan-500/20 border border-cyan-500 text-cyan-400 px-4 py-2 rounded mb-4 text-xs font-bold uppercase tracking-widest">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if($errors->any())
                        <div class="bg-red-500/20 border border-red-500 text-red-400 px-4 py-2 rounded mb-4 text-xs font-bold uppercase tracking-widest" data-en="Failed: Check file size (Max 30MB) or required fields." data-ms="Gagal: Periksa saiz fail (Maks 30MB) atau medan wajib.">
                            Failed: Check file size (Max 30MB) or required fields.
                        </div>
                    @endif

                    <form id="level4-form" action="{{ route('admin.level4.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                        @csrf
                        
                        <div>
                            <p class="text-[10px] text-cyan-500 mb-1 uppercase tracking-widest font-bold" data-en="Website URL" data-ms="URL Laman">Website URL</p>
                            <input type="text" id="url" name="url" class="lz-input" placeholder="https://example.com" required>
                        </div>

                        <div>
                            <p class="text-[10px] text-cyan-500 mb-1 uppercase tracking-widest font-bold" data-en="Page Title" data-ms="Tajuk Laman">Page Title</p>
                            <input type="text" id="title" name="title" class="lz-input" placeholder="Login | Secure Portal" required>
                        </div>

                        <div>
                            <p class="text-[10px] text-cyan-500 mb-1 uppercase tracking-widest font-bold" data-en="Clues (Bullet Points)" data-ms="Petunjuk Laman (Bullet Points)">Clues (Bullet Points)</p>
                            <textarea id="clues" name="clues" class="lz-input h-24 resize-none" placeholder="Tanda-tanda laman pancingan data..." required></textarea>
                        </div>

                        <div>
                            <p class="text-[10px] text-cyan-500 mb-1 uppercase tracking-widest font-bold" data-en="Feedback / Explanation" data-ms="Maklum Balas / Penerangan">Feedback / Explanation</p>
                            <textarea id="explanation" name="explanation" class="lz-input h-24 resize-none" placeholder="Penerangan terperinci untuk ejen selepas menjawab..." required></textarea>
                        </div>

                        <div class="border border-cyan-900/50 p-3 rounded bg-black/30">
                            <label class="block text-[10px] text-cyan-500 mb-2 uppercase tracking-widest font-bold" data-en="Upload Mockup Image" data-ms="Muat Naik Imej Mockup">Upload Mockup Image</label>
                            <input type="file" id="image" name="image" accept="image/*" class="w-full text-xs text-gray-400" required>
                            <p id="edit-img-hint" class="text-[9px] text-yellow-500 mt-2 hidden uppercase tracking-widest font-bold" data-en="Leave empty to keep existing image" data-ms="Biarkan kosong untuk kekalkan imej sedia ada">Leave empty to keep existing image</p>
                        </div>

                        <div class="flex items-center gap-6 pt-2">
                            <div class="flex items-center gap-2">
                                <input type="checkbox" name="has_ssl" id="has_ssl" class="w-4 h-4 accent-cyan-600 bg-black border-cyan-900 rounded cursor-pointer">
                                <label for="has_ssl" class="text-sm text-gray-300 font-bold uppercase tracking-widest" data-en="Valid SSL" data-ms="SSL Sah">Valid SSL</label>
                            </div>
                            <div class="flex items-center gap-2">
                                <input type="checkbox" name="is_phishing" id="is_phishing" class="w-4 h-4 accent-red-600 bg-black border-red-900 rounded cursor-pointer">
                                <label for="is_phishing" class="text-sm text-red-400 font-bold uppercase tracking-widest" data-en="Phishing Site" data-ms="Laman Phishing">Phishing Site</label>
                            </div>
                        </div>

                        <div class="flex gap-3 pt-4 border-t border-cyan-900/50 mt-4">
                            <button type="submit" id="submit-btn" class="flex-1 bg-cyan-900 hover:bg-cyan-700 text-cyan-300 hover:text-white border border-cyan-700 font-bold py-3 px-6 rounded transition-colors text-sm shadow-[0_0_10px_rgba(8,145,178,0.3)]">
                                <span data-en="Upload" data-ms="Muat Naik">Upload</span>
                            </button>
                            <button type="button" onclick="resetForm()" class="bg-red-900/30 hover:bg-red-900/60 text-red-500 border border-red-900/50 font-bold py-3 px-6 rounded transition-colors text-sm">
                                <span data-en="Cancel" data-ms="Batal">Cancel</span>
                            </button>
                        </div>
                    </form>

                </div>
            </div>

            <div class="lg:col-span-2 sc-anim sc-in-right d-3">
                <div class="theme-card backdrop-blur-md rounded-lg p-6 border border-cyan-500/40 shadow-[0_0_25px_rgba(8,145,178,0.15)] h-full max-h-[820px] flex flex-col">
                    <div class="flex justify-between items-center mb-5 border-b border-cyan-500/20 pb-3 shrink-0">
                        <div class="flex items-center text-cyan-400 text-sm font-bold tracking-widest uppercase">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                            <span data-en="Active Mockup Gallery" data-ms="Galeri Mockup Aktif">Active Mockup Gallery</span>
                        </div>
                    </div>

                    <div class="overflow-x-auto overflow-y-auto flex-1 min-h-0 pr-2">
                        <table class="w-full text-left min-w-full">
                            <thead class="uppercase tracking-widest text-xs text-white border-b border-cyan-500/30 sticky top-0 bg-black/90 backdrop-blur z-10">
                                <tr>
                                    <th class="px-4 py-4" data-en="Preview" data-ms="Pratonton">Preview</th>
                                    <th class="px-4 py-4" data-en="Title & URL" data-ms="Tajuk & URL">Title & URL</th>
                                    <th class="px-4 py-4" data-en="Clues & Feedback" data-ms="Petunjuk & Maklum Balas">Clues & Feedback</th>
                                    <th class="px-4 py-4 text-center whitespace-nowrap" data-en="Status" data-ms="Status">Status</th>
                                    <th class="px-4 py-4 text-right whitespace-nowrap" data-en="Action" data-ms="Tindakan">Action</th>
                                </tr>
                            </thead>
                            <tbody class="font-mono text-base font-bold opacity-90 divide-y divide-cyan-500/10">
                                
                                @forelse($scenarios as $s)
                                <tr class="hover:bg-cyan-900/20 transition-colors">
                                    <td class="px-4 py-4">
                                        <img src="{{ Storage::url($s->image_path) }}" alt="Preview" class="h-14 w-20 object-cover rounded border border-cyan-500/30 shadow-[0_0_10px_rgba(8,145,178,0.2)]">
                                    </td>
                                    <td class="px-4 py-4">
                                        <div class="text-cyan-400 font-bold uppercase text-xs tracking-widest truncate max-w-[150px]">{{ $s->title }}</div>
                                        <div class="text-gray-400 text-[10px] truncate max-w-[150px] mt-1">{{ $s->url }}</div>
                                    </td>
                                    
                                    <td class="px-4 py-4 text-gray-300">
                                        <div class="truncate max-w-[200px] mb-1">
                                            <span class="text-cyan-500 text-[10px]">CLUE:</span> 
                                            <span class="dynamic-q" data-original="{{ $s->clues }}">{{ $s->clues }}</span>
                                        </div>
                                        <div class="truncate max-w-[200px]">
                                            <span class="text-yellow-500 text-[10px]">FB:</span> 
                                            <span class="dynamic-q" data-original="{{ $s->explanation }}">{{ $s->explanation ?? 'N/A' }}</span>
                                        </div>
                                    </td>
                                    
                                    <td class="px-4 py-4 text-center whitespace-nowrap">
                                        @if($s->is_phishing)
                                            <span class="bg-red-900/50 text-red-400 px-3 py-1.5 rounded text-xs uppercase font-extrabold border border-red-500/50">Phishing</span>
                                        @else
                                            <span class="bg-emerald-900/50 text-emerald-400 px-3 py-1.5 rounded text-xs uppercase font-extrabold border border-emerald-500/50" data-en="Safe" data-ms="Selamat">Safe</span>
                                        @endif
                                        @if($s->has_ssl)
                                            <span class="ml-2 text-emerald-500 text-lg" title="Has SSL">🔒</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-4 text-right">
                                        <div class="flex justify-end gap-2 items-center flex-nowrap whitespace-nowrap">
                                            <button type="button" onclick='editScenario(@json($s))' class="text-blue-400 hover:text-blue-300 text-xs font-bold uppercase tracking-widest bg-blue-900/20 px-3 py-1.5 rounded border border-blue-900/50 cursor-pointer transition-colors">
                                                <span data-en="Edit" data-ms="Sunting">Edit</span>
                                            </button>

                                            <form action="{{ route('admin.level4.destroy', $s->id) }}" method="POST" onsubmit="return confirm('Delete this mockup permanently?');" class="inline-block m-0">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-500 hover:text-red-400 text-xs font-bold uppercase tracking-widest bg-red-900/20 px-3 py-1.5 rounded border border-red-900/50 cursor-pointer transition-colors">
                                                    <span data-en="Delete" data-ms="Padam">Delete</span>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-10 text-center text-gray-500 italic text-sm font-bold" data-en="No web mockups found in database." data-ms="Tiada mockup web dijumpai di pangkalan data.">No web mockups found in database.</td>
                                </tr>
                                @endforelse

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script>
        // --- 1. GOOGLE API TWO-WAY AUTO-TRANSLATOR ---
        async function translateGoogleAPI(text, targetLang) {
            let url = `https://translate.googleapis.com/translate_a/single?client=gtx&sl=auto&tl=${targetLang}&dt=t&q=${encodeURI(text)}`;
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
            const questionRows = document.querySelectorAll('.dynamic-q');
            
            const translateRows = async (rows) => {
                for(let row of rows) {
                    let originalText = row.getAttribute('data-original');
                    
                    if(!originalText || originalText === '--' || originalText === 'N/A') continue;

                    let cacheKey = `data-cache-${lang}`;
                    let cachedText = row.getAttribute(cacheKey);
                    
                    if(cachedText) {
                        row.innerText = cachedText;
                    } else {
                        row.innerText = "Translating..."; 
                        let translation = await translateGoogleAPI(originalText, lang);
                        row.setAttribute(cacheKey, translation); 
                        row.innerText = translation;
                    }
                }
            }
            await translateRows(questionRows);
        }

        // --- 2. ANIMATION LOGIC ---
        window.addEventListener('DOMContentLoaded', () => {
            setTimeout(() => { document.body.classList.add('loaded'); }, 50); 
        });

        document.querySelectorAll('.nav-trigger').forEach(trigger => {
            trigger.addEventListener('click', function(e) {
                e.preventDefault(); 
                const targetUrl = this.getAttribute('href');
                document.body.classList.remove('loaded');
                document.body.classList.add('exiting');
                setTimeout(() => { window.location.href = targetUrl; }, 500); 
            });
        });

        // --- 3. THEME & LANGUAGE LOGIC ---
        const bodyEl = document.body;
        const themeBtn = document.getElementById('theme-toggle');
        const langBtn = document.getElementById('lang-toggle');
        const translatables = document.querySelectorAll('[data-en]');

        let currentTheme = localStorage.getItem('shield_theme') || 'dark';
        let currentLang = localStorage.getItem('shield_lang') || 'en';

        // Apply Theme on Load
        if (currentTheme === 'light') {
            bodyEl.classList.add('light-mode');
            if(themeBtn) themeBtn.innerHTML = '🌙 DARK MODE';
        }

        // Apply Language function (Updated for Two-Way API translation)
        function applyLanguage(lang) {
            if(langBtn) langBtn.innerHTML = lang === 'en' ? '🇲🇾 BAHASA' : '🇬🇧 ENGLISH';
            
            // Static translations
            translatables.forEach(el => {
                if(el.getAttribute(`data-${lang}`)) {
                    const text = el.getAttribute(`data-${lang}`);
                    if(el.children.length > 0) {
                        Array.from(el.childNodes).forEach(child => {
                            if(child.nodeType === Node.TEXT_NODE && child.textContent.trim() !== '') {
                                child.textContent = ' ' + text;
                            }
                        });
                        const innerSpan = el.querySelector('span:not([data-en])');
                        if (innerSpan) innerSpan.innerText = text;
                    } else {
                        el.innerText = text;
                    }
                }
            });

            // Fire the Two-Way Google API for dynamic clues
            processDynamicTranslations(lang);
        }
        
        applyLanguage(currentLang); // Run on boot

        // Theme Toggle Click Event
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

        // Language Toggle Click Event
        if(langBtn) {
            langBtn.addEventListener('click', () => {
                currentLang = currentLang === 'en' ? 'ms' : 'en';
                localStorage.setItem('shield_lang', currentLang);
                applyLanguage(currentLang);
            });
        }

        // --- 4. LEVEL 4 FORM LOGIC ---
        function editScenario(s) {
            const form = document.getElementById('level4-form');
            form.action = `/admin/level4/update/${s.id}`;

            document.getElementById('url').value = s.url || '';
            document.getElementById('title').value = s.title || '';
            document.getElementById('clues').value = s.clues || '';
            
            // 🔥 POPULATE NEW EXPLANATION FIELD 🔥
            document.getElementById('explanation').value = s.explanation || '';
            
            document.getElementById('has_ssl').checked = s.has_ssl ? true : false;
            document.getElementById('is_phishing').checked = s.is_phishing ? true : false;

            document.getElementById('image').removeAttribute('required');
            document.getElementById('edit-img-hint').classList.remove('hidden');

            document.getElementById('submit-btn').innerHTML = currentLang === 'ms' ? '<span data-en="Update" data-ms="Kemaskini">Kemaskini</span>' : '<span data-en="Update" data-ms="Kemaskini">Update</span>';
            document.getElementById('form-header-text').innerHTML = `Edit Mockup (ID: ${s.id})`;

            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        function resetForm() {
            const form = document.getElementById('level4-form');
            form.reset(); 
            form.action = "{{ route('admin.level4.store') }}";
            
            document.getElementById('image').setAttribute('required', 'required');
            document.getElementById('edit-img-hint').classList.add('hidden');

            document.getElementById('submit-btn').innerHTML = currentLang === 'ms' ? '<span data-en="Upload" data-ms="Muat Naik">Muat Naik</span>' : '<span data-en="Upload" data-ms="Muat Naik">Upload</span>';
            document.getElementById('form-header-text').innerHTML = currentLang === 'ms' ? '<span data-en="New Site Mockup" data-ms="Mockup Laman Baru">Mockup Laman Baru</span>' : '<span data-en="New Site Mockup" data-ms="Mockup Laman Baru">New Site Mockup</span>';
        }
    </script>
<script>
    window.addEventListener('DOMContentLoaded', () => {
        setTimeout(() => {
            window.parent.postMessage('ensureMusicPlaying', '*');
        }, 100);
    });
</script>
@include('partials.cursor')
</body>
</html>