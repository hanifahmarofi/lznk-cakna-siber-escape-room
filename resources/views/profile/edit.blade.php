<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>S.H.I.E.L.D // Agent Profile</title>
    @vite('resources/css/app.css')
    <link href="https://fonts.googleapis.com/css2?family=Anton&family=Poppins:wght@700&family=Share+Tech+Mono&display=swap" rel="stylesheet">
    
    <style>
        body { font-family: 'Share Tech Mono', monospace; }
        .title-font { font-family: 'Poppins', sans-serif; font-weight: 700; text-shadow: 0 0 10px rgba(16,185,129,0.7); }
        .scanlines {
            background: linear-gradient(to bottom, rgba(255,255,255,0), rgba(255,255,255,0) 50%, rgba(0,0,0,0.1) 50%, rgba(0,0,0,0.1));
            background-size: 100% 4px;
        }

        :root {
            --bg-color: #000000;
            --text-color: #d1d5db;
            --vid-opacity: 0.8;
            --vid-overlay: linear-gradient(to bottom, rgba(0,0,0,0.8), rgba(0,0,0,0.4), rgba(0,0,0,0.9));
            --card-bg: rgba(21, 21, 21, 0.85);
            --card-hover: rgba(31, 31, 31, 0.9);
            --title-color: #ffffff;
            --input-bg: #0a0a0a;
            --input-border: #065f46;
            --input-text: #d1d5db;
        }

        .light-mode {
            --bg-color: #d1d5db;
            --text-color: #0f172a;
            --vid-opacity: 0.15;
            --vid-overlay: linear-gradient(to bottom, rgba(31, 41, 55, 0.9), rgba(156, 163, 175, 0.5), rgba(17, 24, 39, 0.95));
            --card-bg: rgba(229, 231, 235, 0.95);
            --card-hover: rgba(243, 244, 246, 0.98);
            --title-color: #064e3b;
            --input-bg: #ffffff;
            --input-border: #9ca3af;
            --input-text: #111827;
        }

        body { background-color: var(--bg-color); color: var(--text-color); transition: background-color 0.5s ease; }
        .theme-video { opacity: var(--vid-opacity); transition: opacity 0.5s ease; }
        .theme-overlay { background: var(--vid-overlay); transition: background 0.5s ease; }
        .theme-card { background: var(--card-bg) !important; transition: background 0.3s ease; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); }
        .theme-title { color: var(--title-color) !important; }

        .lz-input {
            background-color: var(--input-bg); border: 1px solid var(--input-border); color: var(--input-text);
            width: 100%; padding: 12px 16px; border-radius: 4px; font-family: 'Share Tech Mono', monospace; 
            font-size: 1rem; transition: all 0.2s;
        }
        .lz-input:focus { outline: none; border-color: #10b981; box-shadow: 0 0 10px rgba(16, 185, 129, 0.2); }

        .sc-anim { opacity: 0; animation-fill-mode: forwards; animation-timing-function: cubic-bezier(0.16, 1, 0.3, 1); animation-duration: 0.6s; }
        .sc-in-up { animation-name: slideInUp; }
        .d-1 { animation-delay: 0.1s; } .d-2 { animation-delay: 0.2s; } .d-3 { animation-delay: 0.3s; } .d-4 { animation-delay: 0.4s; }
        
        @keyframes slideInUp { 0% { transform: translateY(50px); opacity: 0; } 100% { transform: translateY(0); opacity: 1; } }
    </style>
</head>
<body class="min-h-screen relative overflow-x-hidden transition-colors duration-500 pb-10">

    <div class="fixed inset-0 z-0 overflow-hidden pointer-events-none">
        <video autoplay loop muted playsinline class="theme-video w-full h-full object-cover">
            <source src="{{ asset('video/videoplayback.webm') }}" type="video/webm">
        </video>
        <div class="absolute inset-0 bg-emerald-600 mix-blend-color opacity-50"></div>
        <div class="theme-overlay absolute inset-0 transition-background duration-500"></div>
    </div>

    <div class="fixed top-4 right-4 z-50 flex gap-3">
        <button id="lang-toggle" class="theme-card backdrop-blur border border-emerald-500/50 px-3 py-1.5 rounded-full text-xs font-bold tracking-wider hover:bg-emerald-500 hover:text-white transition-colors shadow-[0_0_10px_rgba(16,185,129,0.3)]">
            🇲🇾 BAHASA
        </button>
        <button id="theme-toggle" class="theme-card backdrop-blur border border-emerald-500/50 px-3 py-1.5 rounded-full text-xs font-bold tracking-wider hover:bg-emerald-500 hover:text-white transition-colors shadow-[0_0_10px_rgba(16,185,129,0.3)]">
            ☀️ LIGHT MODE
        </button>
    </div>

    <div class="max-w-4xl mx-auto relative z-10 p-4 md:p-8 mt-12 md:mt-8">
        
        <header class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 pb-4 border-b border-emerald-500/30 sc-anim sc-in-up d-1">
            <div>
                <h1 class="theme-title title-font text-4xl tracking-wider uppercase mb-1 text-emerald-500" data-en="AGENT PROFILE" data-ms="PROFIL EJEN">AGENT PROFILE</h1>
                <p class="text-emerald-600 font-bold tracking-widest text-sm uppercase" data-en="Manage your identity and security clearance" data-ms="Urus identiti dan pelepasan keselamatan anda">Manage your identity and security clearance</p>
            </div>
            
            <a href="{{ route('dashboard') }}" class="mt-4 md:mt-0 px-6 py-3 rounded theme-card border border-emerald-500/50 text-emerald-500 hover:bg-emerald-500 hover:text-white transition-colors text-sm font-bold tracking-widest uppercase flex items-center gap-2 shadow-[0_0_15px_rgba(16,185,129,0.2)]">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                <span data-en="Return to Hub" data-ms="Kembali ke Hab">Return to Hub</span>
            </a>
        </header>

        <div class="grid grid-cols-1 gap-8">
            
            <div class="theme-card backdrop-blur-md rounded-lg p-8 border border-emerald-500/40 shadow-[0_0_25px_rgba(16,185,129,0.15)] sc-anim sc-in-up d-2">
                <div class="flex items-center text-emerald-500 text-lg mb-6 border-b border-emerald-500/20 pb-4 font-bold tracking-widest uppercase">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                    <span data-en="Identity Records" data-ms="Rekod Identiti">Identity Records</span>
                </div>

                @if(session('status') === 'profile-updated')
                    <div class="mb-6 bg-emerald-500/20 border border-emerald-500 text-emerald-400 px-4 py-3 rounded font-bold uppercase tracking-widest text-sm animate-pulse" data-en="PROFILE SUCCESSFULLY UPDATED." data-ms="PROFIL BERJAYA DIKEMASKINI.">
                        PROFILE SUCCESSFULLY UPDATED.
                    </div>
                @endif

                <form method="post" action="{{ route('profile.update') }}" class="space-y-6">
                    @csrf
                    @method('patch')

                    <div>
                        <label class="block text-sm font-bold uppercase tracking-widest mb-2 opacity-80" data-en="Full Name" data-ms="Nama Penuh">Full Name</label>
                        <input type="text" name="full_name" value="{{ old('full_name', auth()->user()->full_name ?? auth()->user()->name) }}" class="lz-input" required autofocus autocomplete="name">
                        @error('full_name') <span class="text-red-500 text-xs font-bold mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold uppercase tracking-widest mb-2 opacity-80" data-en="Encrypted Comm Link (Email)" data-ms="Pautan Komunikasi Sulit (E-mel)">Encrypted Comm Link (Email)</label>
                        <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}" class="lz-input" required autocomplete="username">
                        @error('email') <span class="text-red-500 text-xs font-bold mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="pt-4 flex items-center gap-4">
                        <button type="submit" class="bg-emerald-600 text-black px-8 py-3 rounded font-bold uppercase tracking-widest hover:bg-emerald-500 transition-colors shadow-[0_0_15px_rgba(16,185,129,0.4)]">
                            <span data-en="Save Changes" data-ms="Simpan Perubahan">Save Changes</span>
                        </button>
                    </div>
                </form>
            </div>

            <div class="theme-card backdrop-blur-md rounded-lg p-8 border border-purple-500/40 shadow-[0_0_25px_rgba(168,85,247,0.15)] sc-anim sc-in-up d-3">
                <div class="flex items-center text-purple-500 text-lg mb-6 border-b border-purple-500/20 pb-4 font-bold tracking-widest uppercase">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" /></svg>
                    <span data-en="Security Clearance (Password)" data-ms="Pelepasan Keselamatan (Kata Laluan)">Security Clearance (Password)</span>
                </div>

                @if(session('status') === 'password-updated')
                    <div class="mb-6 bg-purple-500/20 border border-purple-500 text-purple-400 px-4 py-3 rounded font-bold uppercase tracking-widest text-sm animate-pulse" data-en="PASSWORD SUCCESSFULLY UPDATED." data-ms="KATA LALUAN BERJAYA DIKEMASKINI.">
                        PASSWORD SUCCESSFULLY UPDATED.
                    </div>
                @endif

                <form method="post" action="{{ route('password.update') }}" class="space-y-6">
                    @csrf
                    @method('put')

                    <div>
                        <label class="block text-sm font-bold uppercase tracking-widest mb-2 opacity-80" data-en="Current Password" data-ms="Kata Laluan Semasa">Current Password</label>
                        <input type="password" name="current_password" class="lz-input" autocomplete="current-password">
                        @error('current_password') <span class="text-red-500 text-xs font-bold mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-bold uppercase tracking-widest mb-2 opacity-80" data-en="New Password" data-ms="Kata Laluan Baru">New Password</label>
                            <input type="password" name="password" class="lz-input" autocomplete="new-password">
                            @error('password') <span class="text-red-500 text-xs font-bold mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-bold uppercase tracking-widest mb-2 opacity-80" data-en="Confirm New Password" data-ms="Sahkan Kata Laluan Baru">Confirm New Password</label>
                            <input type="password" name="password_confirmation" class="lz-input" autocomplete="new-password">
                            @error('password_confirmation') <span class="text-red-500 text-xs font-bold mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="pt-4 flex items-center gap-4">
                        <button type="submit" class="bg-purple-600 text-white px-8 py-3 rounded font-bold uppercase tracking-widest hover:bg-purple-500 transition-colors shadow-[0_0_15px_rgba(168,85,247,0.4)]">
                            <span data-en="Update Security" data-ms="Kemaskini Keselamatan">Update Security</span>
                        </button>
                    </div>
                </form>
            </div>

            @php
                // Fetch progress exactly how the Mission page does!
                $progress = \Illuminate\Support\Facades\DB::table('user_progress')
                                ->where('user_id', auth()->id())
                                ->first();
                                
                $hasCompletedGame = ($progress && $progress->level_5_completed == 1);
            @endphp

            @if(session('rooms_completed_memory') >= 5)
            <div class="theme-card backdrop-blur-md rounded-lg p-8 border border-yellow-500/40 shadow-[0_0_30px_rgba(234,179,8,0.2)] sc-anim sc-in-up d-4">
                <div class="flex items-center justify-between mb-2">
                    <div class="flex items-center text-yellow-500 text-lg font-bold tracking-widest uppercase">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" /></svg>
                        <span data-en="Mission Accomplished" data-ms="Misi Selesai">Mission Accomplished</span>
                    </div>
                </div>
                
                <p class="text-gray-400 text-sm mb-6 mt-2 leading-relaxed" data-en="Congratulations Agent. You have successfully secured the mainframe and completed all S.H.I.E.L.D training modules. Your official certificate is now available." data-ms="Tahniah Ejen. Anda telah berjaya menyelamatkan komputer utama dan menyelesaikan semua modul latihan S.H.I.E.L.D. Sijil rasmi anda kini tersedia.">
                    Congratulations Agent. You have successfully secured the mainframe and completed all S.H.I.E.L.D training modules. Your official certificate is now available.
                </p>

                <div class="flex">
                    <a href="{{ url('mission/certificate') }}" class="w-full md:w-auto bg-yellow-600 text-black px-10 py-4 rounded font-extrabold uppercase tracking-widest hover:bg-yellow-500 transition-colors shadow-[0_0_20px_rgba(234,179,8,0.5)] flex items-center justify-center gap-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 00-2 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" /></svg>
                        <span data-en="View Certificate" data-ms="Cetak Sijil">View Certificate</span>
                    </a>
                </div>
            </div>
            @endif

        </div>
    </div>

    <audio id="ui-click-sound" src="{{ asset('audio/mixkit-sci-fi-click-900.wav') }}" preload="auto"></audio>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Setup Audio
            const clickSound = document.getElementById('ui-click-sound');
            if(clickSound) {
                clickSound.volume = 0.6;
                document.querySelectorAll('button, a').forEach(el => {
                    el.addEventListener('click', () => {
                        clickSound.currentTime = 0; 
                        clickSound.play().catch(() => {});
                    });
                });
            }

            // Theme & Language
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
                        el.innerHTML = el.getAttribute(`data-${lang}`);
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
        });
    </script>
    <script>
        // Tell the parent wrapper to ensure music is playing
        window.addEventListener('DOMContentLoaded', () => {
            setTimeout(() => {
                window.parent.postMessage('ensureMusicPlaying', '*');
            }, 100);
        });
    </script>
    @include('partials.cursor')
</body>
</html>