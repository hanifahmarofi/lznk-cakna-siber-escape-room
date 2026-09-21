<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>S.H.I.E.L.D // Level 01 Configuration</title>
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
            --card-bg: rgba(15, 10, 20, 0.85); --card-hover: rgba(25, 15, 30, 0.9);
            --title-color: #ffffff;
        }

        /* --- UNIVERSAL LIGHT MODE OVERRIDES --- */
        .light-mode {
            --bg-color: #f3f4f6;
            --text-color: #111827;
            --vid-opacity: 0.1;
            --vid-overlay: linear-gradient(to bottom, rgba(255,255,255,0.9), rgba(240,240,240,0.8), rgba(255,255,255,0.95));
            --card-bg: rgba(255, 255, 255, 0.85);
            --title-color: #1f2937;
        }
        .light-mode .lz-input { background-color: #ffffff; color: #111827; border-color: #9ca3af; }
        .light-mode .lz-input:focus { border-color: #6366f1; box-shadow: 0 0 10px rgba(99, 102, 241, 0.3); }
        .light-mode table { color: #111827; }
        
        .light-mode th { border-bottom-color: #d1d5db; color: #ffffff; } 
        
        .light-mode td { border-color: #e5e7eb; }
        .light-mode .text-gray-300, .light-mode .text-gray-400, .light-mode .text-gray-500 { color: #374151; }

        body { background-color: var(--bg-color); color: var(--text-color); transition: background-color 0.3s ease; }
        .theme-video { opacity: var(--vid-opacity); }
        .theme-overlay { background: var(--vid-overlay); }
        .theme-card { background: var(--card-bg) !important; transition: background 0.3s ease; }
        .theme-title { color: var(--title-color) !important; }
        
        /* SHAKE-FREE ANIMATIONS */
        .sc-anim { animation-fill-mode: both; transform: translate3d(0,0,0); }
        .sc-in-left { animation: slideInLeft 0.6s cubic-bezier(0.16, 1, 0.3, 1); } 
        .sc-in-right { animation: slideInRight 0.6s cubic-bezier(0.16, 1, 0.3, 1); } 
        .sc-in-up { animation: slideInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1); }
        
        .d-1 { animation-delay: 0.1s; } .d-2 { animation-delay: 0.2s; } .d-3 { animation-delay: 0.3s; }

        @keyframes slideInLeft { 0% { transform: translateX(-100vw); opacity: 0; } 100% { transform: translateX(0); opacity: 1; } }
        @keyframes slideInRight { 0% { transform: translateX(100vw); opacity: 0; } 100% { transform: translateX(0); opacity: 1; } }
        @keyframes slideInUp { 0% { transform: translateY(100vh); opacity: 0; } 100% { transform: translateY(0); opacity: 1; } }

        body.exiting .sc-anim {
            animation: none !important; 
            transition: opacity 0.5s ease-out, transform 0.5s cubic-bezier(0.7, 0, 0.84, 0) !important;
            opacity: 0 !important; pointer-events: none;
        }
        body.exiting .sc-in-left { transform: translateX(-100vw) !important; }
        body.exiting .sc-in-right { transform: translateX(100vw) !important; }
        body.exiting .sc-in-up { transform: translateY(100vh) !important; }

        /* Custom Form Input Styling */
        .lz-input {
            background-color: #0a0a0a;
            border: 1px solid #065f46;
            color: #ffffff;
            width: 100%;
            padding: 14px 16px; 
            border-radius: 4px;
            font-family: 'Share Tech Mono', monospace;
            font-size: 1rem; 
            font-weight: bold;
            transition: all 0.2s;
        }
        .lz-input:focus { outline: none; border-color: #10b981; box-shadow: 0 0 10px rgba(16, 185, 129, 0.2); }
        
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: rgba(0,0,0,0.2); }
        ::-webkit-scrollbar-thumb { background: rgba(168,85,247,0.5); border-radius: 3px; }
    </style>
</head>
<body class="min-h-screen relative overflow-x-hidden pb-10">

    <div class="fixed inset-0 z-0 overflow-hidden">
        <video autoplay loop muted playsinline class="theme-video w-full h-full object-cover">
            <source src="{{ asset('video/videoplayback.webm') }}" type="video/webm">
        </video>
        <div class="absolute inset-0 bg-purple-900 mix-blend-color opacity-40 pointer-events-none"></div>
        <div class="theme-overlay absolute inset-0 pointer-events-none"></div>
    </div>

    <div class="fixed top-4 right-4 z-50 flex gap-3 sc-anim sc-in-right d-5">
        <button id="lang-toggle" class="theme-card backdrop-blur border border-purple-500/50 px-3 py-1.5 rounded-full text-xs font-bold tracking-wider hover:bg-purple-500 hover:text-white transition-colors text-purple-400 shadow-[0_0_10px_rgba(168,85,247,0.3)]">
            🇲🇾 BAHASA
        </button>
        <button id="theme-toggle" class="theme-card backdrop-blur border border-purple-500/50 px-3 py-1.5 rounded-full text-xs font-bold tracking-wider hover:bg-purple-500 hover:text-white transition-colors text-purple-400 shadow-[0_0_10px_rgba(168,85,247,0.3)]">
            ☀️ LIGHT MODE
        </button>
    </div>

    <div class="max-w-7xl mx-auto relative z-10 p-4 md:p-8 mt-4 md:mt-0">
        
        <header class="flex flex-col md:flex-row justify-between items-start md:items-end mb-8 pb-4 border-b border-purple-500/30 sc-anim sc-in-left d-1">
            <div class="flex items-center gap-5">
                <div class="w-16 h-16 rounded-full bg-purple-600 flex items-center justify-center shadow-[0_0_20px_rgba(168,85,247,0.6)]">
                    <span class="font-bold text-2xl text-white">01</span>
                </div>
                <div>
                    <h1 class="theme-title title-font text-3xl tracking-wider uppercase mb-1 text-purple-500" data-en="THE PHISHING NET" data-ms="JARING PHISHING">THE PHISHING NET</h1>
                    <p class="text-purple-400 font-bold tracking-widest text-sm uppercase" data-en="Level Content Configuration" data-ms="Konfigurasi Kandungan Tahap">Level Content Configuration</p>
                </div>
            </div>
            
            <a href="{{ route('admin.levels') }}" class="nav-trigger mt-4 md:mt-0 px-5 py-2 rounded-full theme-card border border-purple-500/50 text-purple-400 hover:bg-purple-500 hover:text-white transition-colors text-xs font-bold tracking-widest uppercase flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                <span data-en="Back to Hub" data-ms="Kembali ke Hab">Back to Hub</span>
            </a>
        </header>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <div class="sc-anim sc-in-left d-2">
                <div class="theme-card backdrop-blur-md rounded-lg p-6 border border-emerald-900/50 shadow-[0_0_20px_rgba(6,95,70,0.2)]">
                    
                    <div class="flex items-center text-emerald-500 text-sm mb-5 border-b border-emerald-900/50 pb-3 font-bold tracking-widest uppercase">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                        <span id="form-header-text" data-en="New Email Payload" data-ms="Muatan E-mel Baru">New Email Payload</span>
                    </div>

                    @if(session('success'))
                        <div class="bg-emerald-500/20 border border-emerald-500 text-emerald-400 px-4 py-2 rounded mb-4 text-xs font-bold uppercase tracking-widest">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form id="level1-form" action="{{ route('admin.level1.store') }}" method="POST" class="space-y-4">
                        @csrf 
                        <div>
                            <input type="text" id="sender_name_or_address" name="sender_name_or_address" class="lz-input" placeholder="Dari (nama atau alamat)" required>
                        </div>

                        <div>
                            <input type="text" id="subject" name="subject" class="lz-input" placeholder="Subjek emel" required>
                        </div>

                        <div>
                            <textarea id="body" name="body" class="lz-input h-48 resize-none" placeholder="Isi emel" required></textarea>
                        </div>

                        <div class="flex items-center gap-2 pt-2">
                            <input type="checkbox" name="is_phishing" id="is_phishing" value="1" class="w-4 h-4 accent-emerald-600 bg-black border-emerald-900 rounded">
                            <label for="is_phishing" class="text-sm text-gray-300 font-bold">Phishing</label>
                        </div>

                        <div class="flex gap-3 pt-4 border-t border-emerald-900/50 mt-4">
                            <button type="submit" id="submit-btn" class="flex-1 bg-emerald-900 hover:bg-emerald-700 text-emerald-400 hover:text-white border border-emerald-700 font-bold py-3 px-6 rounded transition-colors text-sm shadow-[0_0_10px_rgba(16,185,129,0.3)]">
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
                <div class="theme-card backdrop-blur-md rounded-lg p-6 border border-purple-500/40 shadow-[0_0_25px_rgba(168,85,247,0.15)] h-full max-h-[820px] flex flex-col">
                    <div class="flex justify-between items-center mb-5 border-b border-purple-500/20 pb-3 shrink-0">
                        <div class="flex items-center text-purple-400 text-sm font-bold tracking-widest uppercase">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4" /></svg>
                            <span data-en="Active Email Database" data-ms="Pangkalan Data E-mel Aktif">Active Email Database</span>
                        </div>
                    </div>

                    <div class="overflow-x-auto overflow-y-auto flex-1 min-h-0 pr-2">
                        <table class="w-full text-left min-w-full">
                            <thead class="uppercase tracking-widest text-xs text-white border-b border-purple-500/30 sticky top-0 bg-black/90 backdrop-blur z-10">
                                <tr>
                                    <th class="px-4 py-4" data-en="Sender" data-ms="Pengirim">Sender</th>
                                    <th class="px-4 py-4 w-1/3" data-en="Subject" data-ms="Subjek">Subject</th>
                                    <th class="px-4 py-4 w-1/3" data-en="Body Snippet" data-ms="Petikan Isi">Body Snippet</th>
                                    <th class="px-4 py-4 text-center" data-en="Type" data-ms="Jenis">Type</th>
                                    <th class="px-4 py-4 text-right" data-en="Action" data-ms="Tindakan">Action</th>
                                </tr>
                            </thead>
                            <tbody class="font-mono text-base font-bold opacity-90 divide-y divide-purple-500/10">
                                
                                @forelse($emails as $email)
                                <tr class="hover:bg-purple-900/20 transition-colors">
                                    <td class="px-4 py-4 text-gray-300 truncate max-w-[150px] dynamic-subject" data-original="{{ $email->sender_name_or_address }}">{{ $email->sender_name_or_address }}</td>
                                    
                                    <td class="px-4 py-4 text-gray-400 truncate max-w-[150px] dynamic-subject" data-original="{{ $email->subject }}">{{ $email->subject }}</td>
                                    
                                    <td class="px-4 py-4 text-gray-500 truncate max-w-[150px] dynamic-body" data-original="{{ $email->body }}">{{ $email->body }}</td>
                                    
                                    <td class="px-4 py-4 text-center">
                                        @if($email->is_phishing)
                                            <span class="bg-red-900/50 text-red-400 px-3 py-1.5 rounded text-xs uppercase font-extrabold border border-red-500/50">Phishing</span>
                                        @else
                                            <span class="bg-emerald-900/50 text-emerald-400 px-3 py-1.5 rounded text-xs uppercase font-extrabold border border-emerald-500/50" data-en="Safe" data-ms="Selamat">Safe</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-4 text-right">
                                        <div class="flex justify-end gap-2 flex-nowrap whitespace-nowrap">
                                            <button type="button" onclick='editEmail(@json($email))' class="text-emerald-500 hover:text-emerald-400 text-xs font-bold uppercase tracking-widest bg-emerald-900/20 px-3 py-1.5 rounded border border-emerald-900/50 cursor-pointer transition-colors">
                                                <span data-en="Edit" data-ms="Sunting">Edit</span>
                                            </button>
                                            
                                            <form action="{{ route('admin.level1.destroy', $email->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to permanently delete this payload?');" class="inline-block m-0">
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
                                    <td colspan="5" class="px-4 py-10 text-center text-gray-500 italic text-sm font-bold" data-en="No email templates found in database." data-ms="Tiada templat e-mel dijumpai di pangkalan data.">No email templates found in database.</td>
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
            // Target BOTH subject and body columns
            const subRows = document.querySelectorAll('.dynamic-subject');
            const bodyRows = document.querySelectorAll('.dynamic-body');
            
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

            await translateRows(subRows);
            await translateRows(bodyRows);
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

        // Apply Language function
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

            // Fire the API
            processDynamicTranslations(lang);
        }
        applyLanguage(currentLang);

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

        // --- 4. LEVEL 1 FORM LOGIC ---
        function editEmail(email) {
            const form = document.getElementById('level1-form');
            // Assuming your update route matches the pattern of level2
            form.action = `/admin/level1/update/${email.id}`;

            // Populate inputs
            document.getElementById('sender_name_or_address').value = email.sender_name_or_address || '';
            document.getElementById('subject').value = email.subject || '';
            document.getElementById('body').value = email.body || '';
            
            // Handle the checkbox boolean state
            document.getElementById('is_phishing').checked = (email.is_phishing == 1 || email.is_phishing === true);

            // Update UI elements based on language
            document.getElementById('submit-btn').innerHTML = currentLang === 'ms' 
                ? '<span data-en="Update" data-ms="Kemaskini">Kemaskini</span>' 
                : '<span data-en="Update" data-ms="Kemaskini">Update</span>';
                
            document.getElementById('form-header-text').innerHTML = `Edit Payload (ID: ${email.id})`;

            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        function resetForm() {
            const form = document.getElementById('level1-form');
            form.reset(); 
            form.action = "{{ route('admin.level1.store') }}";
            
            document.getElementById('submit-btn').innerHTML = currentLang === 'ms' 
                ? '<span data-en="Save" data-ms="Simpan">Simpan</span>' 
                : '<span data-en="Save" data-ms="Simpan">Save</span>';
                
            document.getElementById('form-header-text').innerHTML = currentLang === 'ms' 
                ? '<span data-en="New Email Payload" data-ms="Muatan E-mel Baru">Muatan E-mel Baru</span>' 
                : '<span data-en="New Email Payload" data-ms="Muatan E-mel Baru">New Email Payload</span>';
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