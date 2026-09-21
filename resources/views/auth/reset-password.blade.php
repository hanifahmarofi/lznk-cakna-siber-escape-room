<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LZNK Cakna Siber // Update Clearance Key</title>
    @vite('resources/css/app.css')
    <link href="https://fonts.googleapis.com/css2?family=Anton&family=Poppins:wght@700&family=Share+Tech+Mono&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Share Tech Mono', monospace; }
        .animate-logo-hover { animation: logo-hover 3s ease-in-out infinite; }
        @keyframes logo-hover { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-10px); } }
        .title-font { font-family: 'Anton', sans-serif; text-shadow: 0 0 15px rgba(16,185,129,0.8); letter-spacing: 0.05em; }

        :root {
            --bg-color: #000000; --text-color: #d1d5db; --vid-opacity: 0.8;
            --vid-overlay: linear-gradient(to bottom, rgba(0,0,0,0.8), rgba(0,0,0,0.2), rgba(0,0,0,0.9));
            --card-bg: rgba(5, 5, 5, 0.6); --title-color: #34d399; 
        }

        .light-mode {
            --bg-color: #d1d5db; --text-color: #0f172a; --vid-opacity: 0.15;
            --vid-overlay: linear-gradient(to bottom, rgba(31, 41, 55, 0.9), rgba(156, 163, 175, 0.5), rgba(17, 24, 39, 0.95));
            --card-bg: linear-gradient(135deg, rgba(229, 231, 235, 0.95) 0%, rgba(209, 213, 219, 0.95) 50%, rgba(156, 163, 175, 0.9) 100%);
            --title-color: #064e3b; 
        }

        body { background-color: var(--bg-color); color: var(--text-color); transition: background-color 0.5s ease; }
        .theme-video { opacity: var(--vid-opacity); transition: opacity 0.5s ease; }
        .theme-overlay { background: var(--vid-overlay); transition: background 0.5s ease; }
        .theme-card { background: var(--card-bg) !important; transition: background 0.3s ease; }
        .theme-title { color: var(--title-color) !important; }
    </style>
</head>

<body class="min-h-screen flex flex-col items-center justify-center relative overflow-y-auto overflow-x-hidden transition-colors duration-500 py-12 md:py-0">

    <div class="fixed inset-0 z-0">
        <video autoplay loop muted playsinline class="theme-video w-full h-full object-cover">
            <source src="{{ asset('video/videoplayback.webm') }}" type="video/webm">
        </video>
        <div class="absolute inset-0 bg-emerald-600 mix-blend-color opacity-90 pointer-events-none"></div>
        <div class="theme-overlay absolute inset-0 pointer-events-none transition-background duration-500"></div>
    </div>

    <div class="z-10 w-full max-w-4xl flex flex-col items-center px-4 mt-10 md:mt-0">
        
        <div class="mb-6 flex flex-col items-center">
            <h1 class="theme-title title-font text-4xl md:text-5xl text-center leading-tight uppercase mb-2">
                <span class="text-white drop-shadow-md">KEY OVERRIDE PROTOCOL</span>
            </h1>
        </div>

        <div class="theme-card w-full max-w-md backdrop-blur-xl border border-emerald-500/50 rounded-lg p-8 relative overflow-hidden">
            <div class="relative z-10 flex items-center text-emerald-500 text-sm mb-6 border-b border-emerald-500/30 pb-3 font-bold tracking-wider">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
                UPDATE AUTHORIZATION KEY
            </div>

            <form method="POST" action="{{ route('password.reset.submit') }}" class="relative z-10 w-full flex flex-col gap-4">
                @csrf
                
                <input type="hidden" name="token" value="{{ $token }}">

                <div class="w-full">
                    <label class="block text-xs text-emerald-600 dark:text-emerald-400 mb-1 uppercase tracking-widest font-bold">Comm Link (Email)</label>
                    <input type="email" name="email" value="{{ request()->email }}" required autofocus class="w-full bg-black/50 border border-emerald-500/50 rounded px-4 py-2.5 text-sm text-emerald-100 focus:outline-none focus:border-emerald-400 focus:ring-1 focus:ring-emerald-400 transition-all placeholder:text-gray-500">
                </div>

                <div class="w-full">
                    <label class="block text-xs text-emerald-600 dark:text-emerald-400 mb-1 uppercase tracking-widest font-bold">New Authorization Key</label>
                    <div class="relative">
                        <input type="password" id="password" name="password" required class="w-full bg-black/50 border border-emerald-500/50 rounded px-4 py-2.5 text-sm text-emerald-100 focus:outline-none focus:border-emerald-400 focus:ring-1 focus:ring-emerald-400 transition-all placeholder:text-gray-500 pr-10">
                        
                        <button type="button" onclick="toggleVisibility('password', 'eye-open-1', 'eye-closed-1')" class="absolute inset-y-0 right-0 pr-3 flex items-center text-emerald-600 hover:text-emerald-400 focus:outline-none transition-colors">
                            <svg id="eye-open-1" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                            <svg id="eye-closed-1" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" /></svg>
                        </button>
                    </div>
                </div>

                <div class="w-full">
                    <label class="block text-xs text-emerald-600 dark:text-emerald-400 mb-1 uppercase tracking-widest font-bold">Confirm New Key</label>
                    <div class="relative">
                        <input type="password" id="password_confirmation" name="password_confirmation" required class="w-full bg-black/50 border border-emerald-500/50 rounded px-4 py-2.5 text-sm text-emerald-100 focus:outline-none focus:border-emerald-400 focus:ring-1 focus:ring-emerald-400 transition-all placeholder:text-gray-500 pr-10">
                        
                        <button type="button" onclick="toggleVisibility('password_confirmation', 'eye-open-2', 'eye-closed-2')" class="absolute inset-y-0 right-0 pr-3 flex items-center text-emerald-600 hover:text-emerald-400 focus:outline-none transition-colors">
                            <svg id="eye-open-2" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                            <svg id="eye-closed-2" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" /></svg>
                        </button>
                    </div>
                </div>

                @if ($errors->any())
                <div class="w-full text-center mt-2">
                    <span class="text-red-500 text-xs font-bold tracking-widest drop-shadow-[0_0_5px_rgba(239,68,68,0.8)]">
                        {{ $errors->first() }}
                    </span>
                </div>
                @endif

                <button type="submit" class="w-full mt-4 bg-emerald-600 hover:bg-emerald-500 text-white font-bold py-3 px-4 rounded shadow-[0_0_20px_rgba(16,185,129,0.4)] hover:shadow-[0_0_30px_rgba(16,185,129,0.7)] transition-all tracking-widest border border-emerald-400/50">
                    OVERRIDE KEY
                </button>
            </form>
        </div>
    </div>

    <script>
        function toggleVisibility(inputId, openIconId, closedIconId) {
            const input = document.getElementById(inputId);
            const eyeOpen = document.getElementById(openIconId);
            const eyeClosed = document.getElementById(closedIconId);
            
            if (input.type === 'password') {
                input.type = 'text';
                eyeOpen.classList.add('hidden');
                eyeClosed.classList.remove('hidden');
            } else {
                input.type = 'password';
                eyeOpen.classList.remove('hidden');
                eyeClosed.classList.add('hidden');
            }
        }
    </script>
    @include('partials.cursor')
</body>
</html>