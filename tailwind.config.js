/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
    ],
    theme: {
        extend: {
            // --- ADD THIS SECTION ---
            keyframes: {
                'lava-movement': {
                    '0%, 100%': { transform: 'translate(0, 0) scale(1)' },
                    '33%': { transform: 'translate(30vw, -20vh) scale(1.1)' },
                    '66%': { transform: 'translate(-20vw, 30vh) scale(0.9)' },
                },
            },
            animation: {
                'lava-blob': 'lava-movement 40s ease-in-out infinite', // Very slow loop
            },
            // ------------------------
        },
    },
    plugins: [],
};