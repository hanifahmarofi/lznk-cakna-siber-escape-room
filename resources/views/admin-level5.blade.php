<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>S.H.I.E.L.D // Final Boss Configuration</title>
    @vite('resources/css/app.css')
    <link href="https://fonts.googleapis.com/css2?family=Anton&family=Poppins:wght@700;900&family=Share+Tech+Mono&display=swap" rel="stylesheet">
    
    <style>
        html, body { overflow-x: hidden; }
        body { font-family: 'Share Tech Mono', monospace; }
        .title-font { font-family: 'Poppins', sans-serif; font-weight: 700; text-shadow: 0 0 15px rgba(220,38,38,0.8); }
        .scanlines { background: linear-gradient(to bottom, rgba(255,255,255,0), rgba(255,255,255,0) 50%, rgba(0,0,0,0.15) 50%, rgba(0,0,0,0.15)); background-size: 100% 4px; }

        :root {
            --bg-color: #050000; --text-color: #d1d5db; --vid-opacity: 0.6;
            --vid-overlay: linear-gradient(to bottom, rgba(20,0,0,0.9), rgba(40,0,0,0.6), rgba(10,0,0,0.95));
            --card-bg: rgba(25, 5, 5, 0.85);
            --title-color: #ffffff;

            /* Custom dynamic text colors for Room 5 */
            --dyn-red: #fca5a5;       /* Light Red for Dark Mode */
            --dyn-red-dark: #ef4444;  /* Strong Red for Dark Mode */
            --dyn-orange: #fdba74;    /* Light Orange for Dark Mode */
            --dyn-gray: #d1d5db;      /* Light Gray for Dark Mode */
            --dyn-emerald: #34d399;   /* Light Green for Dark Mode */
        }

        .light-mode {
            --bg-color: #f8fafc; --text-color: #0f172a; --vid-opacity: 0.15; 
            --vid-overlay: linear-gradient(to bottom, rgba(255,255,255,0.7), rgba(255,255,255,0.4), rgba(255,255,255,0.8)); 
            --card-bg: rgba(255, 255, 255, 0.65); --title-color: #1e293b;

            /* Deep, high-contrast text colors for Light Mode readability */
            --dyn-red: #991b1b;       /* Dark Red */
            --dyn-red-dark: #7f1d1d;  /* Very Dark Red */
            --dyn-orange: #9a3412;    /* Dark Orange */
            --dyn-gray: #1f2937;      /* Dark Gray */
            --dyn-emerald: #047857;   /* Dark Green */
        }

        /* Classes linking to the dynamic colors */
        .text-dyn-red { color: var(--dyn-red) !important; transition: color 0.3s ease; }
        .text-dyn-red-dark { color: var(--dyn-red-dark) !important; transition: color 0.3s ease; }
        .text-dyn-orange { color: var(--dyn-orange) !important; transition: color 0.3s ease; }
        .text-dyn-gray { color: var(--dyn-gray) !important; transition: color 0.3s ease; }
        .text-dyn-emerald { color: var(--dyn-emerald) !important; transition: color 0.3s ease; }

        /* Force inputs to obey light mode rules */
        .light-mode .lz-input { background-color: #ffffff !important; color: #0f172a !important; border-color: #94a3b8 !important; }
        .light-mode .lz-input:focus { border-color: #ef4444 !important; box-shadow: 0 0 10px rgba(239, 68, 68, 0.3) !important; }
        
        .light-mode table { color: #0f172a; }
        .light-mode th { border-bottom-color: #cbd5e1; color: #ffffff !important; } /* Kept headers white against dark bar */
        .light-mode td { border-color: #e2e8f0; }
        
        body { background-color: var(--bg-color); color: var(--text-color); transition: background-color 0.3s ease; }
        .theme-video { opacity: var(--vid-opacity); filter: sepia(100%) hue-rotate(300deg) saturate(500%); }
        .theme-overlay { background: var(--vid-overlay); }
        .theme-card { background: var(--card-bg) !important; transition: background 0.3s ease; }
        .theme-title { color: var(--title-color) !important; }
        
        .sc-anim { animation-fill-mode: both; transform: translate3d(0,0,0); }
        .sc-in-left { animation: slideInLeft 0.6s cubic-bezier(0.16, 1, 0.3, 1); } 
        .sc-in-right { animation: slideInRight 0.6s cubic-bezier(0.16, 1, 0.3, 1); } 
        .d-1 { animation-delay: 0.1s; } .d-2 { animation-delay: 0.2s; } .d-3 { animation-delay: 0.3s; }

        @keyframes slideInLeft { 0% { transform: translateX(-100vw); opacity: 0; } 100% { transform: translateX(0); opacity: 1; } }
        @keyframes slideInRight { 0% { transform: translateX(100vw); opacity: 0; } 100% { transform: translateX(0); opacity: 1; } }

        /* Custom Form Input Styling */
        .lz-input {
            background-color: #1a0000; border: 1px solid #991b1b; color: #ffffff;
            width: 100%; padding: 14px 16px; border-radius: 4px;
            font-family: 'Share Tech Mono', monospace; font-size: 1rem; font-weight: bold; transition: all 0.2s;
        }
        .lz-input:focus { outline: none; border-color: #ef4444; box-shadow: 0 0 10px rgba(239, 68, 68, 0.3); }

        .lz-radio {
            appearance: none; width: 24px; height: 24px; border: 2px solid #991b1b;
            border-radius: 50%; outline: none; cursor: pointer; transition: all 0.2s ease-in-out;
            position: relative; background: #000;
        }
        .lz-radio:checked { border-color: #ef4444; box-shadow: 0 0 10px rgba(239, 68, 68, 0.5); }
        .lz-radio:checked::before {
            content: ''; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);
            width: 12px; height: 12px; border-radius: 50%; background-color: #ef4444;
        }
        
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: rgba(0,0,0,0.2); }
        ::-webkit-scrollbar-thumb { background: rgba(220,38,38,0.5); border-radius: 3px; }

        /* --- MAXIMISE CARD FEATURE STYLES --- */
        #maximize-backdrop {
            display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.85);
            z-index: 90; backdrop-filter: blur(8px);
        }
        body.maximize-active { overflow: hidden; }
        body.maximize-active #maximize-backdrop { display: block; }
        
        .maximized-card {
            position: fixed !important; top: 5% !important; left: 5% !important; right: 5% !important; bottom: 5% !important;
            z-index: 100 !important; max-height: 90vh !important; overflow-y: auto !important;
            box-shadow: 0 0 50px rgba(239, 68, 68, 0.6) !important;
            display: flex; flex-direction: column;
        }
        .maximized-card #level5-form { display: flex; flex-direction: column; flex-grow: 1; }
        .maximized-card .text-area-wrapper { flex-grow: 1; display: flex; flex-direction: column; }
        .maximized-card #question { flex-grow: 1; min-height: 30vh; }
    </style>
</head>
<body class="min-h-screen relative overflow-x-hidden pb-10">

    <!-- Backdrop for Maximise mode -->
    <div id="maximize-backdrop"></div>

    <div class="fixed inset-0 z-0 overflow-hidden">
        <video autoplay loop muted playsinline class="theme-video w-full h-full object-cover">
            <source src="{{ asset('video/videoplayback.webm') }}" type="video/webm">
        </video>
        <div class="absolute inset-0 bg-red-900 mix-blend-color opacity-40 pointer-events-none"></div>
        <div class="theme-overlay absolute inset-0 pointer-events-none"></div>
        <div class="scanlines absolute inset-0 pointer-events-none opacity-50"></div>
    </div>

    <div class="fixed top-4 right-4 z-50 flex gap-3 sc-anim sc-in-right d-5">
        <button id="lang-toggle" class="theme-card backdrop-blur border border-red-500/50 px-3 py-1.5 rounded-full text-xs font-bold tracking-wider hover:bg-red-500 hover:text-white transition-colors text-red-400 shadow-[0_0_10px_rgba(239,68,68,0.3)]">
            🇲🇾 BAHASA
        </button>
        <button id="theme-toggle" class="theme-card backdrop-blur border border-red-500/50 px-3 py-1.5 rounded-full text-xs font-bold tracking-wider hover:bg-red-500 hover:text-white transition-colors text-red-400 shadow-[0_0_10px_rgba(239,68,68,0.3)]">
            ☀️ LIGHT MODE
        </button>
    </div>

    <div class="max-w-7xl mx-auto relative z-10 p-4 md:p-8 mt-4 md:mt-0">
        
        <header class="flex flex-col md:flex-row justify-between items-start md:items-end mb-8 pb-4 border-b border-red-500/50 sc-anim sc-in-left d-1">
            <div class="flex items-center gap-5">
                <div class="w-16 h-16 rounded-full bg-red-900 flex items-center justify-center border-2 border-red-500 shadow-[0_0_25px_rgba(220,38,38,0.8)] animate-pulse">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09A13.916 13.916 0 008 11a4 4 0 118 0c0 1.017-.07 2.019-.203 3m-2.118 6.844A21.88 21.88 0 0015.171 17m3.839 1.132c.645-2.266.99-4.659.99-7.132A8 8 0 008 4.07M3 15.364c.64-1.319 1-2.8 1-4.364 0-1.457.39-2.823 1.07-4" /></svg>
                </div>
                <div>
                    <h1 class="theme-title title-font text-3xl tracking-wider uppercase mb-1 text-red-500" data-en="S.H.I.E.L.D MAINFRAME" data-ms="MAINFRAME S.H.I.E.L.D">S.H.I.E.L.D MAINFRAME</h1>
                    <p class="text-red-400 font-bold tracking-widest text-sm uppercase" data-en="Rapid-Fire T/F Challenge" data-ms="Cabaran Pantas T/P">Rapid-Fire T/F Challenge</p>
                </div>
            </div>
            
            <a href="{{ route('admin.levels') }}" class="nav-trigger mt-4 md:mt-0 px-5 py-2 rounded-full theme-card border border-purple-500/50 text-purple-400 hover:bg-purple-500 hover:text-white transition-colors text-xs font-bold tracking-widest uppercase flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                <span data-en="Back to Hub" data-ms="Kembali ke Hab">Back to Hub</span>
            </a>
        </header>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
            
            <div class="flex flex-col gap-6 sc-anim sc-in-left d-2">
                
                <div class="theme-card backdrop-blur-md rounded-lg p-6 border border-orange-500/50 shadow-[0_0_20px_rgba(249,115,22,0.2)]">
                    <div class="flex items-center text-dyn-orange text-base md:text-lg mb-4 border-b border-orange-900/80 pb-2 font-extrabold tracking-widest uppercase">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" /></svg>
                        <span data-en="Challenge Parameters" data-ms="Parameter Cabaran">Challenge Parameters</span>
                    </div>

                    @if(session('settings_success'))
                        <div class="bg-orange-500/20 border border-orange-500 text-dyn-orange px-3 py-1.5 rounded mb-4 text-xs font-bold uppercase tracking-widest">
                            {{ session('settings_success') }}
                        </div>
                    @endif

                    <form action="{{ route('admin.level5.settings') }}" method="POST" class="space-y-4">
                        @csrf
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="text-xs text-dyn-orange font-bold uppercase tracking-widest block mb-1" data-en="Total Time (Mins)" data-ms="Jumlah Masa (Minit)">Total Time (Mins)</label>
                                <input type="number" name="total_minutes" value="{{ $settings->total_minutes }}" class="lz-input !border-orange-700" required>
                            </div>
                            <div>
                                <label class="text-xs text-dyn-orange font-bold uppercase tracking-widest block mb-1" data-en="Time per Q (Secs)" data-ms="Masa per Soalan (Saat)">Time per Q (Secs)</label>
                                <input type="number" name="seconds_per_question" value="{{ $settings->seconds_per_question }}" class="lz-input !border-orange-700" required>
                            </div>
                            <div>
                                <label class="text-xs text-dyn-orange font-bold uppercase tracking-widest block mb-1" data-en="Streak Target (Secs)" data-ms="Sasaran Streak (Saat)">Streak Target (Secs)</label>
                                <input type="number" name="streak_threshold_seconds" value="{{ $settings->streak_threshold_seconds }}" class="lz-input !border-orange-700" required>
                            </div>
                            <div>
                                <label class="text-xs text-dyn-orange font-bold uppercase tracking-widest block mb-1" data-en="Streak Bonus (%)" data-ms="Bonus Streak (%)">Streak Bonus (%)</label>
                                <input type="number" name="streak_bonus_percent" value="{{ $settings->streak_bonus_percent }}" class="lz-input !border-orange-700" required>
                            </div>
                            <div class="col-span-2">
                                <label class="text-xs text-dyn-red-dark font-extrabold uppercase tracking-widest block mb-1" data-en="Max Mistakes (Lives)" data-ms="Kesilapan Maksimum (Nyawa)">Max Mistakes (Lives)</label>
                                <input type="number" name="max_mistakes" value="{{ $settings->max_mistakes }}" class="lz-input" required>
                            </div>
                        </div>
                        <button type="submit" class="w-full bg-orange-900/50 hover:bg-orange-600 text-dyn-orange hover:text-white border border-orange-700 font-bold py-3 rounded transition-colors text-sm tracking-widest uppercase mt-4 shadow-[0_0_10px_rgba(249,115,22,0.3)]">
                            <span data-en="Update Logic" data-ms="Kemaskini Logik">Update Logic</span>
                        </button>
                    </form>
                </div>

                <!-- FORM CARD ADDED ID HERE -->
                <div id="form-card-container" class="theme-card backdrop-blur-md rounded-lg p-6 border border-red-900/80 shadow-[0_0_30px_rgba(153,27,27,0.4)] transition-all duration-300">
                    <div class="flex justify-between items-center mb-5 border-b border-red-900/80 pb-3 font-extrabold tracking-widest uppercase">
                        <div class="flex items-center text-dyn-red-dark text-base md:text-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                            <span id="form-header-text" data-en="New Flash Question" data-ms="Soalan Kilat Baru">New Flash Question</span>
                        </div>
                        
                        <!-- ADDED MAXIMISE BUTTON HERE -->
                        <button type="button" id="maximize-btn" class="text-dyn-red hover:text-white transition-colors ml-2" title="Toggle Fullscreen">
                            <svg id="max-icon" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4" /></svg>
                            <svg id="min-icon" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 14h4v4M4 14l5 5m11-5h-4v4m4-4l-5 5M4 10h4V6m-4 4l5-5m11 5h-4V6m4 4l-5-5" /></svg>
                        </button>
                    </div>

                    @if(session('success'))
                        <div class="bg-red-500/20 border border-red-500 text-dyn-red px-4 py-2 rounded mb-4 text-xs font-bold uppercase tracking-widest shadow-[0_0_10px_rgba(239,68,68,0.5)]">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form id="level5-form" action="{{ route('admin.level5.store') }}" method="POST" class="space-y-5">
                        @csrf
                        
                        <div class="text-area-wrapper">
                            <p class="text-sm font-extrabold text-dyn-red mb-2 uppercase tracking-widest" data-en="Rapid-Fire Statement" data-ms="Penyataan Pantas">Rapid-Fire Statement</p>
                            <textarea id="question" name="question" class="lz-input h-32 resize-none" placeholder="Contoh: Firewall sahaja sudah cukup untuk melindungi rangkaian syarikat." required></textarea>
                        </div>

                        <div class="bg-white/50 border border-red-900/50 p-4 rounded-lg mt-auto shrink-0">
                            <p class="text-xs text-dyn-red-dark font-extrabold tracking-widest uppercase mb-4 text-center" data-en="Correct Answer" data-ms="Jawapan Betul">Correct Answer</p>
                            
                            <div class="flex justify-center gap-8">
                                <label class="flex items-center gap-3 cursor-pointer p-2 rounded hover:bg-white/5 transition-colors">
                                    <input type="radio" name="is_true" value="1" class="lz-radio" required>
                                    <span class="text-dyn-emerald font-extrabold tracking-widest uppercase text-base" data-en="TRUE (BENAR)" data-ms="BENAR (TRUE)">TRUE (BENAR)</span>
                                </label>
                                
                                <label class="flex items-center gap-3 cursor-pointer p-2 rounded hover:bg-white/5 transition-colors">
                                    <input type="radio" name="is_true" value="0" class="lz-radio" required>
                                    <span class="text-dyn-red-dark font-extrabold tracking-widest uppercase text-base" data-en="FALSE (PALSU)" data-ms="PALSU (FALSE)">FALSE (PALSU)</span>
                                </label>
                            </div>
                        </div>

                        <div class="flex gap-3 pt-4 border-t border-red-900/50 mt-4 shrink-0">
                            <button type="submit" id="submit-btn" class="flex-1 bg-red-800 hover:bg-red-600 text-white border border-red-500 font-bold py-3 px-6 rounded transition-colors text-sm shadow-[0_0_15px_rgba(220,38,38,0.4)] tracking-widest uppercase">
                                <span data-en="Inject" data-ms="Suntik">Inject</span>
                            </button>
                            <button type="button" onclick="resetForm()" class="bg-transparent hover:bg-black/10 text-dyn-gray border border-gray-600 font-bold py-3 px-6 rounded transition-colors text-sm uppercase">
                                <span data-en="Cancel" data-ms="Batal">Cancel</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="lg:col-span-2 sc-anim sc-in-right d-3">
                <div class="theme-card backdrop-blur-md rounded-lg p-6 border border-red-900/50 shadow-[0_0_25px_rgba(153,27,27,0.2)] h-full max-h-[820px] flex flex-col">
                    <div class="flex justify-between items-center mb-5 border-b border-red-900/50 pb-3 shrink-0">
                        <div class="flex items-center text-dyn-red-dark text-base md:text-lg font-extrabold tracking-widest uppercase">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
                            <span><span data-en="Mainframe Question Pool" data-ms="Kolam Soalan Mainframe">Mainframe Question Pool</span> ({{ count($questions) }}/30)</span>
                        </div>
                    </div>

                    <div class="overflow-x-auto overflow-y-auto flex-1 min-h-0 pr-2">
                        <table class="w-full text-left min-w-full">
                            <thead class="uppercase tracking-widest text-xs text-white border-b border-red-900/50 sticky top-0 bg-black/90 backdrop-blur z-10">
                                <tr>
                                    <th class="px-4 py-4 w-2/3" data-en="Statement" data-ms="Penyataan">Statement</th>
                                    <th class="px-4 py-4 text-center whitespace-nowrap" data-en="Correct Answer" data-ms="Jawapan Betul">Correct Answer</th>
                                    <th class="px-4 py-4 text-right whitespace-nowrap" data-en="Action" data-ms="Tindakan">Action</th>
                                </tr>
                            </thead>
                            <tbody class="font-mono text-base font-bold opacity-90 divide-y divide-red-900/20">
                                
                                @forelse($questions as $q)
                                <tr class="hover:bg-red-900/20 transition-colors">
                                    <td class="px-4 py-4 text-dyn-gray truncate max-w-[300px] dynamic-q" data-ms="{{ $q->question }}">{{ $q->question }}</td>
                                    <td class="px-4 py-4 text-center whitespace-nowrap">
                                        @if($q->is_true)
                                            <span class="bg-emerald-900/50 text-dyn-emerald px-3 py-1.5 rounded text-xs uppercase font-extrabold border border-emerald-500/50" data-en="TRUE" data-ms="BENAR">TRUE</span>
                                        @else
                                            <span class="bg-red-900/50 text-dyn-red-dark px-3 py-1.5 rounded text-xs uppercase font-extrabold border border-red-500/50" data-en="FALSE" data-ms="PALSU">FALSE</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-4 text-right">
                                        <div class="flex justify-end gap-2 items-center flex-nowrap whitespace-nowrap">
                                            <button type="button" onclick='editQuestion(@json($q))' class="text-blue-500 hover:text-blue-400 text-xs uppercase font-bold tracking-widest bg-blue-900/20 px-3 py-1.5 rounded border border-blue-900/50 cursor-pointer transition-colors">
                                                <span data-en="Edit" data-ms="Sunting">Edit</span>
                                            </button>

                                            <form action="{{ route('admin.level5.destroy', $q->id) }}" method="POST" onsubmit="return confirm('Purge this statement from the Mainframe?');" class="inline-block m-0">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-500 hover:text-red-400 text-xs uppercase font-bold tracking-widest bg-red-900/30 px-3 py-1.5 rounded border border-red-700/50 cursor-pointer transition-colors hover:bg-red-900/60">
                                                    <span data-en="Delete" data-ms="Padam">Delete</span>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="px-4 py-10 text-center text-red-500/50 italic font-mono uppercase tracking-widest text-sm font-bold" data-en="Mainframe Data Banks Empty. Need 30 to run full simulation." data-ms="Bank Data Mainframe Kosong. Perlukan 30 untuk jalankan simulasi penuh.">Mainframe Data Banks Empty. Need 30 to run full simulation.</td>
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
        // --- 1. GOOGLE API AUTO-TRANSLATOR MAGIC ---
        async function translateGoogleAPI(text, targetLang) {
            let url = `https://translate.googleapis.com/translate_a/single?client=gtx&sl=auto&tl=${targetLang}&dt=t&q=${encodeURI(text)}`;
            try {
                let response = await fetch(url);
                let data = await response.json();
                return data[0][0][0]; 
            } catch(e) {
                console.error("Translation Failed:", e);
                return text; 
            }
        }

        async function processDynamicTranslations(lang) {
            const questionRows = document.querySelectorAll('.dynamic-q');
            
            for(let row of questionRows) {
                let originalMalay = row.getAttribute('data-ms');
                
                if(lang === 'en') {
                    let cachedEnglish = row.getAttribute('data-en-cached');
                    if(cachedEnglish) {
                        row.innerText = cachedEnglish;
                    } else {
                        row.innerText = "Translating..."; 
                        let englishTranslation = await translateGoogleAPI(originalMalay, 'en');
                        row.setAttribute('data-en-cached', englishTranslation);
                        row.innerText = englishTranslation;
                    }
                } else {
                    row.innerText = originalMalay;
                }
            }
        }

        // --- 2. STANDARD UI LANGUAGE LOGIC ---
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

            processDynamicTranslations(lang);
        }
        
        applyLanguage(currentLang);

        if(langBtn) {
            langBtn.addEventListener('click', () => {
                currentLang = currentLang === 'en' ? 'ms' : 'en';
                localStorage.setItem('shield_lang', currentLang);
                applyLanguage(currentLang);
            });
        }

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

       // --- 3. MAXIMISE CARD LOGIC ---
        const maxBtn = document.getElementById('maximize-btn');
        const formCard = document.getElementById('form-card-container');
        const maxIcon = document.getElementById('max-icon');
        const minIcon = document.getElementById('min-icon');
        const backdrop = document.getElementById('maximize-backdrop');
        
        // Create an invisible placeholder to remember where the card belongs
        const cardPlaceholder = document.createElement('div');
        cardPlaceholder.style.display = 'none';

        function toggleMaximize() {
            const isMaximized = document.body.classList.contains('maximize-active');

            if (!isMaximized) {
                // MAXIMIZE: Place the placeholder, then move the card to the body
                // This escapes the CSS transforms and z-index traps of the parent containers
                formCard.parentNode.insertBefore(cardPlaceholder, formCard);
                document.body.appendChild(formCard);
                
                document.body.classList.add('maximize-active');
                formCard.classList.add('maximized-card');
                maxIcon.classList.add('hidden');
                minIcon.classList.remove('hidden');
            } else {
                // MINIMIZE: Put the card back exactly where the placeholder is
                cardPlaceholder.parentNode.insertBefore(formCard, cardPlaceholder);
                
                document.body.classList.remove('maximize-active');
                formCard.classList.remove('maximized-card');
                maxIcon.classList.remove('hidden');
                minIcon.classList.add('hidden');
            }
        }

        if(maxBtn && formCard) {
            maxBtn.addEventListener('click', toggleMaximize);
        }

        // Close maximise if the dark background is clicked
        if(backdrop) {
            backdrop.addEventListener('click', () => {
                if(document.body.classList.contains('maximize-active')) {
                    toggleMaximize();
                }
            });
        }
        // --- 4. FORM LOGIC ---
        function editQuestion(q) {
            const form = document.getElementById('level5-form');
            form.action = `/admin/level5/update/${q.id}`;
            document.getElementById('question').value = q.question || '';
            const val = q.is_true ? "1" : "0";
            document.querySelector(`input[name="is_true"][value="${val}"]`).checked = true;
            
            document.getElementById('submit-btn').innerHTML = currentLang === 'ms' ? '<span data-en="Update Data" data-ms="Kemaskini Data">Kemaskini Data</span>' : '<span data-en="Update Data" data-ms="Kemaskini Data">Update Data</span>';
            document.getElementById('form-header-text').innerHTML = currentLang === 'ms' ? `<span data-en="Edit Flash Protocol (${q.id})" data-ms="Sunting Protokol Kilat (${q.id})">Sunting Protokol Kilat (${q.id})</span>` : `<span data-en="Edit Flash Protocol (${q.id})" data-ms="Sunting Protokol Kilat (${q.id})">Edit Flash Protocol (${q.id})</span>`;
            
            window.scrollTo({ top: 0, behavior: 'smooth' });
            applyLanguage(currentLang);
        }

        function resetForm() {
            const form = document.getElementById('level5-form');
            form.reset(); 
            form.action = "{{ route('admin.level5.store') }}";
            
            document.getElementById('submit-btn').innerHTML = currentLang === 'ms' ? '<span data-en="Inject" data-ms="Suntik">Suntik</span>' : '<span data-en="Inject" data-ms="Suntik">Inject</span>';
            document.getElementById('form-header-text').innerHTML = currentLang === 'ms' ? '<span data-en="New Flash Question" data-ms="Soalan Kilat Baru">Soalan Kilat Baru</span>' : '<span data-en="New Flash Question" data-ms="Soalan Kilat Baru">New Flash Question</span>';
            
            applyLanguage(currentLang);
        }

        // Exit Animation
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