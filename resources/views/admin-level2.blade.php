<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>S.H.I.E.L.D // Level 02 Configuration</title>
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
        .light-mode .lz-input:focus { border-color: #3b82f6; box-shadow: 0 0 10px rgba(59, 130, 246, 0.3); }
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
            background-color: #0a0f1a; border: 1px solid #1e3a8a; 
            color: #ffffff; width: 100%; padding: 14px 16px; border-radius: 4px;
            font-family: 'Share Tech Mono', monospace; font-size: 1rem; font-weight: bold;
            transition: all 0.2s;
        }
        .lz-input:focus { outline: none; border-color: #3b82f6; box-shadow: 0 0 10px rgba(59, 130, 246, 0.2); }
        
        .lz-radio {
            appearance: none; width: 20px; height: 20px; border: 2px solid #1e3a8a;
            border-radius: 50%; outline: none; cursor: pointer; transition: all 0.2s ease-in-out;
            position: relative; background: #000;
        }
        .lz-radio:checked { border-color: #3b82f6; box-shadow: 0 0 10px rgba(59, 130, 246, 0.5); }
        .lz-radio:checked::before {
            content: ''; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);
            width: 10px; height: 10px; border-radius: 50%; background-color: #3b82f6;
        }

        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: rgba(0,0,0,0.2); }
        ::-webkit-scrollbar-thumb { background: rgba(59,130,246,0.5); border-radius: 3px; }
    </style>
