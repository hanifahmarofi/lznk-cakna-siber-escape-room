<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Soalan Baru - Cyber Escape Room</title>
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
            
            /* Form & Panel Specific */
            --input-bg: rgba(15, 40, 24, 0.7);
            --input-focus: rgba(20, 50, 30, 0.9);
            --panel-header: rgba(212, 175, 55, 0.15);
            --panel-header-2: #0a1a10;
            --option-box-bg: rgba(0, 0, 0, 0.4);
            --option-input-bg: #0a1a10;
            --btn-outline-bg: rgba(212, 175, 55, 0.1);
            --btn-outline-hover: rgba(212, 175, 55, 0.2);
            --remove-btn-hover: rgba(248, 113, 113, 0.1);
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
            
            /* Form & Panel Specific */
            --input-bg: rgba(255, 255, 255, 0.6);
            --input-focus: rgba(255, 255, 255, 1);
            --panel-header: rgba(16, 185, 129, 0.15);
            --panel-header-2: rgba(16, 185, 129, 0.1);
            --option-box-bg: rgba(255, 255, 255, 0.4);
            --option-input-bg: rgba(255, 255, 255, 0.8);
            --btn-outline-bg: rgba(16, 185, 129, 0.1);
            --btn-outline-hover: rgba(16, 185, 129, 0.2);
            --remove-btn-hover: rgba(239, 68, 68, 0.1);
        }

        html, body { height: 100%; width: 100%; overflow-x: hidden; }
        body { 
            font-family: 'Segoe UI', sans-serif; 
            background: linear-gradient(135deg, var(--bg-1) 0%, var(--bg-2) 50%, var(--bg-1) 100%); 
            color: var(--text-main); 
            background-attachment: fixed; 
            transition: background 0.5s ease, color 0.5s ease;
        }

        /* Kelas Dinamik Tema */
        .theme-text-main { color: var(--text-main); transition: color 0.5s ease; }
        .theme-text-muted { color: var(--text-muted); transition: color 0.5s ease; }
        .theme-text-gold { color: var(--text-gold); transition: color 0.5s ease; }
        .theme-text-gold-dark { color: var(--text-gold-dark); transition: color 0.5s ease; }
        .theme-border { border-color: var(--glass-border); transition: border-color 0.5s ease; }
        .theme-border-hover:hover { border-color: var(--glass-border-hover); }
        .theme-panel-header { background: var(--panel-header); border-color: var(--glass-border); transition: all 0.5s ease; }
        .theme-panel-header-2 { background: var(--panel-header-2); border-color: var(--glass-border); transition: all 0.5s ease; }
        .theme-option-box { background: var(--option-box-bg); border-color: var(--glass-border); transition: all 0.5s ease; }
        
        .remove-btn:hover { background-color: var(--remove-btn-hover); }

        .particles { position: fixed; top: 0; left: 0; width: 100%; height: 100%; overflow: hidden; pointer-events: none; z-index: 0; }
        .particle { position: absolute; background: var(--particle); border-radius: 50%; animation: float linear infinite; transition: background 0.5s ease; }
        @keyframes float { 0% { transform: translateY(100vh) translateX(0); opacity: 0; } 10% { opacity: 1; } 90% { opacity: 1; } 100% { transform: translateY(-10vh) translateX(100px); opacity: 0; } }

        .glass-panel { background: var(--glass-bg); backdrop-filter: blur(10px); border: 1px solid var(--glass-border); box-shadow: 0 8px 32px rgba(0, 0, 0, 0.15); transition: all 0.5s ease; }
        .glass-card { background: var(--glass-card); backdrop-filter: blur(8px); border: 1px solid var(--glass-border); border-radius: 16px; transition: all 0.5s ease; }
        
        .text-gold-gradient { background: linear-gradient(135deg, #d4af37 0%, #f0d56f 50%, #d4af37 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }
        body.light-mode .text-gold-gradient { background: linear-gradient(135deg, #b45309 0%, #d97706 50%, #b45309 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }
        
        .btn-gold { background: linear-gradient(135deg, #d4af37 0%, #f0d56f 50%, #d4af37 100%); color: #0f2818; border: none; font-weight: 700; transition: all 0.3s ease; box-shadow: 0 4px 15px rgba(212, 175, 55, 0.3); cursor: pointer; }
        .btn-gold:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(212, 175, 55, 0.5); }
        
        .btn-outline-gold { border: 1px solid var(--text-gold-dark); color: var(--text-gold); background: var(--btn-outline-bg); transition: all 0.3s; }
        .btn-outline-gold:hover { background: var(--btn-outline-hover); box-shadow: 0 0 15px var(--glass-shadow); color: var(--text-main); }
        
        /* Form Inputs */
        .form-input { 
            width: 100%; padding: 0.85rem 1rem; 
            background: var(--input-bg); border: 1px solid var(--glass-border); 
            border-radius: 10px; color: var(--text-main); transition: all 0.3s ease; 
        }
        .form-input:focus { outline: none; border-color: var(--text-gold-dark); background: var(--input-focus); box-shadow: 0 0 15px var(--glass-shadow); }
        .form-label { display: block; font-size: 0.75rem; font-weight: 700; color: var(--text-gold-dark); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.5rem; transition: color 0.5s ease;}
        
        /* Input khusus Branching Dropdown */
        select.branching-dropdown, select[class*="branching-dropdown-"] {
            background-color: var(--option-input-bg) !important;
        }

        body.light-mode .form-input::placeholder { color: #9ca3af; }
        body:not(.light-mode) .form-input::placeholder { color: #6b7280; }
    </style>
</head>
<body class="relative pb-32 preload">
    <script>
        if (localStorage.getItem('theme') === 'light') {
            document.body.classList.add('light-mode');
        }
    </script>

    <div class="particles" id="particlesContainer"></div>

    <nav class="glass-panel sticky top-0 z-50 border-b-2 border-t-0 border-l-0 border-r-0 theme-border">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20 items-center">
                <div class="flex items-center gap-4">
                    <img src="{{ asset('img/logo2.png') }}" onerror="this.style.display='none'" alt="LZNK" class="h-12 drop-shadow-[0_0_8px_rgba(212,175,55,0.6)]">
                    <div class="hidden md:block border-l h-10 pl-4 theme-border">
                        <h1 class="text-xl font-bold text-gold-gradient tracking-wide translation-target">Tambah Soalan Baru</h1>
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

                    <!-- UPDATED BACK BUTTON ROUTE: admin.builder.show -->
                    <a href="{{ route('admin.builder.show', $room->id) }}" class="btn-outline-gold flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold uppercase tracking-wider">
                        <svg class="w-4 h-4 hidden md:block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        <span class="translation-target">Kembali</span>
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10 relative z-10">
        
        <!-- UPDATED FORM ACTION: admin.builder.node.store -->
        <form action="{{ route('admin.builder.node.store', $room->id) }}" method="POST" id="questionForm" enctype="multipart/form-data">
            @csrf

            <div class="glass-card overflow-hidden mb-10 shadow-lg">
                <div class="theme-panel-header border-b px-6 py-4">
                    <h2 class="text-lg font-bold theme-text-gold tracking-widest uppercase translation-target">Bahagian 1: Teras Soalan</h2>
                </div>
                <div class="p-6 space-y-6">
                    <div>
                        <label class="form-label"><span class="translation-target">Teks Soalan / Situasi</span> <span class="text-red-500">*</span></label>
                        <textarea name="text" rows="4" class="form-input translation-placeholder" required placeholder="Masukkan situasi atau soalan di sini..."></textarea>
                    </div>
                    <div>
                        <label class="form-label"><span class="translation-target">Tahap Soalan (Level)</span> <span class="text-red-500">*</span></label>
                        <input type="number" name="level" value="1" class="form-input w-full md:w-1/3" required min="1">
                        <p class="text-xs theme-text-main opacity-60 mt-2 italic translation-target">Biarkan Level 1 jika ini adalah soalan pertama. Tukar kepada nombor lebih besar untuk sub-soalan.</p>
                    </div>
                    
                    <!-- VIDEO SETTINGS BLOCK -->
                    <div class="mt-6 border-t theme-border pt-6">
                        <h3 class="text-md font-bold theme-text-gold tracking-widest uppercase mb-4 translation-target">Lampiran Video (Pilihan)</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-black/10 p-4 rounded-lg border theme-border mb-6">
                            <div>
                                <label class="form-label translation-target">Pilihan 1: URL Video (YouTube)</label>
                                <input type="url" name="video_url" class="form-input translation-placeholder" placeholder="Contoh: https://www.youtube.com/watch?v=XXXXX">
                                <p class="text-xs theme-text-muted opacity-80 mt-2 translation-target">Guna ini untuk pautkan video dari YouTube.</p>
                            </div>
                            <div>
                                <label class="form-label translation-target">Pilihan 2: Muat Naik MP4</label>
                                <input type="file" name="video_upload" accept="video/mp4,video/x-m4v,video/*" class="form-input file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-[#10b981] file:text-white hover:file:bg-[#059669] transition-all">
                                <p class="text-xs theme-text-muted opacity-80 mt-2 translation-target">Format: MP4, Max: 50MB.</p>
                            </div>
                        </div>
                        <div>
                            <label class="form-label translation-target">Masa Wajib Tonton (Saat)</label>
                            <input type="number" name="video_duration" value="0" min="0" class="form-input w-full md:w-1/3 translation-placeholder" placeholder="Contoh: 30">
                            <p class="text-xs theme-text-main opacity-60 mt-2 italic translation-target">Biarkan 0 jika tiada masa wajib. Jika diisi, pemain tidak boleh menjawab selagi masa belum tamat.</p>
                        </div>
                    </div>
                    <!-- END VIDEO SETTINGS BLOCK -->

                </div>
            </div>

            <div class="glass-card overflow-hidden mb-8 shadow-lg">
                <div class="theme-panel-header-2 border-b px-6 py-4 flex justify-between items-center">
                    <h2 class="text-lg font-bold theme-text-main tracking-widest uppercase translation-target">Bahagian 2: Pilihan Jawapan</h2>
                    <button type="button" id="addOptionBtn" class="btn-outline-gold px-4 py-1.5 rounded-lg text-xs font-bold uppercase tracking-wider transition shadow-sm">
                        <span class="translation-target">+ Tambah Jawapan</span>
                    </button>
                </div>
                
                <div id="optionsContainer" class="p-6 space-y-8">
                    <div class="option-item theme-option-box border p-6 rounded-xl relative theme-border-hover">
                        <div class="absolute -top-4 -left-4 w-10 h-10 rounded-full bg-gradient-to-br from-[#d4af37] to-[#8b6508] text-[#0f2818] font-black text-lg flex items-center justify-center border-4 border-[#0f2818] shadow-lg option-number">1</div>
                        
                        <div class="space-y-5 pt-2">
                            <div>
                                <label class="form-label translation-target">Teks Jawapan</label>
                                <input type="text" name="options[0][text]" class="form-input translation-placeholder" placeholder="Masukkan pilihan jawapan..." required>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <div>
                                    <label class="form-label translation-target">Markah (Pts)</label>
                                    <input type="number" name="options[0][points]" value="0" class="form-input font-bold" required>
                                </div>
                                <div>
                                    <label class="form-label theme-text-muted translation-target">Pautkan Ke (Branching)</label>
                                    <select name="options[0][next_question_id]" class="form-input appearance-none branching-dropdown">
                                        <option value="" class="translation-target">-- Tamatkan Modul (Tiada Pautan) --</option>
                                    </select>
                                </div>
                            </div>
                            <div>
                                <label class="form-label translation-target">Maklum Balas Tersembunyi</label>
                                <input type="text" name="options[0][feedback]" class="form-input text-sm italic translation-placeholder" placeholder="Maklum balas jika staf pilih ini...">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="fixed bottom-0 left-0 w-full glass-panel border-t-2 border-b-0 border-l-0 border-r-0 theme-border py-4 px-6 z-50 flex justify-end items-center gap-4">
                <div class="max-w-5xl mx-auto w-full flex justify-between md:justify-end gap-4">
                    <!-- UPDATED BACK BUTTON ROUTE: admin.builder.show -->
                    <a href="{{ route('admin.builder.show', $room->id) }}" class="btn-outline-gold px-6 py-2.5 rounded-lg text-sm font-bold uppercase tracking-widest text-center w-full md:w-auto translation-target">Batal</a>
                    <button type="submit" class="btn-gold flex justify-center items-center gap-2 px-8 py-2.5 rounded-lg text-sm font-bold uppercase tracking-widest w-full md:w-auto shadow-md">
                        <svg class="w-5 h-5 hidden md:block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                        <span class="translation-target">Simpan Soalan</span>
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
            "Tambah Soalan Baru": "Add New Question",
            "Kembali": "Back",
            "Bahagian 1: Teras Soalan": "Part 1: Core Question",
            "Teks Soalan / Situasi": "Question Text / Situation",
            "Tahap Soalan (Level)": "Question Level",
            "Biarkan Level 1 jika ini adalah soalan pertama. Tukar kepada nombor lebih besar untuk sub-soalan.": "Leave at Level 1 if this is the first question. Change to a higher number for sub-questions.",
            "Lampiran Video (Pilihan)": "Video Attachment (Optional)",
            "Pilihan 1: URL Video (YouTube)": "Option 1: Video URL (YouTube)",
            "Guna ini untuk pautkan video dari YouTube.": "Use this to link a video from YouTube.",
            "Pilihan 2: Muat Naik MP4": "Option 2: Upload MP4",
            "Format: MP4, Max: 50MB.": "Format: MP4, Max: 50MB.",
            "Masa Wajib Tonton (Saat)": "Mandatory Watch Time (Seconds)",
            "Biarkan 0 jika tiada masa wajib. Jika diisi, pemain tidak boleh menjawab selagi masa belum tamat.": "Leave 0 if no mandatory time. If set, players cannot answer until the timer ends.",
            "Bahagian 2: Pilihan Jawapan": "Part 2: Answer Options",
            "+ Tambah Jawapan": "+ Add Answer",
            "Teks Jawapan": "Answer Text",
            "Markah (Pts)": "Marks (Pts)",
            "Pautkan Ke (Branching)": "Link To (Branching)",
            "-- Tamatkan Modul (Tiada Pautan) --": "-- End Module (No Link) --",
            "Maklum Balas Tersembunyi": "Hidden Feedback",
            "Batal": "Cancel",
            "Simpan Soalan": "Save Question",

            // Placeholders
            "Contoh: Menghadapi Email Phishing": "E.g.: Handling Phishing Emails",
            "Masukkan situasi atau soalan di sini...": "Enter situation or question here...",
            "Masukkan pilihan jawapan...": "Enter answer option...",
            "Maklum balas jika staf pilih ini...": "Feedback if staff chooses this...",
            "Contoh: https://www.youtube.com/watch?v=XXXXX": "E.g.: https://www.youtube.com/watch?v=XXXXX",
            "Contoh: 30": "E.g.: 30"
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
                    if (el.tagName === 'OPTION') {
                        el.textContent = el.getAttribute('data-en') || el.getAttribute('data-ms');
                    } else {
                        el.textContent = el.getAttribute('data-en') || el.getAttribute('data-ms');
                    }
                });

            } else {
                if(btnMs) btnMs.className = "px-2.5 py-1 text-[10px] font-bold rounded bg-[#10b981] text-white shadow-sm transition-all";
                if(btnEn) btnEn.className = "px-2.5 py-1 text-[10px] font-bold rounded text-gray-400 hover:text-white transition-all";
                
                document.querySelectorAll('.db-translate').forEach(el => {
                    el.textContent = el.getAttribute('data-ms');
                });
            }
        }

        // ==========================================
        // SISTEM DYNAMIC SMART SYNC (TAMBAH JAWAPAN)
        // ==========================================
        const existingQuestions = @json($otherQuestions);
        let optionIndex = 1;
        const currentLang = localStorage.getItem('lang') || 'ms';

        function populateDropdown(selectElement) {
            existingQuestions.forEach(q => {
                const opt = document.createElement('option');
                opt.value = q.id;
                
                const textMs = `#${q.id} - ${q.text.substring(0, 45)}...`;
                const textEn = q.text_en ? `#${q.id} - ${q.text_en.substring(0, 45)}...` : textMs;
                
                opt.className = 'db-translate';
                opt.setAttribute('data-ms', textMs);
                opt.setAttribute('data-en', textEn);
                
                opt.textContent = currentLang === 'en' ? textEn : textMs;
                selectElement.appendChild(opt);
            });
        }

        populateDropdown(document.querySelector('.branching-dropdown'));

        document.getElementById('addOptionBtn').addEventListener('click', function() {
            const container = document.getElementById('optionsContainer');
            
            const isEn = (localStorage.getItem('lang') === 'en');
            const placeholderJawapan = isEn ? enDictionary["Masukkan pilihan jawapan..."] : "Masukkan pilihan jawapan...";
            const placeholderMaklumBalas = isEn ? enDictionary["Maklum balas jika staf pilih ini..."] : "Maklum balas jika staf pilih ini...";
            const labelJawapan = isEn ? enDictionary["Teks Jawapan"] : "Teks Jawapan";
            const labelMarkah = isEn ? enDictionary["Markah (Pts)"] : "Markah (Pts)";
            const labelBranching = isEn ? enDictionary["Pautkan Ke (Branching)"] : "Pautkan Ke (Branching)";
            const labelTamat = isEn ? enDictionary["-- Tamatkan Modul (Tiada Pautan) --"] : "-- Tamatkan Modul (Tiada Pautan) --";
            const labelMaklumBalas = isEn ? enDictionary["Maklum Balas Tersembunyi"] : "Maklum Balas Tersembunyi";

            const newOptionHtml = `
                <div class="option-item theme-option-box border p-6 rounded-xl relative theme-border-hover mt-8" id="optionBox_${optionIndex}">
                    <div class="absolute -top-4 -left-4 w-10 h-10 rounded-full bg-gradient-to-br from-[#d4af37] to-[#8b6508] text-[#0f2818] font-black text-lg flex items-center justify-center border-4 border-[#0f2818] shadow-lg option-number">${optionIndex + 1}</div>
                    
                    <div class="absolute top-4 right-4">
                        <button type="button" class="text-red-500 hover:text-red-600 p-1.5 rounded transition remove-btn" onclick="removeOption(${optionIndex})">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>

                    <div class="space-y-5 pt-2">
                        <div>
                            <label class="form-label translation-target">${labelJawapan}</label>
                            <input type="text" name="options[${optionIndex}][text]" class="form-input translation-placeholder" placeholder="${placeholderJawapan}" required>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label class="form-label translation-target">${labelMarkah}</label>
                                <input type="number" name="options[${optionIndex}][points]" value="0" class="form-input font-bold" required>
                            </div>
                            <div>
                                <label class="form-label theme-text-muted translation-target">${labelBranching}</label>
                                <select name="options[${optionIndex}][next_question_id]" class="form-input appearance-none branching-dropdown-${optionIndex}">
                                    <option value="" class="translation-target">${labelTamat}</option>
                                </select>
                            </div>
                        </div>
                        <div>
                            <label class="form-label translation-target">${labelMaklumBalas}</label>
                            <input type="text" name="options[${optionIndex}][feedback]" class="form-input text-sm italic translation-placeholder" placeholder="${placeholderMaklumBalas}">
                        </div>
                    </div>
                </div>
            `;
            
            container.insertAdjacentHTML('beforeend', newOptionHtml);
            
            const newSelect = document.querySelector(`.branching-dropdown-${optionIndex}`);
            populateDropdown(newSelect);
            
            optionIndex++;
            updateOptionNumbers();
        });

        window.removeOption = function(id) {
            const box = document.getElementById(`optionBox_${id}`);
            if(box) {
                box.remove();
                updateOptionNumbers();
            }
        };

        function updateOptionNumbers() {
            const boxes = document.querySelectorAll('.option-item');
            boxes.forEach((box, index) => {
                box.querySelector('.option-number').textContent = index + 1;
            });
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