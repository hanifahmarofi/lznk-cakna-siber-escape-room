<div id="custom-gif-cursor" style="opacity: 0; pointer-events: none; position: fixed; z-index: 2147483647;"></div>

<style>
    /* Force hide the native cursor on ALL elements globally */
    *, *::before, *::after {
        cursor: none !important;
    }

    /* Pierce the Shadow DOM to hide cursors on native HTML5 video controls */
    video::-webkit-media-controls,
    video::-webkit-media-controls-enclosure,
    video::-webkit-media-controls-panel,
    video::-webkit-media-controls-play-button,
    video::-webkit-media-controls-timeline,
    video::-webkit-media-controls-mute-button,
    video::-webkit-media-controls-volume-slider,
    video::-webkit-media-controls-fullscreen-button,
    video::-webkit-media-controls-current-time-display,
    video::-webkit-media-controls-time-remaining-display,
    video::-internal-media-controls-overlay-cast-button {
        cursor: none !important;
    }

    #custom-gif-cursor {
        width: 32px; 
        height: 32px; 
        background-image: url("{{ asset('img/pointer.gif') }}");
        background-size: contain;
        background-repeat: no-repeat;
        will-change: left, top;
        transition: opacity 0.1s ease;
    }
</style>

<script>
    (function() {
        // Prevent duplicate cursors in the same window
        if (window.scCursorInitialized) return;
        window.scCursorInitialized = true;

        const cursor = document.getElementById('custom-gif-cursor');
        if (!cursor) return;

        // 1. Check if we are running inside the game-wrapper iframe
        const inIframe = window.self !== window.top;

        if (inIframe) {
            // IFRAME MODE: Hide local cursor and broadcast coordinates to the parent
            cursor.style.display = 'none';

            window.addEventListener('pointermove', (e) => {
                window.parent.postMessage({
                    type: 'iframeMouseMove',
                    x: e.clientX,
                    y: e.clientY
                }, '*');
            }, { passive: true });
            
        } else {
            // STANDALONE MODE: If page is opened directly without iframe, use local cursor
            let mouseX = 0, mouseY = 0;
            let cursorX = 0, cursorY = 0;

            window.addEventListener('pointermove', (e) => {
                mouseX = e.clientX;
                mouseY = e.clientY;
                if (cursor.style.opacity === '0') cursor.style.opacity = '1';
            }, { passive: true });

            // Animation loop
            function animateCursor() {
                cursorX += (mouseX - cursorX) * 0.8;
                cursorY += (mouseY - cursorY) * 0.8;
                
                cursor.style.left = (cursorX - 2) + 'px';
                cursor.style.top = (cursorY - 2) + 'px';
                
                requestAnimationFrame(animateCursor);
            }
            animateCursor();
        }
    })();
</script>