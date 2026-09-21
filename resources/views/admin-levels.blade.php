<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>S.H.I.E.L.D // Level Configuration</title>
    @vite('resources/css/app.css')
    <link href="https://fonts.googleapis.com/css2?family=Anton&family=Poppins:wght@700&family=Share+Tech+Mono&display=swap" rel="stylesheet">
    
    <style>
        body { font-family: 'Share Tech Mono', monospace; overflow-x: hidden; }
        .title-font { font-family: 'Poppins', sans-serif; font-weight: 700; text-shadow: 0 0 5px rgba(168,85,247,0.7); }
        .scanlines { background: linear-gradient(to bottom, rgba(255,255,255,0), rgba(255,255,255,0) 50%, rgba(0,0,0,0.1) 50%, rgba(0,0,0,0.1)); background-size: 100% 4px; }

        :root {
            --bg-color: #000000; 
            --text-color: #d1d5db; 
            --vid-opacity: 0.8;
            --vid-overlay: linear-gradient(to bottom, rgba(0,0,0,0.85), rgba(0,0,0,0.4), rgba(0,0,0,0.95));
            --vid-filter: none;
            --card-bg: rgba(15, 10, 20, 0.85); 
            --card-hover: rgba(25, 15, 30, 0.9);
            --title-color: #ffffff; 
            --value-color: #ffffff;
            --glossy-sheen: none;
            
            /* Room 5 specific variables */
            --boss-text: #fca5a5; /* red-300 */
            --boss-btn-text: #fca5a5; /* red-300 */
        }

        .light-mode {
            /* 🚨 ARCADE STYLE LIGHT MODE 🚨 */
            --bg-color: #f3f4f6; 
            --text-color: #1f2937; 
            --vid-opacity: 0.15;
            --vid-overlay: rgba(255, 255, 255, 0.85);
            --vid-filter: invert(100%) hue-rotate(180deg) saturate(150%) brightness(120%);
            
            --card-bg: rgba(255, 255, 255, 0.95); 
            --card-hover: #ffffff;
            --title-color: #7e22ce; /* Purple 700 */
            --value-color: #0f172a;
            --glossy-sheen: 0 10px 25px -5px rgba(0, 0, 0, 0.1); 
            
            /* Room 5 Light Mode Adjustments */
            --boss-text: #b91c1c; /* Much darker red for readability */
            --boss-btn-text: #ffffff; /* White text on the button */
        }
        
        /* FIX: Hide the dark purple hover gradient inside Room 6 during Light Mode */
        .light-mode .arcade-inner-glow {
            display: none !important;
            opacity: 0 !important;
        }

        body { background-color: var(--bg-color); color: var(--text-color); transition: background-color 0.5s ease; }
        .theme-video { opacity: var(--vid-opacity); filter: var(--vid-filter); transition: opacity 0.5s ease, filter 0.5s ease; }
        .theme-overlay { background: var(--vid-overlay); transition: background 0.5s ease; }
        .theme-card { background: var(--card-bg) !important; transition: background 0.3s ease, box-shadow 0.3s ease; box-shadow: var(--glossy-sheen); }
        .theme-card:hover { background: var(--card-hover) !important; }
        .theme-title { color: var(--title-color) !important; }
        
        /* Apply dynamic colors to the Boss Room */
        .boss-card p { color: var(--boss-text); transition: color 0.5s ease; }
        .boss-card .nav-trigger { color: var(--boss-btn-text); transition: color 0.5s ease; }
        
        /* Light Mode Text Overrides */
        .light-mode .text-white { color: #000000 !important; text-shadow: none !important; }
        .light-mode .text-purple-400 { color: #7e22ce !important; }
        .light-mode .bg-red-900\/10 { background-color: rgba(254, 226, 226, 0.5) !important; } /* Soft pink for boss room bg in light mode */

        /* ======================================================== */
        /* BULLETPROOF STATE-BASED ANIMATIONS */
        /* ======================================================== */
        .sc-anim { 
            opacity: 0; 
            transition: all 0.6s cubic-bezier(0.16, 1, 0.3, 1);
            pointer-events: none; 
        }
        
        .sc-in-left { transform: translateX(-100vw); } 
        .sc-in-right { transform: translateX(100vw); } 
        .sc-in-up { transform: translateY(100vh); }

        .d-1 { transition-delay: 0.1s; } .d-2 { transition-delay: 0.2s; } .d-3 { transition-delay: 0.3s; } 
        .d-4 { transition-delay: 0.4s; } .d-5 { transition-delay: 0.5s; } .d-6 { transition-delay: 0.6s; }

        body.loaded .sc-anim {
            opacity: 1;
            transform: translate(0, 0) !important;
            pointer-events: auto;
        }

        body.exiting .sc-anim {
            transition: all 0.5s cubic-bezier(0.7, 0, 0.84, 0); 
            transition-delay: 0s !important; 
            opacity: 0;
            pointer-events: none;
        }
        /* ======================================================== */
    </style>
</head>
<body class="min-h-screen relative overflow-x-hidden transition-colors duration-500 pb-10">

    <div class="fixed inset-0 z-0 overflow-hidden">
        <video autoplay loop muted playsinline class="theme-video w-full h-full object-cover">
            <source src="{{ asset('video/videoplayback.webm') }}" type="video/webm">
        </video>
        <div class="absolute inset-0 bg-purple-900 mix-blend-color opacity-40 pointer-events-none"></div>
        <div class="theme-overlay absolute inset-0 pointer-events-none transition-background duration-500"></div>
    </div>

    <div class="fixed top-4 right-4 z-50 flex gap-3 sc-anim sc-in-right d-5">
        <button id="lang-toggle" class="theme-card backdrop-blur border border-purple-500/50 px-3 py-1.5 rounded-full text-xs font-bold tracking-wider hover:bg-purple-500 hover:text-white transition-colors text-purple-400">
            🇲🇾 BAHASA
        </button>
        <button id="theme-toggle" class="theme-card backdrop-blur border border-purple-500/50 px-3 py-1.5 rounded-full text-xs font-bold tracking-wider hover:bg-purple-500 hover:text-white transition-colors text-purple-400">
            ☀️ LIGHT MODE
        </button>
    </div>

    <div class="max-w-7xl mx-auto relative z-10 p-4 md:p-8 mt-8 md:mt-0">
        
        <header class="flex flex-col md:flex-row justify-between items-start md:items-end mb-8 pb-4 border-b border-purple-500/30 sc-anim sc-in-left d-1">
            <div class="flex items-center gap-5">
                <div class="w-16 h-16 rounded-full bg-blue-600 flex items-center justify-center shadow-[0_0_20px_rgba(37,99,235,0.6)]">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                </div>
                <div>
                    <h1 class="theme-title title-font text-3xl tracking-wider uppercase mb-1 text-blue-500" data-en="LEVEL CONFIGURATION" data-ms="KONFIGURASI TAHAP">LEVEL CONFIGURATION</h1>
                    <p class="text-blue-400 font-bold tracking-widest text-sm uppercase" data-en="Module Architecture Hub" data-ms="Pusat Seni Bina Modul">Module Architecture Hub</p>
                </div>
            </div>
            
            <a href="{{ route('admin.dashboard') }}" class="nav-trigger mt-4 md:mt-0 px-5 py-2 rounded-full theme-card border border-purple-500/50 text-purple-500 hover:bg-purple-500 hover:text-white transition-colors text-xs font-bold tracking-widest uppercase flex items-center gap-2 cursor-pointer">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                <span data-en="Back to Dashboard" data-ms="Kembali ke Papan Pemuka">Back to Dashboard</span>
            </a>
        </header>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            
            <div class="theme-card backdrop-blur-md rounded-lg p-6 border border-purple-500/50 shadow-[0_0_15px_rgba(168,85,247,0.1)] flex flex-col justify-between sc-anim sc-in-left d-2">
                <div>
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-10 h-10 rounded bg-purple-500/20 flex items-center justify-center text-purple-400 border border-purple-500/50 shrink-0"><span class="font-bold text-lg">01</span></div>
                        <h3 class="theme-title title-font text-xl uppercase" data-en="The Phishing Net" data-ms="Jaring Phishing">The Phishing Net</h3>
                    </div>
                    <p class="text-sm md:text-base font-bold opacity-80 mb-6 font-mono leading-relaxed" data-en="Edit email UI templates, define phishing red flags, and set success criteria." data-ms="Edit templat UI e-mel, takrifkan tanda amaran phishing, dan tetapkan kriteria kejayaan.">Edit email UI templates, define phishing red flags, and set success criteria.</p>
                </div>
                <a href="{{ route('admin.level1') }}" class="nav-trigger w-full bg-purple-600 hover:bg-purple-500 text-white font-bold py-2.5 px-4 rounded shadow-md transition-all tracking-widest text-xs uppercase flex justify-center items-center gap-2 mt-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                    <span data-en="Configure Level" data-ms="Konfigurasi Tahap">Configure Level</span>
                </a>
            </div>

            <div class="theme-card backdrop-blur-md rounded-lg p-6 border border-purple-500/50 shadow-[0_0_15px_rgba(168,85,247,0.1)] flex flex-col justify-between sc-anim sc-in-right d-3">
                <div>
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-10 h-10 rounded bg-purple-500/20 flex items-center justify-center text-purple-400 border border-purple-500/50 shrink-0"><span class="font-bold text-lg">02</span></div>
                        <h3 class="theme-title title-font text-xl uppercase" data-en="The Brute Force Gate" data-ms="Pintu Brute Force">The Brute Force Gate</h3>
                    </div>
                    <p class="text-sm md:text-base font-bold opacity-80 mb-6 font-mono leading-relaxed" data-en="Manage multiple-choice questions on password strength and database seeding." data-ms="Urus soalan aneka pilihan tentang kekuatan kata laluan dan pangkalan data.">Manage multiple-choice questions on password strength and database seeding.</p>
                </div>
                <a href="{{ route('admin.level2') }}" class="nav-trigger w-full bg-purple-600 hover:bg-purple-500 text-white font-bold py-2.5 px-4 rounded shadow-md transition-all tracking-widest text-xs uppercase flex justify-center items-center gap-2 mt-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                    <span data-en="Configure Level" data-ms="Konfigurasi Tahap">Configure Level</span>
                </a>
            </div>

            <div class="theme-card backdrop-blur-md rounded-lg p-6 border border-purple-500/50 shadow-[0_0_15px_rgba(168,85,247,0.1)] flex flex-col justify-between sc-anim sc-in-right d-4">
                <div>
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-10 h-10 rounded bg-purple-500/20 flex items-center justify-center text-purple-400 border border-purple-500/50 shrink-0"><span class="font-bold text-lg">03</span></div>
                        <h3 class="theme-title title-font text-xl uppercase" data-en="The Human Firewall" data-ms="Tembok Api Manusia">The Human Firewall</h3>
                    </div>
                    <p class="text-sm md:text-base font-bold opacity-80 mb-6 font-mono leading-relaxed" data-en="Edit WhatsApp/Telegram chat scripts to simulate social engineering attempts." data-ms="Edit skrip sembang WhatsApp/Telegram untuk mensimulasikan cubaan kejuruteraan sosial.">Edit WhatsApp/Telegram chat scripts to simulate social engineering attempts.</p>
                </div>
                <a href="{{ route('admin.level3') }}" class="nav-trigger w-full bg-purple-600 hover:bg-purple-500 text-white font-bold py-2.5 px-4 rounded shadow-md transition-all tracking-widest text-xs uppercase flex justify-center items-center gap-2 mt-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                    <span data-en="Configure Level" data-ms="Konfigurasi Tahap">Configure Level</span>
                </a>
            </div>

            <div class="theme-card backdrop-blur-md rounded-lg p-6 border border-purple-500/50 shadow-[0_0_15px_rgba(168,85,247,0.1)] flex flex-col justify-between sc-anim sc-in-left d-4">
                <div>
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-10 h-10 rounded bg-purple-500/20 flex items-center justify-center text-purple-400 border border-purple-500/50 shrink-0"><span class="font-bold text-lg">04</span></div>
                        <h3 class="theme-title title-font text-xl uppercase" data-en="The Mirror Web" data-ms="Web Cermin">The Mirror Web</h3>
                    </div>
                    <p class="text-sm md:text-base font-bold opacity-80 mb-6 font-mono leading-relaxed" data-en="Upload mocked-up website images and configure URL/SSL visual analysis clues." data-ms="Muat naik imej laman web olok-olok dan konfigurasi petunjuk analisis visual URL/SSL.">Upload mocked-up website images and configure URL/SSL visual analysis clues.</p>
                </div>
                <a href="{{ route('admin.level4') }}" class="nav-trigger w-full bg-purple-600 hover:bg-purple-500 text-white font-bold py-2.5 px-4 rounded shadow-md transition-all tracking-widest text-xs uppercase flex justify-center items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                    <span data-en="Configure Level" data-ms="Konfigurasi Tahap">Configure Level</span>
                </a>
            </div>

            <div class="boss-card theme-card backdrop-blur-md rounded-lg p-6 border border-red-500/60 shadow-[0_0_20px_rgba(220,38,38,0.2)] flex flex-col justify-between sc-anim sc-in-right d-5 lg:col-span-2 relative overflow-hidden">
                <div class="absolute inset-0 bg-red-900/10 z-0 pointer-events-none"></div>
                <div class="absolute inset-0 scanlines pointer-events-none opacity-50 z-0"></div>
                <div class="relative z-10">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-10 h-10 rounded bg-red-900/40 flex items-center justify-center text-red-500 border border-red-500/50 shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09A13.916 13.916 0 008 11a4 4 0 118 0c0 1.017-.07 2.019-.203 3m-2.118 6.844A21.88 21.88 0 0015.171 17m3.839 1.132c.645-2.266.99-4.659.99-7.132A8 8 0 008 4.07M3 15.364c.64-1.319 1-2.8 1-4.364 0-1.457.39-2.823 1.07-4" /></svg>
                        </div>
                        <h3 class="text-red-500 title-font text-2xl tracking-widest uppercase" data-en="S.H.I.E.L.D Mainframe (Final Boss)" data-ms="Mainframe S.H.I.E.L.D (Bos Terakhir)">S.H.I.E.L.D Mainframe (Final Boss)</h3>
                    </div>
                    <p class="text-sm md:text-base font-bold mb-6 font-mono leading-relaxed max-w-2xl" data-en="Set rapid-fire timed challenge parameters. Configure lives (e.g., 3 strikes), countdown duration, and final password decryption logic." data-ms="Tetapkan parameter cabaran pantas bermasa. Konfigurasi nyawa (cth., 3 cubaan), tempoh kira detik, dan logik nyahsulit kata laluan akhir.">Set rapid-fire timed challenge parameters. Configure lives (e.g., 3 strikes), countdown duration, and final password decryption logic.</p>
                </div>
                <a href="{{ route('admin.level5') }}" class="nav-trigger w-full relative z-10 bg-red-600/80 hover:bg-red-600 border border-red-500/50 font-bold py-3 px-4 rounded shadow-md transition-colors tracking-widest text-xs uppercase flex justify-center items-center gap-2 mt-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                    <span data-en="Configure Final Encounter" data-ms="Konfigurasi Pertemuan Akhir">Configure Final Encounter</span>
                </a>
            </div>

            <div class="theme-card backdrop-blur-md rounded-xl p-6 border-2 border-purple-500/50 shadow-[0_0_20px_rgba(168,85,247,0.3)] hover:shadow-[0_0_30px_rgba(168,85,247,0.5)] transition-all transform hover:-translate-y-1 relative overflow-hidden group sc-anim sc-in-up d-6 lg:col-span-3">
                <div class="arcade-inner-glow absolute inset-0 bg-gradient-to-br from-purple-900/40 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500 pointer-events-none"></div>
                
                <div class="flex items-center gap-4 mb-4 relative z-10">
                    <div class="w-12 h-12 rounded bg-purple-500/20 flex items-center justify-center text-purple-400 border border-purple-500/50">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                    <div>
                        <h2 class="text-xl text-purple-400 font-bold uppercase tracking-widest title-font mb-0.5" data-en="MINI-GAME ARCADE" data-ms="ARKED PERMAINAN MINI">MINI-GAME ARCADE</h2>
                        <span class="text-xs text-purple-500/70 font-mono font-bold tracking-wider">MODULE 06</span>
                    </div>
                </div>
                
                <p class="text-sm md:text-base font-bold mb-6 font-mono leading-relaxed max-w-1xl" data-en="Deploy diverse interactive modules. Configure Word Scrambles, Domino logic, Cryptography matching, Video Analysis, and Cyber Chess scenarios." data-ms="Kerahkan modul interaktif pelbagai jenis. Konfigurasi teka-teki perkataan, logik Domino, padanan Kriptografi, Analisis Video, dan senario Catur Siber.">
                    Deploy diverse interactive modules. Configure Word Scrambles, Domino logic, Cryptography matching, Video Analysis, and Cyber Chess scenarios.
                </p>
                
                <a href="{{ route('admin.level6') }}" class="w-full block bg-purple-600 hover:bg-purple-500 text-white border border-purple-400 font-bold py-3 px-4 rounded text-center uppercase tracking-widest text-xs transition-colors shadow-[0_0_15px_rgba(147,51,234,0.4)] relative z-10">
                    <span class="flex items-center justify-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                        <span data-en="CONFIGURE MODULE" data-ms="KONFIGURASI MODUL">CONFIGURE MODULE</span>
                    </span>
                </a>
            </div>

        </div> </div>

    <script>
        const bodyEl = document.body;
        const themeBtn = document.getElementById('theme-toggle');
        const langBtn = document.getElementById('lang-toggle');
        const translatables = document.querySelectorAll('[data-en]');

        // --- THE MAGIC: Start entrance transition instantly on load ---
        window.addEventListener('DOMContentLoaded', () => {
            setTimeout(() => { document.body.classList.add('loaded'); }, 50); 
        });

        let currentTheme = localStorage.getItem('shield_theme') || 'dark';
        let currentLang = localStorage.getItem('shield_lang') || 'en';

        if (currentTheme === 'light') {
            bodyEl.classList.add('light-mode');
            if(themeBtn) themeBtn.innerHTML = '🌙 DARK MODE';
        }

        function applyLanguage(lang) {
            if(langBtn) langBtn.innerHTML = lang === 'en' ? '🇲🇾 BAHASA' : '🇬🇧 ENGLISH';
            translatables.forEach(el => {
                if (el.tagName === 'SPAN' || el.tagName === 'P' || el.tagName === 'H1' || el.tagName === 'H2' || el.tagName === 'H3' || el.tagName === 'DIV') {
                    if(el.getAttribute(`data-${lang}`)) {
                        const textSpan = el.querySelector('span');
                        if(textSpan && !el.hasAttribute('data-en')) textSpan.innerText = el.getAttribute(`data-${lang}`);
                        else el.innerText = el.getAttribute(`data-${lang}`);
                    }
                }
            });
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

        // --- NEW EXIT LOGIC ---
        document.querySelectorAll('.nav-trigger').forEach(trigger => {
            trigger.addEventListener('click', function(e) {
                e.preventDefault(); 
                const targetUrl = this.getAttribute('href');
                
                document.body.classList.remove('loaded');
                document.body.classList.add('exiting');
                
                setTimeout(() => { window.location.href = targetUrl; }, 500); 
            });
        });
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