</head>
<body class="min-h-screen relative overflow-x-hidden pb-10">

    <div class="fixed inset-0 z-0 overflow-hidden">
        <video autoplay loop muted playsinline class="theme-video w-full h-full object-cover">
            <source src="{{ asset('video/videoplayback.webm') }}" type="video/webm">
        </video>
        <div class="absolute inset-0 bg-blue-900 mix-blend-color opacity-30 pointer-events-none"></div>
        <div class="theme-overlay absolute inset-0 pointer-events-none"></div>
    </div>

    <div class="fixed top-4 right-4 z-50 flex gap-3 sc-anim sc-in-right d-5">
        <button id="lang-toggle" class="theme-card backdrop-blur border border-blue-500/50 px-3 py-1.5 rounded-full text-xs font-bold tracking-wider hover:bg-blue-500 hover:text-white transition-colors text-blue-400 shadow-[0_0_10px_rgba(59,130,246,0.3)]">
            🇲🇾 BAHASA
        </button>
        <button id="theme-toggle" class="theme-card backdrop-blur border border-blue-500/50 px-3 py-1.5 rounded-full text-xs font-bold tracking-wider hover:bg-blue-500 hover:text-white transition-colors text-blue-400 shadow-[0_0_10px_rgba(59,130,246,0.3)]">
            ☀️ LIGHT MODE
        </button>
    </div>

    <div class="max-w-7xl mx-auto relative z-10 p-4 md:p-8 mt-4 md:mt-0">
        
        <header class="flex flex-col md:flex-row justify-between items-start md:items-end mb-8 pb-4 border-b border-blue-500/30 sc-anim sc-in-left d-1">
            <div class="flex items-center gap-5">
                <div class="w-16 h-16 rounded-full bg-blue-600 flex items-center justify-center shadow-[0_0_20px_rgba(37,99,235,0.6)]">
                    <span class="font-bold text-2xl text-white">02</span>
                </div>
                <div>
                    <h1 class="theme-title title-font text-3xl tracking-wider uppercase mb-1 text-blue-500" data-en="THE BRUTE FORCE GATE" data-ms="PINTU BRUTE FORCE">THE BRUTE FORCE GATE</h1>
                    <p class="text-blue-400 font-bold tracking-widest text-sm uppercase" data-en="Level Content Configuration" data-ms="Konfigurasi Kandungan Tahap">Level Content Configuration</p>
                </div>
            </div>
            
            <a href="{{ route('admin.levels') }}" class="nav-trigger mt-4 md:mt-0 px-5 py-2 rounded-full theme-card border border-blue-500/50 text-blue-400 hover:bg-blue-500 hover:text-white transition-colors text-xs font-bold tracking-widest uppercase flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                <span data-en="Back to Hub" data-ms="Kembali ke Hab">Back to Hub</span>
            </a>
        </header>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <div class="sc-anim sc-in-left d-2">
                <div class="theme-card backdrop-blur-md rounded-lg p-6 border border-blue-900/50 shadow-[0_0_20px_rgba(30,58,138,0.2)]">
                    
                    <div class="flex items-center text-blue-400 text-sm mb-5 border-b border-blue-900/50 pb-3 font-bold tracking-widest uppercase">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                        <span id="form-header-text" data-en="New Objective Protocol" data-ms="Protokol Objektif Baru">New Objective Protocol</span>
                    </div>

                    @if(session('success'))
                        <div class="bg-blue-500/20 border border-blue-500 text-blue-400 px-4 py-2 rounded mb-4 text-xs font-bold uppercase tracking-widest">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form id="level2-form" action="{{ route('admin.level2.store') }}" method="POST" class="space-y-4">
                        @csrf
                        
                        <div>
                            <input type="text" id="category" name="category" class="lz-input" placeholder="Kategori (contoh: password, web, dll)">
                        </div>

                        <div>
                            <textarea id="question" name="question" class="lz-input h-32 resize-none" placeholder="Soalan / Question" required></textarea>
                        </div>

                        <div class="space-y-4 pt-2">
                            <p class="text-[10px] text-blue-500 font-bold tracking-widest uppercase opacity-70" data-en="Mark correct answer with radio button" data-ms="Tandakan jawapan betul dengan butang radio">Mark correct answer with radio button</p>
                            
                            <div class="flex items-center gap-3">
                                <input type="text" id="option_1" name="option_1" class="lz-input flex-1" placeholder="Pilihan / Option 1" required>
                                <input type="radio" name="correct_answer" value="1" class="lz-radio" required>
                            </div>
                            
                            <div class="flex items-center gap-3">
                                <input type="text" id="option_2" name="option_2" class="lz-input flex-1" placeholder="Pilihan / Option 2" required>
                                <input type="radio" name="correct_answer" value="2" class="lz-radio" required>
                            </div>

                            <div class="flex items-center gap-3">
                                <input type="text" id="option_3" name="option_3" class="lz-input flex-1" placeholder="Pilihan / Option 3" required>
                                <input type="radio" name="correct_answer" value="3" class="lz-radio" required>
                            </div>

                            <div class="flex items-center gap-3">
                                <input type="text" id="option_4" name="option_4" class="lz-input flex-1" placeholder="Pilihan / Option 4" required>
                                <input type="radio" name="correct_answer" value="4" class="lz-radio" required>
                            </div>
                        </div>

                        <div class="flex gap-3 pt-4 border-t border-blue-900/50 mt-4">
                            <button type="submit" id="submit-btn" class="flex-1 bg-blue-900 hover:bg-blue-700 text-blue-300 hover:text-white border border-blue-700 font-bold py-3 px-6 rounded transition-colors text-sm shadow-[0_0_10px_rgba(59,130,246,0.3)]">
                                <span data-en="Save" data-ms="Simpan">Save</span>
                            </button>
                            <button type="button" onclick="resetForm()" class="bg-red-900/30 hover:bg-red-900/60 text-red-500 border border-red-900/50 font-bold py-3 px-6 rounded transition-colors text-sm">
                                <span data-en="Cancel" data-ms="Batal">Cancel</span>
                            </button>
                        </div>
                    </form>

                </div>
            </div>

            <div class="lg:col-span-2 sc-anim sc-in-right d-3">
                <div class="theme-card backdrop-blur-md rounded-lg p-6 border border-blue-500/40 shadow-[0_0_25px_rgba(59,130,246,0.15)] h-full max-h-[820px] flex flex-col">
                    <div class="flex justify-between items-center mb-5 border-b border-blue-500/20 pb-3 shrink-0">
                        <div class="flex items-center text-blue-400 text-sm font-bold tracking-widest uppercase">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4" /></svg>
                            <span data-en="Active Question Database" data-ms="Pangkalan Data Soalan Aktif">Active Question Database</span>
                        </div>
                    </div>

                    <div class="overflow-x-auto overflow-y-auto flex-1 min-h-0 pr-2">
                        <table class="w-full text-left min-w-full">
                            <thead class="uppercase tracking-widest text-xs text-white border-b border-blue-500/30 sticky top-0 bg-black/90 backdrop-blur z-10">
                                <tr>
                                    <th class="px-4 py-4" data-en="Category" data-ms="Kategori">Category</th>
                                    <th class="px-4 py-4 w-1/2" data-en="Question" data-ms="Soalan">Question</th>
                                    <th class="px-4 py-4 text-center whitespace-nowrap" data-en="Correct Ans" data-ms="Jawapan Betul">Correct Ans</th>
                                    <th class="px-4 py-4 text-right whitespace-nowrap" data-en="Action" data-ms="Tindakan">Action</th>
                                </tr>
                            </thead>
                            <tbody class="font-mono text-base font-bold opacity-90 divide-y divide-blue-500/10">
                                
                                @forelse($questions as $q)
                                <tr class="hover:bg-blue-900/20 transition-colors">
                                    <td class="px-4 py-4 text-blue-400 font-bold uppercase text-xs tracking-widest">{{ $q->category ?? 'General' }}</td>
                                    
                                    <td class="px-4 py-4 text-gray-300 truncate max-w-[250px] dynamic-q" data-original="{{ $q->question }}">{{ $q->question }}</td>
                                    
                                    <td class="px-4 py-4 text-center whitespace-nowrap">
                                        <span class="bg-blue-900/50 text-blue-300 px-3 py-1.5 rounded text-xs uppercase font-extrabold border border-blue-500/50 whitespace-nowrap">Option {{ $q->correct_answer }}</span>
                                    </td>
                                    
                                    <td class="px-4 py-4 text-right">
                                        <div class="flex justify-end gap-2 flex-nowrap whitespace-nowrap">
                                            <button type="button" onclick='editQuestion(@json($q))' class="text-blue-500 hover:text-blue-400 text-xs font-bold uppercase tracking-widest bg-blue-900/20 px-3 py-1.5 rounded border border-blue-900/50 cursor-pointer transition-colors">
                                                <span data-en="Edit" data-ms="Sunting">Edit</span>
                                            </button>

                                            <form action="{{ route('admin.level2.destroy', $q->id) }}" method="POST" onsubmit="return confirm('Delete this question from the database?');" class="inline-block m-0">
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
                                    <td colspan="4" class="px-4 py-10 text-center text-gray-500 italic text-sm font-bold" data-en="No objective questions found in database." data-ms="Tiada soalan objektif dijumpai di pangkalan data.">No objective questions found in database.</td>
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
                return data[0][0][0]; // Extracts the translated text
            } catch(e) {
                console.error("Translation Failed:", e);
                return text; // Fallback
            }
        }

        async function processDynamicTranslations(lang) {
            const questionRows = document.querySelectorAll('.dynamic-q');
            
            const translateRows = async (rows) => {
                for(let row of rows) {
                    let originalText = row.getAttribute('data-original');
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
            };
            
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

        if (currentTheme === 'light') {
            bodyEl.classList.add('light-mode');
            if(themeBtn) themeBtn.innerHTML = '🌙 DARK MODE';
        }

        function applyLanguage(lang) {
            if(langBtn) langBtn.innerHTML = lang === 'en' ? '🇲🇾 BAHASA' : '🇬🇧 ENGLISH';
            
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

            // Fire the Google API
            processDynamicTranslations(lang);
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

        // --- 4. LEVEL 2 FORM LOGIC ---
        function editQuestion(q) {
            const form = document.getElementById('level2-form');
            form.action = `/admin/level2/update/${q.id}`;

            document.getElementById('category').value = q.category || '';
            document.getElementById('question').value = q.question || '';
            document.getElementById('option_1').value = q.option_1 || '';
            document.getElementById('option_2').value = q.option_2 || '';
            document.getElementById('option_3').value = q.option_3 || '';
            document.getElementById('option_4').value = q.option_4 || '';

            document.querySelector(`input[name="correct_answer"][value="${q.correct_answer}"]`).checked = true;

            document.getElementById('submit-btn').innerHTML = currentLang === 'ms' ? '<span data-en="Update" data-ms="Kemaskini">Kemaskini</span>' : '<span data-en="Update" data-ms="Kemaskini">Update</span>';
            document.getElementById('form-header-text').innerHTML = `Edit Protocol (ID: ${q.id})`;

            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        function resetForm() {
            const form = document.getElementById('level2-form');
            form.reset(); 
            form.action = "{{ route('admin.level2.store') }}";
            
            document.getElementById('submit-btn').innerHTML = currentLang === 'ms' ? '<span data-en="Save" data-ms="Simpan">Simpan</span>' : '<span data-en="Save" data-ms="Simpan">Save</span>';
            document.getElementById('form-header-text').innerHTML = currentLang === 'ms' ? '<span data-en="New Objective Protocol" data-ms="Protokol Objektif Baru">Protokol Objektif Baru</span>' : '<span data-en="New Objective Protocol" data-ms="Protokol Objektif Baru">New Objective Protocol</span>';
        }
    </script>
   <script>
    // Tell the parent wrapper to ensure music is playing
    // If it's already playing, the wrapper will ignore this and let it loop seamlessly.
    window.addEventListener('DOMContentLoaded', () => {
        setTimeout(() => {
            window.parent.postMessage('ensureMusicPlaying', '*');
        }, 100);
    });
</script>
@include('partials.cursor')
</body>
</html>