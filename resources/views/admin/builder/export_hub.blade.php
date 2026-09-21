<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hab Eksport Data - Cyber Escape Room</title>
    <script src="https://cdn.tailwindcss.com/3.4.17"></script>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
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
            --btn-outline-bg: rgba(212, 175, 55, 0.1);
            --btn-outline-hover: rgba(212, 175, 55, 0.2);
            --icon-bg: rgba(212, 175, 55, 0.2);
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
            --btn-outline-bg: rgba(16, 185, 129, 0.1);
            --btn-outline-hover: rgba(16, 185, 129, 0.2);
            --icon-bg: rgba(16, 185, 129, 0.2);
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
        .theme-icon-bg { background: var(--icon-bg); border-color: var(--glass-border); transition: all 0.5s ease; }

        .particles { position: fixed; top: 0; left: 0; width: 100%; height: 100%; overflow: hidden; pointer-events: none; z-index: 0; }
        .particle { position: absolute; background: var(--particle); border-radius: 50%; animation: float linear infinite; transition: background 0.5s ease; }
        @keyframes float { 0% { transform: translateY(100vh) translateX(0); opacity: 0; } 100% { transform: translateY(-10vh) translateX(100px); opacity: 0; } }

        .glass-panel { background: var(--glass-bg); backdrop-filter: blur(10px); border: 1px solid var(--glass-border); box-shadow: 0 8px 32px rgba(0, 0, 0, 0.15); transition: all 0.5s ease; }
        .glass-card { background: var(--glass-card); backdrop-filter: blur(8px); border: 1px solid var(--glass-border); border-radius: 16px; transition: all 0.5s ease; }
        .glass-card:hover { border-color: var(--glass-border-hover); box-shadow: 0 10px 30px var(--glass-shadow); transform: translateY(-5px); }

        .text-gold-gradient { background: linear-gradient(135deg, #d4af37 0%, #f0d56f 50%, #d4af37 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }
        body.light-mode .text-gold-gradient { background: linear-gradient(135deg, #b45309 0%, #d97706 50%, #b45309 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }
        
        .btn-outline-gold { border: 1px solid var(--text-gold-dark); color: var(--text-gold); background: var(--btn-outline-bg); transition: all 0.3s; }
        .btn-outline-gold:hover { background: var(--btn-outline-hover); box-shadow: 0 0 15px var(--glass-shadow); color: var(--text-main); cursor: pointer; }
    </style>
</head>
<body class="relative">
    <script>if (localStorage.getItem('theme') === 'light') document.body.classList.add('light-mode');</script>

    <div class="particles" id="particlesContainer"></div>

    <nav class="glass-panel sticky top-0 z-50 border-b-2 border-t-0 border-l-0 border-r-0 theme-border">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20 items-center">
                <div class="flex items-center gap-4">
                    <img src="{{ asset('img/logo2.png') }}" alt="LZNK" class="h-12 drop-shadow-[0_0_8px_rgba(212,175,55,0.6)]">
                    <div class="hidden md:block border-l h-10 pl-4 theme-border">
                        <h1 class="text-xl font-bold text-gold-gradient tracking-wide translation-target">Hab Eksport Laporan</h1>
                        <p class="text-xs theme-text-muted tracking-widest uppercase mt-1 translation-target">Muat Turun & Cetak Data</p>
                    </div>
                </div>
                
                <div class="flex items-center gap-3">
                    
                    <div class="flex items-center bg-black/20 p-1 rounded-lg border theme-border mr-1 md:mr-2 backdrop-blur-sm shadow-inner z-50 relative">
                        <button onclick="switchLanguage('ms')" id="lang-ms" class="px-2.5 py-1 text-[10px] font-bold rounded bg-[#10b981] text-white transition-all shadow-sm">BM</button>
                        <button onclick="switchLanguage('en')" id="lang-en" class="px-2.5 py-1 text-[10px] font-bold rounded text-gray-400 hover:text-white transition-all">EN</button>
                    </div>

                    <label class="relative inline-flex items-center cursor-pointer mr-2" title="Tukar Tema">
                        <input type="checkbox" id="theme-toggle" class="sr-only peer">
                        <div class="w-12 h-6 bg-gray-700 peer-focus:outline-none rounded-full peer peer-checked:bg-[#10b981] transition-colors duration-300 relative border border-gray-500 peer-checked:border-green-400">
                            <div class="absolute top-[1px] left-[2px] bg-white rounded-full h-5 w-5 transition-transform duration-300 peer-checked:translate-x-full flex items-center justify-center shadow-sm" id="toggle-circle">
                                <svg class="w-3 h-3 text-gray-800" fill="currentColor" viewBox="0 0 20 20"><path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path></svg>
                            </div>
                        </div>
                    </label>

                    <a href="{{ url('/builder') }}" class="btn-outline-gold flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold uppercase tracking-wider">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        <span class="hidden md:inline translation-target">Dashboard</span>
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12 relative z-10">
        
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-black theme-text-main tracking-wide drop-shadow-md mb-4 translation-target">Pilih Format Laporan</h2>
            <p class="theme-text-muted font-medium max-w-2xl mx-auto translation-target">Jana fail pangkalan data (.csv/.xlsx) untuk rujukan rekod staf atau lihat visualisasi statistik keseluruhan tahap kesedaran siber.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            
            <a href="{{ route('admin.export.summary') }}" class="glass-card p-8 flex flex-col items-center text-center group cursor-pointer relative overflow-hidden">
                <div class="absolute -right-4 -bottom-4 text-black/5 text-9xl group-hover:scale-110 transition-transform">🥇</div>
                <div class="w-20 h-20 rounded-2xl theme-icon-bg border theme-border flex items-center justify-center text-4xl mb-6 relative z-10">
                    <svg class="w-10 h-10 theme-text-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                </div>
                <h3 class="text-xl font-bold theme-text-gold mb-3 relative z-10 translation-target">Ringkasan Prestasi</h3>
                <p class="text-sm theme-text-main opacity-80 mb-6 relative z-10 translation-target">Laporan ringkas yang mengandungi Nama Staf, ID Staf, dan Jumlah Mata Keseluruhan.</p>
                <div class="mt-auto px-4 py-2 bg-green-500/20 text-green-500 font-bold text-xs uppercase tracking-widest rounded-full border border-green-500/50 relative z-10 translation-target">
                    Muat Turun Excel
                </div>
            </a>

            <a href="{{ route('admin.export.detailed') }}" class="glass-card p-8 flex flex-col items-center text-center group cursor-pointer relative overflow-hidden">
                <div class="absolute -right-4 -bottom-4 text-black/5 text-9xl group-hover:scale-110 transition-transform">🔍</div>
                <div class="w-20 h-20 rounded-2xl theme-icon-bg border theme-border flex items-center justify-center text-4xl mb-6 relative z-10">
                    <svg class="w-10 h-10 theme-text-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                </div>
                <h3 class="text-xl font-bold theme-text-gold mb-3 relative z-10 translation-target">Jejak Audit Terperinci</h3>
                <p class="text-sm theme-text-main opacity-80 mb-6 relative z-10 translation-target">Jadual lengkap menyenaraikan setiap tindakan, Modul, Soalan, dan Status (Betul/Salah).</p>
                <div class="mt-auto px-4 py-2 bg-green-500/20 text-green-500 font-bold text-xs uppercase tracking-widest rounded-full border border-green-500/50 relative z-10 translation-target">
                    Muat Turun Excel
                </div>
            </a>

            <a href="{{ route('admin.export.charts') }}" class="glass-card p-8 flex flex-col items-center text-center group cursor-pointer relative overflow-hidden">
                <div class="absolute -right-4 -bottom-4 text-black/5 text-9xl group-hover:scale-110 transition-transform">📊</div>
                <div class="w-20 h-20 rounded-2xl theme-icon-bg border theme-border flex items-center justify-center text-4xl mb-6 relative z-10">
                    <svg class="w-10 h-10 theme-text-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"></path></svg>
                </div>
                <h3 class="text-xl font-bold theme-text-gold mb-3 relative z-10 translation-target">Statistik Modul (Pai)</h3>
                <p class="text-sm theme-text-main opacity-80 mb-6 relative z-10 translation-target">Paparan visual carta pai untuk analisis kadar kejayaan (Betul/Salah) bagi setiap bilik.</p>
                <div class="mt-auto px-4 py-2 bg-blue-500/20 text-blue-500 font-bold text-xs uppercase tracking-widest rounded-full border border-blue-500/50 relative z-10 translation-target">
                    Lihat Paparan Web
                </div>
            </a>

        </div>
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

        // ==========================================
        // 🌐 KAMUS TERJEMAHAN MAGIS (Auto-Scanner)
        // ==========================================
        const enDictionary = {
            "Hab Eksport Laporan": "Report Export Hub",
            "Muat Turun & Cetak Data": "Download & Print Data",
            "Dashboard": "Dashboard",
            "Pilih Format Laporan": "Choose Report Format",
            "Jana fail pangkalan data (.csv/.xlsx) untuk rujukan rekod staf atau lihat visualisasi statistik keseluruhan tahap kesedaran siber.": "Generate database files (.csv/.xlsx) for staff records reference or view overall cyber awareness statistics visualization.",
            "Ringkasan Prestasi": "Performance Summary",
            "Laporan ringkas yang mengandungi Nama Staf, ID Staf, dan Jumlah Mata Keseluruhan.": "A brief report containing Staff Name, Staff ID, and Total Overall Score.",
            "Muat Turun Excel": "Download Excel",
            "Jejak Audit Terperinci": "Detailed Audit Trail",
            "Jadual lengkap menyenaraikan setiap tindakan, Modul, Soalan, dan Status (Betul/Salah).": "A complete table listing every action, Module, Question, and Status (Correct/Incorrect).",
            "Statistik Modul (Pai)": "Module Statistics (Pie)",
            "Paparan visual carta pai untuk analisis kadar kejayaan (Betul/Salah) bagi setiap bilik.": "Visual pie chart display for analyzing success rates (Correct/Incorrect) for each room.",
            "Lihat Paparan Web": "View Web Display"
        };

        function applyTranslation() {
            const lang = localStorage.getItem('lang') || 'ms';
            const btnEn = document.getElementById('lang-en');
            const btnMs = document.getElementById('lang-ms');

            if (lang === 'en') {
                if(btnEn) btnEn.className = "px-2.5 py-1 text-[10px] font-bold rounded bg-[#10b981] text-white transition-all shadow-sm";
                if(btnMs) btnMs.className = "px-2.5 py-1 text-[10px] font-bold rounded text-gray-400 hover:text-white transition-all";
                
                document.querySelectorAll('.translation-target').forEach(el => {
                    const text = el.textContent.trim();
                    if (text && enDictionary[text]) {
                        el.textContent = enDictionary[text];
                    }
                });
            } else {
                if(btnMs) btnMs.className = "px-2.5 py-1 text-[10px] font-bold rounded bg-[#10b981] text-white transition-all shadow-sm";
                if(btnEn) btnEn.className = "px-2.5 py-1 text-[10px] font-bold rounded text-gray-400 hover:text-white transition-all";
            }
        }

        function switchLanguage(lang) {
            localStorage.setItem('lang', lang);
            window.location.reload(); 
        }

        document.addEventListener('DOMContentLoaded', applyTranslation);
    </script>
    @include('partials.cursor')
</body>
</html>