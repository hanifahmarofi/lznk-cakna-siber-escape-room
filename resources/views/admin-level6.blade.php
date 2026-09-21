<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin // Level 6 Mini-Games</title>
    @vite('resources/css/app.css')
    <link href="https://fonts.googleapis.com/css2?family=Share+Tech+Mono&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-color: #000000;
            --text-color: #d1d5db;
            --card-bg: rgba(17, 24, 39, 0.5); /* gray-900/50 */
            --input-bg: #0a0a0a;
            --modal-bg: #0f172a;
        }

        .light-mode {
            --bg-color: #e5e7eb;
            --text-color: #0f172a;
            --card-bg: rgba(255, 255, 255, 0.9);
            --input-bg: #f8fafc;
            --modal-bg: #f1f5f9;
        }

        body { font-family: 'Share Tech Mono', monospace; background-color: var(--bg-color); color: var(--text-color); transition: background-color 0.5s; }
        
        .lz-input { background-color: var(--input-bg); border: 1px solid #065f46; color: var(--text-color); width: 100%; padding: 10px; border-radius: 4px; transition: all 0.3s; }
        .lz-input:focus { outline: none; border-color: #10b981; box-shadow: 0 0 10px rgba(16, 185, 129, 0.3); }
        
        /* Ensures inputs don't stretch vertically and stay uniform */
        input.lz-input, select.lz-input { height: 44px; }
        textarea.lz-input { min-height: 80px; resize: vertical; }

        .game-config { display: none; }
        .game-config.active { display: block; animation: fadeIn 0.3s; }
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }

        /* Modal Styles */
        .modal-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.85); backdrop-filter: blur(5px); z-index: 50; align-items: center; justify-content: center; padding: 20px; }
        .modal-overlay.active { display: flex; }
        .modal-content { background: var(--modal-bg); border: 2px solid #10b981; width: 100%; max-width: 800px; max-height: 90vh; overflow-y: auto; border-radius: 8px; position: relative; transition: background-color 0.5s; }

        /* Custom backgrounds for specific elements to match themes */
        .legend-bg { background-color: #0f172a; }
        .checkbox-box { background-color: rgba(0,0,0,0.3); border-color: #1f2937; }

        /* Light Mode Specific Overrides */
        .light-mode .text-emerald-400, .light-mode .text-emerald-500 { color: #047857 !important; }
        .light-mode .border-emerald-500\/30 { border-color: #10b981 !important; }
        .light-mode .text-gray-200, .light-mode .text-gray-300, .light-mode .text-gray-400 { color: #1e293b !important; }
        .light-mode .bg-gray-900\/50, .light-mode .bg-black\/40 { background-color: var(--card-bg) !important; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); }
        .light-mode th { color: #047857 !important; border-bottom-color: #cbd5e1 !important; }
        .light-mode td { border-color: #e2e8f0 !important; color: #0f172a !important; }
        .light-mode tr:hover { background-color: rgba(0,0,0,0.05) !important; }
        
        .light-mode .legend-bg { background-color: #ffffff; color: #7e22ce !important; border-color: #c084fc !important; }
        .light-mode .checkbox-box { background-color: #f8fafc; border-color: #cbd5e1; }

        /* Top right button theme matching */
        .theme-card { background: rgba(15, 10, 20, 0.85); backdrop-filter: blur(8px); }
        .light-mode .theme-card { background: rgba(255, 255, 255, 0.95); }
    </style>
</head>
<body class="p-4 md:p-8 transition-colors duration-500">

    <div class="fixed top-4 right-4 z-50 flex gap-3">
        <button id="lang-toggle" class="theme-card border border-emerald-500/50 px-3 py-1.5 rounded-full text-xs font-bold tracking-wider hover:bg-emerald-500 hover:text-white transition-colors text-emerald-500">
            🇲🇾 BAHASA
        </button>
        <button id="theme-toggle" class="theme-card border border-emerald-500/50 px-3 py-1.5 rounded-full text-xs font-bold tracking-wider hover:bg-emerald-500 hover:text-white transition-colors text-emerald-500">
            ☀️ LIGHT MODE
        </button>
    </div>

    <div class="max-w-6xl mx-auto mt-10 md:mt-0">
        <div class="flex justify-between items-center mb-8 border-b border-emerald-500/30 pb-4">
            <h1 class="text-xl md:text-3xl text-emerald-500 font-bold tracking-widest uppercase drop-shadow-[0_0_10px_rgba(16,185,129,0.5)]" data-en="Level 6: Mini-Game Arcade" data-ms="Tahap 6: Arked Permainan Mini">Level 6: Mini-Game Arcade</h1>
            <a href="{{ route('admin.dashboard') }}" class="bg-gray-800 hover:bg-gray-700 border border-gray-600 px-4 py-2 rounded text-[10px] md:text-sm uppercase font-bold tracking-widest text-white transition-colors" data-en="Back to Admin" data-ms="Kembali ke Admin">Back to Admin</a>
        </div>

        @if(session('success'))
            <div class="bg-emerald-900/50 border border-emerald-500 text-emerald-400 px-4 py-3 rounded mb-6 uppercase tracking-widest font-bold text-xs text-center">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-gray-900/50 border border-emerald-500/30 p-6 rounded-lg mb-10 shadow-[0_0_20px_rgba(16,185,129,0.1)]">
            <h2 class="text-xl text-emerald-400 font-bold mb-6 uppercase tracking-widest" data-en="Deploy New Mini-Game" data-ms="Kerahkan Permainan Mini Baru">Deploy New Mini-Game</h2>
            <form action="{{ route('admin.level6.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div id="create-form-fields"></div>
                <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-500 text-black font-extrabold py-3 rounded uppercase tracking-widest text-lg shadow-[0_0_15px_rgba(16,185,129,0.4)] mt-4" data-en="Initialize Mini-Game" data-ms="Mula Permainan Mini">Initialize Mini-Game</button>
            </form>
        </div>

        <div class="mt-12 mb-10">
            <h2 class="text-xl text-emerald-400 font-bold mb-4 uppercase tracking-widest border-b border-emerald-500/30 pb-2" data-en="Deployed Modules" data-ms="Modul Dikerahkan">Deployed Modules</h2>
            <div class="bg-gray-900/50 border border-emerald-500/30 rounded-lg overflow-hidden">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-emerald-900/30 text-emerald-500 text-[10px] uppercase tracking-widest border-b border-emerald-500/50">
                            <th class="p-4" data-en="Title" data-ms="Tajuk">Title</th>
                            <th class="p-4" data-en="Engine" data-ms="Enjin">Engine</th>
                            <th class="p-4" data-en="Base Score" data-ms="Markah Asas">Base Score</th>
                            <th class="p-4 text-right" data-en="Actions" data-ms="Tindakan">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm font-mono">
                        @forelse($miniGames as $game)
                            <tr class="border-b border-gray-800 hover:bg-gray-800/50 transition-colors">
                                <td class="p-4 text-gray-200 font-bold">{{ $game->title }}</td>
                                <td class="p-4 text-purple-400 uppercase tracking-widest text-xs">{{ str_replace('_', ' ', $game->game_type) }}</td>
                                <td class="p-4 text-emerald-400 font-bold">{{ $game->base_score }} PTS</td>
                                <td class="p-4 text-right flex justify-end gap-2">
                                    <button onclick="openEditModal({{ $game->id }})" type="button" class="bg-blue-900/50 hover:bg-blue-600 text-blue-400 hover:text-white px-3 py-1.5 rounded text-[10px] font-bold uppercase tracking-widest transition-colors border border-blue-500/50" data-en="Edit" data-ms="Sunting">Edit</button>
                                    <form action="{{ route('admin.level6.destroy', $game->id) }}" method="POST" onsubmit="return confirm('Purge module?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="bg-red-900/50 hover:bg-red-600 text-red-400 hover:text-white px-3 py-1.5 rounded text-[10px] font-bold uppercase tracking-widest transition-colors border border-red-500/50" data-en="Purge" data-ms="Padam">Purge</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="p-8 text-center text-gray-500 italic" data-en="No modules initialized." data-ms="Tiada modul dimulakan.">No modules initialized.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div id="editModal" class="modal-overlay">
        <div class="modal-content p-6 md:p-10">
            <div class="flex justify-between items-center mb-6 border-b border-emerald-500/30 pb-4">
                <h2 class="text-xl text-emerald-400 font-bold uppercase tracking-widest" data-en="Update Parameters" data-ms="Kemaskini Parameter">Update Parameters</h2>
                <button type="button" onclick="closeEditModal()" class="text-red-500 font-bold hover:text-red-400" data-en="&times; CLOSE" data-ms="&times; TUTUP">&times; CLOSE</button>
            </div>
            <form id="editForm" method="POST" enctype="multipart/form-data">
                @csrf
                <div id="edit-form-fields"></div>
                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-500 text-white font-extrabold py-3 rounded uppercase tracking-widest text-lg mt-6" data-en="Apply Updates" data-ms="Guna Kemaskini">Apply Updates</button>
            </form>
        </div>
    </div>

    <script id="form-template" type="text/template">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label class="block text-xs text-emerald-600 mb-1 uppercase font-bold tracking-widest" data-en="Game Title" data-ms="Tajuk Permainan">Game Title</label>
                <input type="text" name="title" id="title_FORM_ID" required class="lz-input">
            </div>
            <div>
                <label class="block text-xs text-emerald-600 mb-1 uppercase font-bold tracking-widest" data-en="Base Score" data-ms="Markah Asas">Base Score</label>
                <input type="number" name="base_score" id="base_score_FORM_ID" required class="lz-input">
            </div>
            <div>
                <label class="block text-xs text-emerald-600 mb-1 uppercase font-bold tracking-widest" data-en="Engine Type" data-ms="Jenis Enjin">Engine Type</label>
                <select name="game_type" id="game_type_FORM_ID" required class="lz-input" onchange="switchGameConfig(this.value, 'FORM_ID')">
                    <option value="scramble">Word Scramble</option>
                    <option value="domino">Falling Dominoes</option>
                    <option value="connect">Connect the Dots</option>
                    <option value="video_abcd">Video Analysis</option>
                    <option value="chess">Cyber Chess</option>
                </select>
            </div>
            <div>
                <label class="block text-xs text-transparent mb-1 select-none pointer-events-none">Spacer</label>
                <label class="checkbox-box flex items-center cursor-pointer p-2.5 rounded border w-full hover:border-gray-500 transition-colors" style="height: 44px;">
                    <input type="checkbox" name="is_active" id="is_active_FORM_ID" value="1" class="h-5 w-5 bg-black border-emerald-500 text-emerald-500 rounded focus:ring-emerald-500">
                    <span class="ml-3 text-sm text-gray-300 uppercase tracking-widest font-bold" data-en="Visible to Agents" data-ms="Dapat Dilihat Oleh Ejen">Visible to Agents</span>
                </label>
            </div>
        </div>
        
        <div class="mb-6">
            <label class="block text-xs text-emerald-600 mb-1 uppercase font-bold tracking-widest" data-en="Briefing Instructions" data-ms="Arahan Taklimat">Briefing Instructions</label>
            <textarea name="instruction" id="instruction_FORM_ID" rows="3" class="lz-input"></textarea>
        </div>

        <fieldset class="border border-purple-500/50 p-5 rounded bg-purple-900/10 mb-6 shadow-inner">
            <legend class="legend-bg px-3 py-1 text-[10px] text-purple-400 font-bold uppercase tracking-widest border border-purple-500/50 rounded" data-en="Engine Data Configuration" data-ms="Konfigurasi Data Enjin">Engine Data Configuration</legend>
            
            <div id="config-scramble-FORM_ID" class="game-config mt-2">
                <button type="button" onclick="addScrambleWord('FORM_ID')" class="bg-purple-600 hover:bg-purple-500 transition text-white text-[10px] font-bold tracking-widest uppercase px-3 py-2 mb-4 rounded shadow-md" data-en="+ Add Word" data-ms="+ Tambah Perkataan">+ Add Word</button>
                <div id="scramble-container-FORM_ID"></div>
            </div>

            <div id="config-domino-FORM_ID" class="game-config mt-2">
                <div class="mb-4 checkbox-box p-4 rounded border inline-block w-full md:w-auto">
                    <label class="block text-[10px] text-purple-400 uppercase mb-2 font-bold tracking-widest" data-en="Max Mistakes Allowed" data-ms="Maksimum Kesilapan Dibenarkan">Max Mistakes Allowed</label>
                    <input type="number" name="game_data[max_mistakes]" id="domino_mistakes_FORM_ID" class="lz-input w-full md:w-32">
                </div>
                <br>
                <button type="button" onclick="addDominoPhrase('FORM_ID')" class="bg-purple-600 hover:bg-purple-500 transition text-white text-[10px] font-bold tracking-widest uppercase px-3 py-2 mb-4 rounded shadow-md" data-en="+ Add Phrase" data-ms="+ Tambah Frasa">+ Add Phrase</button>
                <div id="domino-container-FORM_ID"></div>
            </div>

            <div id="config-connect-FORM_ID" class="game-config mt-2">
                <button type="button" onclick="addConnectPair('FORM_ID')" class="bg-purple-600 hover:bg-purple-500 transition text-white text-[10px] font-bold tracking-widest uppercase px-3 py-2 mb-4 rounded shadow-md" data-en="+ Add Match" data-ms="+ Tambah Padanan">+ Add Match</button>
                <div id="connect-container-FORM_ID"></div>
            </div>

            <div id="config-video_abcd-FORM_ID" class="game-config mt-2">
                
                <div class="bg-purple-900/20 border border-purple-500/40 p-4 rounded-lg mb-6 shadow-md">
                    <h3 class="text-xs text-purple-400 font-bold uppercase tracking-widest mb-3 border-b border-purple-500/30 pb-2" data-en="Default Starting Video" data-ms="Video Mula Lalai">Default Starting Video</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] text-gray-400 uppercase mb-1" data-en="YouTube / Link URL" data-ms="URL YouTube / Pautan">YouTube / Link URL</label>
                            <input type="text" name="game_data[video_url]" id="video_url_FORM_ID" class="lz-input text-sm">
                        </div>
                        <div>
                            <label class="block text-[10px] text-gray-400 uppercase mb-1" data-en="OR Direct MP4 Upload" data-ms="ATAU Muat Naik MP4 Langsung">OR Direct MP4 Upload</label>
                            <input type="file" name="game_data[video_file]" id="video_file_FORM_ID" accept="video/mp4,video/webm" class="lz-input text-sm" style="padding: 7px;">
                        </div>
                    </div>
                </div>
                
                <div class="flex justify-between items-end border-b border-gray-700 pb-2 mb-4">
                    <h3 class="text-sm text-gray-300 font-bold uppercase tracking-widest" data-en="Question Sequence" data-ms="Urutan Soalan">Question Sequence</h3>
                    <button type="button" onclick="addVideoQuestion('FORM_ID')" class="bg-emerald-600 hover:bg-emerald-500 transition text-black font-extrabold text-[10px] tracking-widest uppercase px-4 py-2 rounded shadow-[0_0_10px_rgba(16,185,129,0.4)]" data-en="+ Add Question" data-ms="+ Tambah Soalan">+ Add Question</button>
                </div>
                
                <div id="video-container-FORM_ID" class="space-y-6"></div>
            </div>

            <div id="config-chess-FORM_ID" class="game-config mt-2">
                <div class="flex justify-between items-end border-b border-gray-700 pb-2 mb-4">
                    <h3 class="text-sm text-gray-300 font-bold uppercase tracking-widest" data-en="Chess Sequence" data-ms="Urutan Catur">Chess Sequence</h3>
                    <button type="button" onclick="addChessQuestion('FORM_ID')" class="bg-emerald-600 hover:bg-emerald-500 transition text-black font-extrabold text-[10px] tracking-widest uppercase px-4 py-2 rounded shadow-[0_0_10px_rgba(16,185,129,0.4)]" data-en="+ Add Move" data-ms="+ Tambah Pergerakan">+ Add Move</button>
                </div>
                <div id="chess-container-FORM_ID" class="space-y-6"></div>
            </div>
        </fieldset>
    </script>

    <script>
        // --- TRANSLATION AND THEME LOGIC ---
        const bodyEl = document.body;
        const themeBtn = document.getElementById('theme-toggle');
        const langBtn = document.getElementById('lang-toggle');

        let currentTheme = localStorage.getItem('shield_theme') || 'dark';
        let currentLang = localStorage.getItem('shield_lang') || 'en';

        if (currentTheme === 'light') { 
            bodyEl.classList.add('light-mode'); 
            if(themeBtn) themeBtn.innerHTML = '🌙 DARK MODE'; 
        }

        function applyLanguage(lang) {
            if(langBtn) langBtn.innerHTML = lang === 'en' ? '🇲🇾 BAHASA' : '🇬🇧 ENGLISH';
            document.querySelectorAll('[data-en]').forEach(el => {
                const text = el.getAttribute(`data-${lang}`);
                if (el.tagName === 'INPUT' && el.type === 'button') {
                    el.value = text;
                } else if (el.tagName === 'INPUT' || el.tagName === 'TEXTAREA') {
                    // Not modifying placeholders strictly right now, but text inside elements
                } else {
                    const childSpan = el.querySelector('span:not([data-en])');
                    if (childSpan) childSpan.innerText = text;
                    else el.innerText = text;
                }
            });
        }
        applyLanguage(currentLang);

        if(themeBtn) {
            themeBtn.addEventListener('click', () => {
                currentTheme = currentTheme === 'dark' ? 'light' : 'dark';
                localStorage.setItem('shield_theme', currentTheme);
                if(currentTheme === 'light') { bodyEl.classList.add('light-mode'); themeBtn.innerHTML = '🌙 DARK MODE'; } 
                else { bodyEl.classList.remove('light-mode'); themeBtn.innerHTML = '☀️ LIGHT MODE'; }
            });
        }

        if(langBtn) {
            langBtn.addEventListener('click', () => {
                currentLang = currentLang === 'en' ? 'ms' : 'en';
                localStorage.setItem('shield_lang', currentLang);
                applyLanguage(currentLang);
            });
        }

        // --- GAME CONFIGURATION LOGIC ---
        const allGames = @json($miniGames);
        let counters = { scramble: 0, domino: 0, connect: 0, video: 0, chess: 0 };

        function initForms() { 
            renderForm('create', 'create-form-fields'); 
            const defaultType = document.getElementById('game_type_create').value;
            switchGameConfig(defaultType, 'create');
        }

        function renderForm(formId, targetDivId) {
            let template = document.getElementById('form-template').innerHTML;
            template = template.replace(/FORM_ID/g, formId);
            document.getElementById(targetDivId).innerHTML = template;
            applyLanguage(currentLang); // Translate the newly rendered template
        }

        function switchGameConfig(gameType, formId) {
            const configs = document.querySelectorAll(`[id^="config-"][id$="-${formId}"]`);
            configs.forEach(c => {
                c.classList.remove('active');
                c.querySelectorAll('input, textarea, select').forEach(i => i.disabled = true);
            });
            const active = document.getElementById(`config-${gameType}-${formId}`);
            if (active) {
                active.classList.add('active');
                active.querySelectorAll('input, textarea, select').forEach(i => i.disabled = false);
            }
        }

        // --- SMALLER GAME ENGINES ---
        function addScrambleWord(fid, val = {word:'', hint:''}) {
            const id = counters.scramble++;
            const html = `<div class="mb-3 p-4 checkbox-box rounded relative group transition">
                <button type="button" onclick="this.parentElement.remove()" class="absolute right-3 top-3 text-red-500 text-xs font-bold bg-red-900/30 px-2 rounded opacity-50 group-hover:opacity-100 transition">X</button>
                <input type="text" name="game_data[words][${id}][word]" value="${val.word || ''}" class="lz-input mb-2 uppercase text-lg font-bold" placeholder="WORD">
                <input type="text" name="game_data[words][${id}][hint]" value="${val.hint || ''}" class="lz-input text-sm" placeholder="Hint Description...">
            </div>`;
            document.getElementById(`scramble-container-${fid}`).insertAdjacentHTML('beforeend', html);
        }

        function addDominoPhrase(fid, val = {phrase:'', hint:''}) {
            const id = counters.domino++;
            const html = `<div class="mb-3 p-4 checkbox-box rounded relative group transition">
                <button type="button" onclick="this.parentElement.remove()" class="absolute right-3 top-3 text-red-500 text-xs font-bold bg-red-900/30 px-2 rounded opacity-50 group-hover:opacity-100 transition">X</button>
                <input type="text" name="game_data[phrases][${id}][phrase]" value="${val.phrase || ''}" class="lz-input mb-2 uppercase text-lg font-bold" placeholder="PHRASE">
                <input type="text" name="game_data[phrases][${id}][hint]" value="${val.hint || ''}" class="lz-input text-sm" placeholder="Hint Description...">
            </div>`;
            document.getElementById(`domino-container-${fid}`).insertAdjacentHTML('beforeend', html);
        }

        function addConnectPair(fid, val = {term:'', def:''}) {
            const id = counters.connect++;
            const html = `<div class="grid grid-cols-2 gap-3 mb-3 p-3 checkbox-box rounded relative group transition">
                <button type="button" onclick="this.parentElement.remove()" class="absolute -right-2 -top-2 text-white bg-red-600 hover:bg-red-500 px-2 py-0.5 rounded shadow-lg font-bold text-xs opacity-0 group-hover:opacity-100 transition">X</button>
                <input type="text" name="game_data[pairs][${id}][term]" value="${val.term || ''}" class="lz-input text-sm font-bold" placeholder="Left Term">
                <input type="text" name="game_data[pairs][${id}][def]" value="${val.def || ''}" class="lz-input text-sm" placeholder="Right Definition">
            </div>`;
            document.getElementById(`connect-container-${fid}`).insertAdjacentHTML('beforeend', html);
        }

        // --- UPGRADED VIDEO ENGINE ---
        function addVideoQuestion(fid, val = {question:'', video_url:'', options:{A:'',B:'',C:'',D:''}, correct_option:'A'}) {
            const id = counters.video++;
            const opts = val.options || {A:'',B:'',C:'',D:''};
            
            const html = `<div class="video-q-block p-5 border border-purple-500/30 border-l-4 border-l-purple-500 rounded-lg checkbox-box relative shadow-[0_4px_15px_rgba(0,0,0,0.3)] transition-all hover:border-purple-400">
                
                <div class="flex justify-between items-center mb-4 border-b border-purple-500/20 pb-3">
                    <span class="q-num-label text-sm font-bold text-purple-400 uppercase tracking-widest bg-purple-900/30 px-3 py-1 rounded" data-en="Question" data-ms="Soalan">Question</span>
                    <button type="button" onclick="this.closest('.video-q-block').remove(); updateVideoNumbers('${fid}');" class="text-red-400 hover:text-white bg-red-900/30 hover:bg-red-600 px-3 py-1 rounded text-xs font-bold uppercase tracking-widest transition-colors" data-en="🗑️ Remove" data-ms="🗑️ Padam">🗑️ Remove</button>
                </div>
                
                <input type="text" name="game_data[questions][${id}][question]" value="${val.question || ''}" class="lz-input mb-4 text-base font-bold" placeholder="Enter Question Statement Here">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4 bg-black/40 p-3 rounded border border-gray-800">
                    <div>
                        <label class="block text-[10px] text-gray-400 uppercase mb-1 font-bold" data-en="Specific Video URL" data-ms="URL Video Spesifik">Specific Video URL</label>
                        <input type="text" name="game_data[questions][${id}][video_url]" value="${val.video_url || ''}" class="lz-input text-xs border-purple-900 focus:border-purple-500" placeholder="Leave blank to keep previous video">
                    </div>
                    <div>
                        <label class="block text-[10px] text-gray-400 uppercase mb-1 font-bold" data-en="OR Specific MP4 Upload" data-ms="ATAU Muat Naik MP4 Spesifik">OR Specific MP4 Upload</label>
                        <input type="file" name="game_data[questions][${id}][video_file]" accept="video/mp4,video/webm" class="lz-input text-xs border-purple-900 focus:border-purple-500" style="padding: 7px;">
                    </div>
                </div>
                
                <div class="grid grid-cols-2 gap-3 mb-4">
                    <input type="text" name="game_data[questions][${id}][options][A]" value="${opts.A || ''}" class="lz-input text-sm" placeholder="Option A">
                    <input type="text" name="game_data[questions][${id}][options][B]" value="${opts.B || ''}" class="lz-input text-sm" placeholder="Option B">
                    <input type="text" name="game_data[questions][${id}][options][C]" value="${opts.C || ''}" class="lz-input text-sm" placeholder="Option C">
                    <input type="text" name="game_data[questions][${id}][options][D]" value="${opts.D || ''}" class="lz-input text-sm" placeholder="Option D">
                </div>

                <div class="bg-emerald-900/10 p-3 rounded border border-emerald-900/30 flex items-center justify-between">
                    <label class="text-[11px] text-emerald-500 uppercase font-bold tracking-widest" data-en="Correct Answer Selection" data-ms="Pilihan Jawapan Betul">Correct Answer Selection</label>
                    <select name="game_data[questions][${id}][correct_option]" class="lz-input w-auto text-sm border-emerald-700 focus:border-emerald-400 font-bold text-emerald-500">
                        <option value="A" ${val.correct_option=='A'?'selected':''}>Option A</option>
                        <option value="B" ${val.correct_option=='B'?'selected':''}>Option B</option>
                        <option value="C" ${val.correct_option=='C'?'selected':''}>Option C</option>
                        <option value="D" ${val.correct_option=='D'?'selected':''}>Option D</option>
                    </select>
                </div>
            </div>`;
            document.getElementById(`video-container-${fid}`).insertAdjacentHTML('beforeend', html);
            updateVideoNumbers(fid);
            applyLanguage(currentLang);
        }

        function updateVideoNumbers(fid) {
            const blocks = document.getElementById(`video-container-${fid}`).querySelectorAll('.q-num-label');
            const isMalay = currentLang === 'ms';
            blocks.forEach((label, index) => {
                label.innerText = isMalay ? `Soalan ${index + 1}` : `Question ${index + 1}`;
            });
        }

        // --- UPGRADED CHESS ENGINE ---
        function addChessQuestion(fid, val = {statement:'', is_true:1, steps_forward:1, steps_backward:1}) {
            const id = counters.chess++;
            const html = `<div class="chess-q-block p-5 border border-purple-500/30 border-l-4 border-l-purple-500 rounded-lg checkbox-box relative shadow-[0_4px_15px_rgba(0,0,0,0.3)] transition-all hover:border-purple-400">
                
                <div class="flex justify-between items-center mb-4 border-b border-purple-500/20 pb-3">
                    <span class="chess-num-label text-sm font-bold text-purple-400 uppercase tracking-widest bg-purple-900/30 px-3 py-1 rounded" data-en="Move" data-ms="Pergerakan">Move</span>
                    <button type="button" onclick="this.closest('.chess-q-block').remove(); updateChessNumbers('${fid}');" class="text-red-400 hover:text-white bg-red-900/30 hover:bg-red-600 px-3 py-1 rounded text-xs font-bold uppercase tracking-widest transition-colors" data-en="🗑️ Remove" data-ms="🗑️ Padam">🗑️ Remove</button>
                </div>

                <textarea name="game_data[questions][${id}][statement]" class="lz-input mb-4 text-sm font-bold" rows="2" placeholder="Enter Chess Statement Here...">${val.statement || ''}</textarea>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-3 bg-black/40 p-3 rounded border border-gray-800">
                    <div>
                        <label class="block text-[10px] text-gray-400 uppercase mb-1 font-bold" data-en="Answer" data-ms="Jawapan">Answer</label>
                        <select name="game_data[questions][${id}][is_true]" class="lz-input text-sm font-bold ${val.is_true==1 ? 'text-emerald-500' : 'text-red-500'}" onchange="this.className='lz-input text-sm font-bold ' + (this.value=='1' ? 'text-emerald-500' : 'text-red-500')">
                            <option value="1" ${val.is_true==1?'selected':''}>TRUE</option>
                            <option value="0" ${val.is_true==0?'selected':''}>FALSE</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] text-emerald-500 uppercase mb-1 font-bold" data-en="Steps Forward (If Right)" data-ms="Gerak Depan (Jika Betul)">Steps Forward (If Right)</label>
                        <input type="number" name="game_data[questions][${id}][steps_forward]" value="${val.steps_forward || 1}" class="lz-input text-sm border-emerald-900 focus:border-emerald-500" placeholder="Forward">
                    </div>
                    <div>
                        <label class="block text-[10px] text-red-500 uppercase mb-1 font-bold" data-en="Steps Back (If Wrong)" data-ms="Gerak Belakang (Jika Salah)">Steps Back (If Wrong)</label>
                        <input type="number" name="game_data[questions][${id}][steps_backward]" value="${val.steps_backward || 1}" class="lz-input text-sm border-red-900 focus:border-red-500" placeholder="Backward">
                    </div>
                </div>
            </div>`;
            document.getElementById(`chess-container-${fid}`).insertAdjacentHTML('beforeend', html);
            updateChessNumbers(fid);
            applyLanguage(currentLang);
        }

        function updateChessNumbers(fid) {
            const blocks = document.getElementById(`chess-container-${fid}`).querySelectorAll('.chess-num-label');
            const isMalay = currentLang === 'ms';
            blocks.forEach((label, index) => {
                label.innerText = isMalay ? `Urutan Gerak ${index + 1}` : `Move Sequence ${index + 1}`;
            });
        }

        // --- EDIT MODAL LOGIC ---
        function openEditModal(gameId) {
            const game = allGames.find(g => g.id === gameId);
            if (!game) return alert("Data not found!");

            document.getElementById('editModal').classList.add('active');
            const targetDiv = document.getElementById('edit-form-fields');
            targetDiv.innerHTML = ''; 
            renderForm('edit', 'edit-form-fields');
            document.getElementById('editForm').action = `/admin/level6/update/${game.id}`;
            
            setTimeout(() => {
                if (!document.getElementById('title_edit')) return;
                
                document.getElementById('title_edit').value = game.title;
                document.getElementById('base_score_edit').value = game.base_score;
                document.getElementById('game_type_edit').value = game.game_type;
                document.getElementById('instruction_edit').value = game.instruction || '';
                document.getElementById('is_active_edit').checked = (game.is_active == 1);
                
                switchGameConfig(game.game_type, 'edit');
                
                let data = game.game_data;
                if (typeof data === 'string') { try { data = JSON.parse(data); } catch(e) { data = {}; } }
                
                if (game.game_type === 'scramble' && data.words) data.words.forEach(w => addScrambleWord('edit', w));
                if (game.game_type === 'domino') {
                    document.getElementById('domino_mistakes_edit').value = data.max_mistakes || 6;
                    if(data.phrases) data.phrases.forEach(p => addDominoPhrase('edit', p));
                }
                if (game.game_type === 'connect' && data.pairs) data.pairs.forEach(p => addConnectPair('edit', p));
                if (game.game_type === 'video_abcd') {
                    document.getElementById('video_url_edit').value = data.video_url || '';
                    if(data.questions) data.questions.forEach(q => addVideoQuestion('edit', q));
                }
                if (game.game_type === 'chess' && data.questions) data.questions.forEach(q => addChessQuestion('edit', q));
                
                // Re-apply language inside modal to translate newly generated elements
                applyLanguage(currentLang);
            }, 100);
        }

        function closeEditModal() { document.getElementById('editModal').classList.remove('active'); }
        window.onload = initForms;
    </script>
    @include('partials.cursor')
</body>
</html>