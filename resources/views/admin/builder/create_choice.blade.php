<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Jawapan - Cyber Escape Room</title>
    <script src="https://cdn.tailwindcss.com/3.4.17"></script>
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
            --particle: rgba(212, 175, 55, 0.15);
            
            /* Form Specific */
            --input-bg: rgba(15, 40, 24, 0.7);
            --input-focus: rgba(20, 50, 30, 0.9);
            --panel-header: rgba(212, 175, 55, 0.15);
            --btn-outline-bg: rgba(212, 175, 55, 0.1);
            --btn-outline-hover: rgba(212, 175, 55, 0.2);
            --option-bg: #0a1a10;
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
            --particle: rgba(16, 185, 129, 0.25);
            
            /* Form Specific */
            --input-bg: rgba(255, 255, 255, 0.6);
            --input-focus: rgba(255, 255, 255, 1);
            --panel-header: rgba(16, 185, 129, 0.15);
            --btn-outline-bg: rgba(16, 185, 129, 0.1);
            --btn-outline-hover: rgba(16, 185, 129, 0.2);
            --option-bg: #ffffff;
        }

        html, body { height: 100%; width: 100%; overflow-x: hidden; }
        body { 
            font-family: 'Segoe UI', sans-serif; 
            background: linear-gradient(135deg, var(--bg-1) 0%, var(--bg-2) 50%, var(--bg-1) 100%); 
            color: var(--text-main); 
            background-attachment: fixed; 
            transition: background 0.5s ease, color 0.5s ease;
        }

        .theme-text-main { color: var(--text-main); transition: color 0.5s ease; }
        .theme-text-muted { color: var(--text-muted); transition: color 0.5s ease; }
        .theme-text-gold { color: var(--text-gold); transition: color 0.5s ease; }
        .theme-text-gold-dark { color: var(--text-gold-dark); transition: color 0.5s ease; }
        .theme-border { border-color: var(--glass-border); transition: border-color 0.5s ease; }
        .theme-panel-header { background: var(--panel-header); border-color: var(--glass-border); transition: all 0.5s ease; }

        .particles { position: fixed; top: 0; left: 0; width: 100%; height: 100%; overflow: hidden; pointer-events: none; z-index: 0; }
        .particle { position: absolute; background: var(--particle); border-radius: 50%; animation: float linear infinite; transition: background 0.5s ease; }
        @keyframes float { 0% { transform: translateY(100vh) translateX(0); opacity: 0; } 10% { opacity: 1; } 90% { opacity: 1; } 100% { transform: translateY(-10vh) translateX(100px); opacity: 0; } }

        .glass-panel { background: var(--glass-bg); backdrop-filter: blur(10px); border: 1px solid var(--glass-border); box-shadow: 0 8px 32px rgba(0, 0, 0, 0.15); transition: all 0.5s ease; }
        .glass-card { background: var(--glass-card); backdrop-filter: blur(8px); border: 1px solid var(--glass-border); border-radius: 16px; transition: all 0.5s ease; }
        
        .text-gold-gradient { background: linear-gradient(135deg, #d4af37 0%, #f0d56f 50%, #d4af37 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }
        body.light-mode .text-gold-gradient { background: linear-gradient(135deg, #b45309 0%, #d97706 50%, #b45309 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }
        
        .btn-gold { background: linear-gradient(135deg, #d4af37 0%, #f0d56f 50%, #d4af37 100%); color: #0f2818; border: none; font-weight: 700; transition: all 0.3s ease; box-shadow: 0 4px 15px rgba(212, 175, 55, 0.3); cursor: pointer;}
        .btn-gold:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(212, 175, 55, 0.5); }
        
        .btn-outline-gold { border: 1px solid var(--text-gold-dark); color: var(--text-gold); background: var(--btn-outline-bg); transition: all 0.3s; }
        .btn-outline-gold:hover { background: var(--btn-outline-hover); box-shadow: 0 0 15px var(--glass-shadow); color: var(--text-main); }
        
        .form-input { 
            width: 100%; padding: 0.85rem 1rem; 
            background: var(--input-bg); border: 1px solid var(--glass-border); 
            border-radius: 10px; color: var(--text-main); transition: all 0.3s ease; 
        }
        .form-input:focus { outline: none; border-color: var(--text-gold-dark); background: var(--input-focus); box-shadow: 0 0 15px var(--glass-shadow); }
        .form-label { display: block; font-size: 0.75rem; font-weight: 700; color: var(--text-gold-dark); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.5rem; transition: color 0.5s ease; }
        
        select.form-input { background-color: var(--option-bg); }
        
        body.light-mode .form-input::placeholder { color: #9ca3af; }
        body:not(.light-mode) .form-input::placeholder { color: #6b7280; }
    </style>
</head>
<body class="relative pb-32 preload">
    <script>if (localStorage.getItem('theme') === 'light') document.body.classList.add('light-mode');</script>

    <div class="particles" id="particlesContainer"></div>

    <nav class="glass-panel sticky top-0 z-50 border-b-2 border-t-0 border-l-0 border-r-0 theme-border">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20 items-center">
                <div class="flex items-center gap-4">
                    <img src="{{ asset('img/logo2.png') }}" onerror="this.style.display='none'" alt="LZNK" class="h-12 drop-shadow-[0_0_8px_rgba(212,175,55,0.6)]">
                    <div class="hidden md:block border-l h-10 pl-4 theme-border">
                        <h1 class="text-xl font-bold text-gold-gradient tracking-wide translation-target">Tambah Jawapan Baru</h1>
                        <p class="text-xs theme-text-muted tracking-widest uppercase mt-1"><span class="translation-target">Modul:</span> <span class="db-translate" data-ms="{{ $room->title }}" data-en="{{ $room->title_en ?: $room->title }}">{{ $room->title }}</span></p>
                    </div>
                </div>
                
                <div class="flex items-center gap-3">
                    <div class="flex items-center bg-black/20 p-1 rounded-lg border theme-border mr-1 md:mr-2 backdrop-blur-sm shadow-inner z-50 relative">
                        <button type="button" onclick="switchLanguage('ms')" id="lang-ms" class="px-2.5 py-1 text-[10px] font-bold rounded bg-[#10b981] text-white shadow-sm transition-all">BM</button>
                        <button type="button" onclick="switchLanguage('en')" id="lang-en" class="px-2.5 py-1 text-[10px] font-bold rounded text-gray-400 hover:text-white transition-all">EN</button>
                    </div>

                    <label class="relative inline-flex items-center cursor-pointer mr-2" title="Tukar Tema">
                        <input type="checkbox" id="theme-toggle" class="sr-only peer" autocomplete="off">
                        <div class="w-12 h-6 bg-gray-700 peer-focus:outline-none rounded-full peer peer-checked:bg-[#10b981] transition-colors duration-300 relative border border-gray-500 peer-checked:border-green-400">
                            <div class="absolute top-[1px] left-[2px] bg-white rounded-full h-5 w-5 transition-transform duration-300 peer-checked:translate-x-full flex items-center justify-center shadow-sm" id="toggle-circle">
                                <svg class="w-3 h-3 text-gray-800" fill="currentColor" viewBox="0 0 20 20"><path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path></svg>
                            </div>
                        </div>
                    </label>

                    <a href="{{ route('admin.builder.show', $room->id) }}" class="btn-outline-gold flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold uppercase tracking-wider">
                        <svg class="w-4 h-4 hidden md:block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        <span class="translation-target">Batal & Kembali</span>
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10 relative z-10">
        
        <div class="glass-card p-6 mb-8 border-l-4 theme-border" style="border-left-color: var(--text-gold-dark);">
            <span class="text-xs font-bold theme-text-gold-dark uppercase tracking-widest mb-1 block translation-target">Rujukan Soalan Semasa:</span>
            <h2 class="text-lg font-bold theme-text-main leading-relaxed db-translate" data-ms="{{ $question->text }}" data-en="{{ $question->text_en ?: $question->text }}">{{ $question->text }}</h2>
        </div>

        @if ($errors->any())
            <div class="mb-8 bg-red-500/10 border border-red-500 text-red-500 px-6 py-4 rounded-xl shadow-sm font-medium">
                <h3 class="font-bold mb-2 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    <span class="translation-target">Sila periksa ralat berikut:</span>
                </h3>
                <ul class="list-disc list-inside text-sm ml-4">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.builder.choice.store', $question->id) }}" method="POST">
            @csrf

            <div class="glass-card overflow-hidden mb-10 shadow-lg">
                <div class="theme-panel-header border-b px-6 py-4 flex justify-between items-center">
                    <h2 class="text-lg font-bold theme-text-gold tracking-widest uppercase translation-target">Butiran Jawapan</h2>
                </div>
                
                <div class="p-6 space-y-6">
                    <div>
                        <label class="form-label"><span class="translation-target">Teks Pilihan Jawapan</span> <span class="text-red-500">*</span></label>
                        <input type="text" name="text" required placeholder="Contoh: Saya akan memberikan maklumat tersebut..." class="form-input translation-placeholder" value="{{ old('text') }}">
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="form-label"><span class="translation-target">Markah (Points)</span> <span class="text-red-500">*</span></label>
                            <input type="number" name="points" required value="{{ old('points', 0) }}" class="form-input font-bold">
                            <p class="text-xs theme-text-main opacity-60 mt-2 italic translation-target">Gunakan nilai negatif (cth: -5) untuk jawapan yang salah/berisiko.</p>
                        </div>
                        
                        <div>
                            <label class="form-label theme-text-muted translation-target">Pautkan Ke (Branching)</label>
                            <select name="next_question_id" class="form-input appearance-none cursor-pointer">
                                <option value="" class="translation-target">-- Tamatkan Modul (Tiada Pautan) --</option>
                                @if(isset($otherQuestions))
                                    @foreach($otherQuestions as $otherQ)
                                        <option class="db-translate" data-ms="Soalan #{{ $otherQ->id }}: {{ Str::limit($otherQ->text, 40) }}" data-en="Question #{{ $otherQ->id }}: {{ Str::limit($otherQ->text_en ?: $otherQ->text, 40) }}" value="{{ $otherQ->id }}" {{ old('next_question_id') == $otherQ->id ? 'selected' : '' }}>
                                            Soalan #{{ $otherQ->id }}: {{ Str::limit($otherQ->text, 40) }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                    
                    <div>
                        <label class="form-label translation-target">Maklum Balas (Feedback) - Pilihan</label>
                        <textarea name="feedback" rows="3" placeholder="Contoh: Salah! Anda tidak patut memberikan maklumat peribadi melalui telefon." class="form-input text-sm italic translation-placeholder">{{ old('feedback') }}</textarea>
                        <p class="text-xs theme-text-main opacity-60 mt-2 italic translation-target">Mesej ini akan dipaparkan kepada pengguna selepas mereka memilih jawapan ini.</p>
                    </div>
                </div>
            </div>

            <div class="fixed bottom-0 left-0 w-full glass-panel border-t-2 border-b-0 border-l-0 border-r-0 theme-border py-4 px-6 z-50 flex justify-end items-center gap-4">
                <div class="max-w-4xl mx-auto w-full flex justify-end gap-4 px-4 sm:px-6 lg:px-8">
                    <button type="submit" class="btn-gold flex justify-center items-center gap-2 px-8 py-2.5 rounded-lg text-sm font-bold uppercase tracking-widest w-full md:w-auto shadow-md">
                        <svg class="w-5 h-5 hidden md:block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                        <span class="translation-target">Simpan Jawapan</span>
                    </button>
                </div>
            </div>
        </form>
    </main>

    <script>
        const container = document.getElementById('particlesContainer');
        for (let i = 0; i < 30; i++) {
            const p = document.createElement('div'); p.className = 'particle';
            const size = Math.random() * 4 + 2; p.style.width = size + 'px'; p.style.height = size + 'px';
            p.style.left = Math.random() * 100 + '%'; p.style.animationDuration = (Math.random() * 15 + 10) + 's';
            p.style.animationDelay = Math.random() * 5 + 's'; container.appendChild(p);
        }

        const themeToggle = document.getElementById('theme-toggle');
        const toggleCircle = document.getElementById('toggle-circle');
        const body = document.body;

        const moonIcon = '<svg class="w-3 h-3 text-gray-800" fill="currentColor" viewBox="0 0 20 20"><path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path></svg>';
        const sunIcon = '<svg class="w-4 h-4 text-yellow-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" clip-rule="evenodd"></path></svg>';

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
        });

        // 🌐 KAMUS TERJEMAHAN MAGIS
        const enDictionary = {
            "Tambah Jawapan Baru": "Add New Answer",
            "Modul:": "Module:",
            "Batal & Kembali": "Cancel & Back",
            "Rujukan Soalan Semasa:": "Current Question Reference:",
            "Sila periksa ralat berikut:": "Please check the following errors:",
            "Butiran Jawapan": "Answer Details",
            "Teks Pilihan Jawapan": "Answer Option Text",
            "Markah (Points)": "Marks (Points)",
            "Gunakan nilai negatif (cth: -5) untuk jawapan yang salah/berisiko.": "Use a negative value (e.g.: -5) for a wrong/risky answer.",
            "Pautkan Ke (Branching)": "Link To (Branching)",
            "-- Tamatkan Modul (Tiada Pautan) --": "-- End Module (No Link) --",
            "Maklum Balas (Feedback) - Pilihan": "Feedback - Optional",
            "Mesej ini akan dipaparkan kepada pengguna selepas mereka memilih jawapan ini.": "This message will be displayed to users after they select this answer.",
            "Simpan Jawapan": "Save Answer",
            
            // Placeholders
            "Contoh: Saya akan memberikan maklumat tersebut...": "E.g.: I will provide the information...",
            "Contoh: Salah! Anda tidak patut memberikan maklumat peribadi melalui telefon.": "E.g.: Wrong! You should not provide personal info over the phone."
        };

        function applyTranslation() {
            const lang = localStorage.getItem('lang') || 'ms';
            const btnEn = document.getElementById('lang-en');
            const btnMs = document.getElementById('lang-ms');

            if (lang === 'en') {
                if(btnEn) btnEn.className = "px-2.5 py-1 text-[10px] font-bold rounded bg-[#10b981] text-white shadow-sm transition-all";
                if(btnMs) btnMs.className = "px-2.5 py-1 text-[10px] font-bold rounded text-gray-400 hover:text-white transition-all";
                
                document.querySelectorAll('.translation-target').forEach(el => {
                    const text = el.textContent.trim();
                    if (text && enDictionary[text]) {
                        el.textContent = enDictionary[text];
                    }
                });

                document.querySelectorAll('.translation-placeholder').forEach(el => {
                    const placeholder = el.getAttribute('placeholder');
                    if (placeholder && enDictionary[placeholder]) {
                        el.setAttribute('placeholder', enDictionary[placeholder]);
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

        function switchLanguage(lang) {
            localStorage.setItem('lang', lang);
            window.location.reload(); 
        }

        document.addEventListener('DOMContentLoaded', () => {
            applyTranslation();
            setTimeout(() => { document.body.classList.remove('preload'); }, 100);
        });
    </script>
    @include('partials.cursor')
</body>
</html>