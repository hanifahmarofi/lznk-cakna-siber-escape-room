<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kemaskini Modul - Cyber Escape Room</title>
    <script src="https://cdn.tailwindcss.com/3.4.17"></script>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        /* 🛑 HALANG ANIMASI SEMASA PAGE RELOAD */
        .preload * {
            -webkit-transition: none !important;
            -moz-transition: none !important;
            -ms-transition: none !important;
            -o-transition: none !important;
            transition: none !important;
        }
        
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
            --input-bg: rgba(15, 40, 24, 0.7);
            --input-focus: rgba(20, 50, 30, 0.9);
            --panel-header-1: rgba(212, 175, 55, 0.3);
            --panel-header-2: rgba(15, 40, 24, 0.1);
            --panel-border: rgba(212, 175, 55, 0.3);
            --check-bg: rgba(10, 26, 16, 0.5);
            --check-hover: rgba(212, 175, 55, 0.1);
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
            --input-bg: rgba(255, 255, 255, 0.9);
            --input-focus: rgba(255, 255, 255, 1);
            --panel-header-1: rgba(16, 185, 129, 0.3);
            --panel-header-2: rgba(255, 255, 255, 0.5);
            --panel-border: rgba(16, 185, 129, 0.4);
            --check-bg: rgba(255, 255, 255, 0.5);
            --check-hover: rgba(16, 185, 129, 0.1);
        }

        html, body { height: 100%; width: 100%; overflow-x: hidden; }
        body { 
            font-family: 'Segoe UI', sans-serif; 
            background: linear-gradient(135deg, var(--bg-1) 0%, var(--bg-2) 50%, var(--bg-1) 100%); 
            color: var(--text-main); 
            background-attachment: fixed; 
            transition: background 0.5s ease, color 0.5s ease;
        }

        .particles { position: fixed; top: 0; left: 0; width: 100%; height: 100%; overflow: hidden; pointer-events: none; z-index: 0; }
        .particle { position: absolute; background: var(--particle); border-radius: 50%; animation: float linear infinite; transition: background 0.5s ease;}
        @keyframes float { 0% { transform: translateY(100vh) translateX(0); opacity: 0; } 100% { transform: translateY(-10vh) translateX(100px); opacity: 0; } }

        .glass-panel { background: var(--glass-bg); backdrop-filter: blur(10px); border: 1px solid var(--glass-border); box-shadow: 0 8px 32px rgba(0, 0, 0, 0.5); transition: all 0.5s ease;}
        .glass-card { background: var(--glass-card); backdrop-filter: blur(8px); border: 1px solid var(--glass-border); border-radius: 16px; transition: all 0.3s ease; }
        
        .text-gold-gradient { background: linear-gradient(135deg, #d4af37 0%, #f0d56f 50%, #d4af37 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }
        body.light-mode .text-gold-gradient { background: linear-gradient(135deg, #b45309 0%, #d97706 50%, #b45309 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }

        .btn-gold { background: linear-gradient(135deg, #d4af37 0%, #f0d56f 50%, #d4af37 100%); color: #0f2818; border: none; font-weight: 700; transition: all 0.3s ease; }
        .btn-gold:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(212, 175, 55, 0.5); cursor: pointer; }
        .btn-outline-gold { border: 1px solid var(--text-gold-dark); color: var(--text-gold); background: rgba(212, 175, 55, 0.1); transition: all 0.3s; }
        .btn-outline-gold:hover { background: rgba(212, 175, 55, 0.2); box-shadow: 0 0 15px rgba(212, 175, 55, 0.3); color: #fff; }
        body.light-mode .btn-outline-gold { border-color: #10b981; color: #047857; background: rgba(16, 185, 129, 0.1); }
        body.light-mode .btn-outline-gold:hover { background: rgba(16, 185, 129, 0.2); box-shadow: 0 0 15px rgba(16, 185, 129, 0.3); color: #064e3b;}
        
        .form-input { width: 100%; padding: 0.85rem 1rem; background: var(--input-bg); border: 1px solid var(--glass-border); border-radius: 10px; color: var(--text-main); transition: all 0.3s ease; }
        .form-input:focus { outline: none; border-color: var(--text-gold); background: var(--input-focus); box-shadow: 0 0 15px var(--glass-shadow); }
        .form-label { display: block; font-size: 0.75rem; font-weight: 700; color: var(--text-gold-dark); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.5rem; transition: color 0.5s ease;}
        
        .theme-border { border-color: var(--panel-border); transition: border-color 0.5s ease; }
        .theme-header-bg { background: linear-gradient(to right, var(--panel-header-1), var(--panel-header-2)); transition: all 0.5s ease;}
        .theme-check-bg { background: var(--check-bg); transition: background 0.5s ease; }
        .theme-check-bg:hover { background: var(--check-hover); }
        .theme-text-muted { color: var(--text-muted); transition: color 0.5s ease;}
    </style>
</head>
<body class="relative pb-32 preload">
    <script>
        if (localStorage.getItem('theme') === 'light') {
            document.body.classList.add('light-mode');
        }
    </script>

    <div class="particles" id="particlesContainer"></div>

    <nav class="glass-panel sticky top-0 z-50 border-b theme-border">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20 items-center">
                <div class="flex items-center gap-4">
                    <img src="{{ asset('img/logo2.png') }}" onerror="this.style.display='none'" alt="LZNK" class="h-12 drop-shadow-[0_0_8px_rgba(212,175,55,0.6)]">
                    <div class="hidden md:block border-l theme-border h-10 pl-4">
                        <h1 class="text-xl font-bold text-gold-gradient tracking-wide translation-target">Kemaskini Modul</h1>
                        <p class="text-xs theme-text-muted tracking-widest uppercase truncate max-w-xs db-translate" data-ms="{{ $room->title }}" data-en="{{ $room->title_en ?: $room->title }}">{{ $room->title }}</p>
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

                    <a href="{{ route('admin.builder.modules') }}" class="btn-outline-gold flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold uppercase tracking-wider">
                        <span class="hidden md:inline translation-target">Batal & Kembali</span>
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10 relative z-10">
        <!-- UPDATED ACTION TO LINK TO CONTROLLER -->
        <form action="{{ route('admin.builder.room.update', $room->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="glass-card overflow-hidden mb-8 shadow-[0_0_20px_rgba(0,0,0,0.3)]">
                <div class="theme-header-bg border-b theme-border px-6 py-4 flex items-center justify-between">
                    <h2 class="text-lg font-bold text-[var(--text-gold)] tracking-widest uppercase translation-target">Maklumat Asas Bilik</h2>
                    <span class="bg-gradient-to-r from-[#d4af37] to-[#f0d56f] text-[#0f2818] text-xs font-black px-3 py-1 rounded shadow-md"><span class="translation-target">Bilik ID:</span> {{ $room->id }}</span>
                </div>
                <div class="p-6 space-y-6">
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="md:col-span-2">
                            <label class="form-label"><span class="translation-target">Tajuk Modul</span> <span class="text-red-400">*</span></label>
                            <input type="text" name="title" required class="form-input translation-placeholder" placeholder="Contoh: Menghadapi Email Phishing" value="{{ old('title', $room->title) }}">
                        </div>
                        <div>
                            <label class="form-label"><span class="translation-target">Markah Lulus (Sijil)</span> <span class="text-red-400">*</span></label>
                            <input type="number" name="pass_mark" required class="form-input font-bold" value="{{ old('pass_mark', $room->pass_mark ?? 50) }}" placeholder="50">
                            <p class="text-xs theme-text-main opacity-60 mt-1 italic translation-target">Staf perlu mencapai markah ini untuk memuat turun sijil.</p>
                        </div>
                    </div>

                    <div>
                        <label class="form-label"><span class="translation-target">Deskripsi & Senario Awal</span> <span class="text-red-400">*</span></label>
                        <textarea name="description" rows="4" class="form-input translation-placeholder" required placeholder="Masukkan senario awal untuk staf...">{{ old('description', $room->description) }}</textarea>
                    </div>
                    <label class="flex items-center gap-3 cursor-pointer p-4 theme-check-bg border theme-border rounded-xl transition">
                        <input type="checkbox" name="is_active" value="1" class="w-5 h-5 accent-[#10b981]" {{ $room->is_active ? 'checked' : '' }}>
                        <div>
                            <span class="font-bold theme-text-muted uppercase tracking-wider translation-target">Aktifkan Bilik Ini</span>
                            <p class="text-xs text-gray-500 translation-target">Jika ditanda, staf boleh mula bermain modul ini.</p>
                        </div>
                    </label>
                </div>
            </div>

            <div class="glass-card overflow-hidden mb-10 shadow-[0_0_20px_rgba(0,0,0,0.3)]">
                <div class="bg-blue-900/20 border-b border-blue-500/30 px-6 py-4 flex justify-between items-center">
                    <h2 class="text-lg font-bold text-blue-400 tracking-widest uppercase flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                        <span class="translation-target">Tetapan Video Pengenalan</span>
                    </h2>
                </div>
                <div class="p-6 space-y-6">
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-black/10 p-4 rounded-lg border theme-border">
                        <div>
                            <label class="form-label text-blue-400 translation-target">Pilihan 1: URL Video (YouTube)</label>
                            <input type="url" name="video_url" value="{{ old('video_url', $room->video_url ?? '') }}" class="form-input translation-placeholder" placeholder="Contoh: https://www.youtube.com/watch?v=XXXXX">
                            <p class="text-xs text-gray-500 mt-2 translation-target">Biar kosong jika anda ingin muat naik fail MP4.</p>
                        </div>

                        <div>
                            <label class="form-label text-blue-400 translation-target">Pilihan 2: Muat Naik MP4</label>
                            <input type="file" name="video_upload" accept="video/mp4,video/x-m4v,video/*" class="form-input file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-[#10b981] file:text-white hover:file:bg-[#059669] transition-all">
                            
                            @if(isset($room->video_path) && $room->video_path)
                                <p class="text-xs text-green-500 font-semibold mt-2 translation-target">✓ Video semasa telah dimuat naik. Upload baru untuk gantikan.</p>
                            @else
                                <p class="text-xs text-gray-500 mt-2 translation-target">Format: MP4, Max: 50MB. (Sistem utamakan fail MP4 jika kedua-duanya diisi).</p>
                            @endif
                        </div>
                    </div>

                    <div>
                        <label class="form-label text-blue-400 translation-target">Arahan / Deskripsi Video</label>
                        <textarea name="video_description" rows="2" class="form-input translation-placeholder" placeholder="Contoh: Sila tonton video di atas dengan teliti sebelum meneruskan sesi...">{{ old('video_description', $room->video_description ?? '') }}</textarea>
                    </div>
                    
                    <div>
                        <label class="form-label text-blue-400 translation-target">Masa Wajib Tonton (Saat)</label>
                        <input type="number" name="video_duration" value="{{ old('video_duration', $room->video_duration ?? 15) }}" min="0" class="form-input translation-placeholder" placeholder="Contoh: 120">
                        <p class="text-xs text-gray-500 mt-2 italic translation-target">Masa staf diwajibkan tunggu sebelum butang 'Mula' muncul (Cth: Masukkan 120 untuk video 2 minit).</p>
                    </div>
                </div> 
            </div> 
            
            <div class="fixed bottom-0 left-0 w-full glass-panel border-t theme-border py-4 px-6 z-50 flex justify-end items-center bg-[var(--bg-1)]/90 backdrop-blur-md">
                <div class="max-w-4xl mx-auto w-full flex justify-end">
                    <button type="submit" class="btn-gold px-8 py-3 rounded-lg text-sm font-bold uppercase tracking-widest translation-target">
                        Simpan Perubahan
                    </button>
                </div>
            </div>
        </form>
    </main>

    <script>
        const container = document.getElementById('particlesContainer');
        for (let i = 0; i < 20; i++) {
            const p = document.createElement('div'); p.className = 'particle';
            const size = Math.random() * 4 + 2; p.style.width = size + 'px'; p.style.height = size + 'px';
            p.style.left = Math.random() * 100 + '%'; p.style.animationDuration = (Math.random() * 15 + 10) + 's';
            container.appendChild(p);
        }

        // ==========================================
        // 🌟 LOGIK SUIS TEMA (LIGHT/DARK MODE)
        // ==========================================
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

        // ==========================================
        // 🌐 KAMUS TERJEMAHAN MAGIS (Auto-Scanner)
        // ==========================================
        const enDictionary = {
            "Kemaskini Modul": "Update Module",
            "Batal & Kembali": "Cancel & Back",
            "Maklumat Asas Bilik": "Basic Room Information",
            "Bilik ID:": "Room ID:",
            "Tajuk Modul": "Module Title",
            "Markah Lulus (Sijil)": "Passing Score (Certificate)",
            "Staf perlu mencapai markah ini untuk memuat turun sijil.": "Staff must achieve this score to download the certificate.",
            "Deskripsi & Senario Awal": "Description & Initial Scenario",
            "Aktifkan Bilik Ini": "Activate This Room",
            "Jika ditanda, staf boleh mula bermain modul ini.": "If checked, staff can start playing this module.",
            "Tetapan Video Pengenalan": "Intro Video Settings",
            "Pilihan 1: URL Video (YouTube)": "Option 1: Video URL (YouTube)",
            "Biar kosong jika anda ingin muat naik fail MP4.": "Leave blank if you wish to upload an MP4 file.",
            "Pilihan 2: Muat Naik MP4": "Option 2: Upload MP4",
            "✓ Video semasa telah dimuat naik. Upload baru untuk gantikan.": "✓ Current video uploaded. Upload new to replace.",
            "Format: MP4, Max: 50MB. (Sistem utamakan fail MP4 jika kedua-duanya diisi).": "Format: MP4, Max: 50MB. (System prioritizes MP4 if both are filled).",
            "Arahan / Deskripsi Video": "Video Instructions / Description",
            "Masa Wajib Tonton (Saat)": "Mandatory Watch Time (Seconds)",
            "Masa staf diwajibkan tunggu sebelum butang 'Mula' muncul (Cth: Masukkan 120 untuk video 2 minit).": "Time staff must wait before 'Start' button appears (E.g.: Enter 120 for a 2-minute video).",
            "Simpan Perubahan": "Save Changes",

            // Placeholders
            "Contoh: Menghadapi Email Phishing": "E.g.: Handling Phishing Emails",
            "Masukkan senario awal untuk staf...": "Enter initial scenario for staff...",
            "Contoh: https://www.youtube.com/watch?v=XXXXX": "E.g.: https://www.youtube.com/watch?v=XXXXX",
            "Contoh: Sila tonton video di atas dengan teliti sebelum meneruskan sesi...": "E.g.: Please watch the video above carefully before proceeding...",
            "Contoh: 120": "E.g.: 120"
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
            setTimeout(() => {
                document.body.classList.remove('preload');
            }, 100);
        });
    </script>
    @include('partials.cursor')
</body>
</html>