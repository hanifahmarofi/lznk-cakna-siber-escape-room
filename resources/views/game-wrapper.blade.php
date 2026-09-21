<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Human Firewall : Cakna Siber : S.H.I.E.L.D</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    @vite('resources/css/app.css')
    <link href="https://fonts.googleapis.com/css2?family=Share+Tech+Mono&display=swap" rel="stylesheet">

    <style>
        body,
        html {
            margin: 0;
            padding: 0;
            height: 100%;
            overflow: hidden;
            background: #000;
            cursor: none !important;
        }

        body *,
        iframe {
            cursor: none !important;
        }

        iframe {
            width: 100vw;
            height: 100vh;
            border: none;
        }

        #master-cursor {
            opacity: 0;
            pointer-events: none;
            position: fixed;
            z-index: 2147483647;
            width: 32px;
            height: 32px;
            background-image: url("{{ asset('img/pointer.gif') }}");
            background-size: contain;
            background-repeat: no-repeat;
            will-change: left, top;
        }
    </style>
</head>

<body>
    <div id="master-cursor"></div>

    <div id="init-overlay" class="fixed inset-0 z-[9999] bg-black flex flex-col items-center justify-center transition-opacity duration-700">
        <div class="absolute inset-0 pointer-events-none" style="background: linear-gradient(to bottom, rgba(255,255,255,0), rgba(255,255,255,0) 50%, rgba(0,0,0,0.15) 50%, rgba(0,0,0,0.15)); background-size: 100% 4px;"></div>

        <h1 class="font-mono text-emerald-500 text-2xl md:text-4xl mb-8 tracking-widest uppercase animate-pulse text-center px-4" style="text-shadow: 0 0 15px rgba(16,185,129,0.8);">
            System Standby: Awaiting User Authorization
        </h1>

        <button type="button" id="init-btn" class="relative z-50 bg-emerald-900/40 hover:bg-emerald-600 text-emerald-500 hover:text-white border-2 border-emerald-600 font-bold py-4 px-8 rounded transition-all uppercase tracking-widest text-xl shadow-[0_0_20px_rgba(16,185,129,0.5)] cursor-none">
            Initialize Mainframe
        </button>
    </div>

    <button id="global-mute-btn" class="fixed bottom-6 left-6 z-[9998] bg-black/70 border border-emerald-500/50 text-emerald-500 hover:bg-emerald-500 hover:text-black p-3 rounded-full backdrop-blur-md transition-all shadow-[0_0_15px_rgba(16,185,129,0.3)] cursor-none hidden">
    </button>

    <audio id="bg-music" src="{{ asset('audio/cod-theme.mp3') }}" loop preload="auto" crossorigin="anonymous"></audio>
    <audio id="ui-click-sound" src="{{ asset('audio/mixkit-sci-fi-click-900.wav') }}" preload="auto"></audio>

    <iframe id="game-frame" src="{{ route('login') }}"></iframe>

    <script>
        // --- MASTER CURSOR LOGIC ---
        const masterCursor = document.getElementById('master-cursor');

        let cursorX = window.innerWidth / 2;
        let cursorY = window.innerHeight / 2;
        let targetX = cursorX;
        let targetY = cursorY;

        window.addEventListener('pointermove', function(e) {
            targetX = e.clientX;
            targetY = e.clientY;
            masterCursor.style.opacity = '1';
        });

        function animateCursor() {
            cursorX += (targetX - cursorX) * 0.8;
            cursorY += (targetY - cursorY) * 0.8;

            masterCursor.style.left = (cursorX - 2) + 'px';
            masterCursor.style.top = (cursorY - 2) + 'px';

            requestAnimationFrame(animateCursor);
        }

        animateCursor();

        // --- AUDIO LOGIC ---
        const overlay = document.getElementById('init-overlay');
        const initBtn = document.getElementById('init-btn');
        const bgMusic = document.getElementById('bg-music');
        const clickSound = document.getElementById('ui-click-sound');
        const muteBtn = document.getElementById('global-mute-btn');
        const gameFrame = document.getElementById('game-frame');

        clickSound.volume = 0.6;

        let audioCtx;
        let gainNode;
        let isBoosted = false;
        let isMusicPlaying = false;
        let isMuted = localStorage.getItem('shield_is_muted') === 'true';

        bgMusic.muted = isMuted;

        const iconVolUp = `
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M11 5L6 9H2v6h4l5 4V5z" />
            </svg>
        `;

        const iconVolOff = `
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z"
                    clip-rule="evenodd" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M17 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2" />
            </svg>
        `;

        function updateMuteButton() {
            muteBtn.innerHTML = isMuted ? iconVolOff : iconVolUp;

            if (isMuted) {
                muteBtn.classList.replace('text-emerald-500', 'text-red-500');
                muteBtn.classList.replace('border-emerald-500/50', 'border-red-500/50');
            } else {
                muteBtn.classList.replace('text-red-500', 'text-emerald-500');
                muteBtn.classList.replace('border-red-500/50', 'border-emerald-500/50');
            }
        }

        function sendMuteStateToIframe() {
            if (gameFrame && gameFrame.contentWindow) {
                gameFrame.contentWindow.postMessage({
                    type: 'syncMute',
                    isMuted: isMuted
                }, '*');
            }
        }

        updateMuteButton();

        muteBtn.addEventListener('click', function() {
            clickSound.currentTime = 0;
            clickSound.play().catch(function() {});

            isMuted = !isMuted;

            bgMusic.muted = isMuted;
            localStorage.setItem('shield_is_muted', isMuted ? 'true' : 'false');

            updateMuteButton();
            sendMuteStateToIframe();
        });

        initBtn.addEventListener('click', function() {
            clickSound.currentTime = 0;
            clickSound.play().catch(function() {});

            overlay.classList.add('opacity-0');
            muteBtn.classList.remove('hidden');

            setTimeout(function() {
                overlay.remove();
            }, 700);

            if (!isBoosted) {
                audioCtx = new (window.AudioContext || window.webkitAudioContext)();

                const source = audioCtx.createMediaElementSource(bgMusic);
                gainNode = audioCtx.createGain();

                gainNode.gain.value = 7.0;

                source.connect(gainNode);
                gainNode.connect(audioCtx.destination);

                isBoosted = true;
            }

            if (audioCtx.state === 'suspended') {
                audioCtx.resume();
            }

            if (!isMuted) {
                bgMusic.play().then(function() {
                    isMusicPlaying = true;
                }).catch(function() {});
            }

            sendMuteStateToIframe();
        });

        // --- IFRAME MESSAGE LISTENER ---
        window.addEventListener('message', function(e) {
            // Cursor position from iframe pages
            if (e.data && e.data.type === 'iframeMouseMove') {
                targetX = e.data.x;
                targetY = e.data.y;
                masterCursor.style.opacity = '1';
            }

            // Child page asks current mute state
            if (e.data && e.data.type === 'requestMuteState') {
                sendMuteStateToIframe();
            }

            // Room page tells lobby music to stop
            if (e.data === 'stopMusic' || e.data === 'pauseMusic') {
                isMusicPlaying = false;
                bgMusic.pause();
                bgMusic.currentTime = 0;
            }

            // Other pages can request lobby music again
            if (e.data === 'ensureMusicPlaying') {
                if (isMusicPlaying || isMuted) return;

                bgMusic.currentTime = 0;
                bgMusic.play().then(function() {
                    isMusicPlaying = true;
                }).catch(function() {});
            }
        });
    </script>
</body>
</html>