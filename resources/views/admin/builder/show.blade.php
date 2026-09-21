<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Urus Soalan - {{ $room->title }}</title>
    <script src="https://cdn.tailwindcss.com/3.4.17"></script>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        /* 🛑 HALANG ANIMASI SEMASA PAGE RELOAD */
        .preload * { transition: none !important; }
        
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
            --table-header: rgba(10, 26, 16, 0.8);
            --btn-outline-bg: rgba(212, 175, 55, 0.1);
            --btn-outline-hover: rgba(212, 175, 55, 0.2);
            --particle: rgba(212, 175, 55, 0.15);
            --blue-badge-bg: rgba(30, 58, 138, 0.5);
            --blue-badge-text: #93c5fd;
            --blue-badge-border: rgba(59, 130, 246, 0.3);
            
            /* JAWAPAN COLORS (DARK MODE) */
            --ans-correct: #34d399; /* Emerald 400 */
            --ans-wrong: #f87171;   /* Red 400 */
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
            --table-header: rgba(230, 247, 236, 0.9);
            --btn-outline-bg: rgba(16, 185, 129, 0.1);
            --btn-outline-hover: rgba(16, 185, 129, 0.2);
            --particle: rgba(16, 185, 129, 0.25);
            --blue-badge-bg: rgba(219, 234, 254, 0.8);
            --blue-badge-text: #1e3a8a;
            --blue-badge-border: rgba(59, 130, 246, 0.4);
            
            /* JAWAPAN COLORS (LIGHT MODE) */
            --ans-correct: #059669; /* Emerald 600 */
            --ans-wrong: #dc2626;   /* Red 600 */
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
        
        .theme-blue-badge { 
            background: var(--blue-badge-bg); color: var(--blue-badge-text); border-color: var(--blue-badge-border);
            transition: all 0.5s ease;
        }

        .particles { position: fixed; top: 0; left: 0; width: 100%; height: 100%; overflow: hidden; pointer-events: none; z-index: 0; }
        .particle { position: absolute; background: var(--particle); border-radius: 50%; animation: float linear infinite; transition: background 0.5s ease; }
        @keyframes float { 0% { transform: translateY(100vh) translateX(0); opacity: 0; } 10% { opacity: 1; } 90% { opacity: 1; } 100% { transform: translateY(-10vh) translateX(100px); opacity: 0; } }

        .glass-panel { background: var(--glass-bg); backdrop-filter: blur(10px); border: 1px solid var(--glass-border); box-shadow: 0 8px 32px rgba(0, 0, 0, 0.15); transition: all 0.5s ease; }
        .glass-card { background: var(--glass-card); backdrop-filter: blur(8px); border: 1px solid var(--glass-border); border-radius: 16px; transition: all 0.5s ease; }
        
        .text-gold-gradient { background: linear-gradient(135deg, #d4af37 0%, #f0d56f 50%, #d4af37 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }
        body.light-mode .text-gold-gradient { background: linear-gradient(135deg, #b45309 0%, #d97706 50%, #b45309 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }
        
        .btn-gold { background: linear-gradient(135deg, #d4af37 0%, #f0d56f 50%, #d4af37 100%); color: #0f2818; border: none; font-weight: 700; transition: all 0.3s ease; box-shadow: 0 4px 15px rgba(212, 175, 55, 0.3); cursor: pointer; }
        .btn-gold:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(212, 175, 55, 0.5); }
        .btn-outline-gold { border: 1px solid var(--text-gold-dark); color: var(--text-gold); background: var(--btn-outline-bg); transition: all 0.3s; cursor: pointer; }
        .btn-outline-gold:hover { background: var(--btn-outline-hover); box-shadow: 0 0 15px var(--glass-shadow); color: var(--text-main); }
        
        ::-webkit-scrollbar { height: 8px; width: 8px; }
        ::-webkit-scrollbar-track { background: rgba(0,0,0,0.1); border-radius: 4px; }
        ::-webkit-scrollbar-thumb { background: var(--glass-border-hover); border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: var(--text-gold); }
    </style>
</head>
<body class="relative preload">
    <script>if (localStorage.getItem('theme') === 'light') document.body.classList.add('light-mode');</script>
    <div class="particles" id="particlesContainer"></div>

    <nav class="glass-panel sticky top-0 z-50 border-b-2 border-t-0 border-l-0 border-r-0 theme-border">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20 items-center">
                <div class="flex items-center gap-4">
                    <img src="{{ asset('img/logo2.png') }}" onerror="this.style.display='none'" alt="LZNK" class="h-12 drop-shadow-[0_0_8px_rgba(212,175,55,0.6)]">
                    <div class="hidden md:block border-l theme-border h-10 pl-4">
                        <h1 class="text-xl font-bold text-gold-gradient tracking-wide translation-target">Pengurusan Soalan</h1>
                        <p class="text-xs theme-text-muted tracking-widest uppercase truncate max-w-xs db-translate" data-ms="{{ $room->title }}" data-en="{{ $room->title_en ?: $room->title }}">{{ $room->title }}</p>
                    </div>
                </div>
                
                <div class="flex items-center gap-3">
                    <div class="flex items-center bg-black/20 p-1 rounded-lg border theme-border mr-1 md:mr-2 backdrop-blur-sm shadow-inner z-50 relative">
                        <button onclick="switchLanguage('ms')" id="lang-ms" class="px-2.5 py-1 text-[10px] font-bold rounded bg-[#10b981] text-white shadow-sm transition-all">BM</button>
                        <button onclick="switchLanguage('en')" id="lang-en" class="px-2.5 py-1 text-[10px] font-bold rounded text-gray-400 hover:text-white transition-all">EN</button>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer mr-2">
                        <input type="checkbox" id="theme-toggle" class="sr-only peer" autocomplete="off">
                        <div class="w-12 h-6 bg-gray-700 peer-focus:outline-none rounded-full peer peer-checked:bg-[#10b981] transition-colors duration-300 relative border border-gray-500 peer-checked:border-green-400">
                            <div class="absolute top-[1px] left-[2px] bg-white rounded-full h-5 w-5 transition-transform duration-300 peer-checked:translate-x-full flex items-center justify-center shadow-sm" id="toggle-circle">
                                <svg class="w-3 h-3 text-gray-800" fill="currentColor" viewBox="0 0 20 20"><path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path></svg>
                            </div>
                        </div>
                    </label>
                    <a href="{{ route('admin.builder.modules') }}" class="btn-outline-gold flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold uppercase tracking-wider">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        <span class="hidden md:inline translation-target">Kembali</span>
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 relative z-10">
        
        <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4 border-b theme-border pb-6">
            <div>
                <h2 class="text-3xl font-black theme-text-main tracking-wide drop-shadow-md translation-target">Senarai Soalan</h2>
                <p class="theme-text-muted mt-1 font-medium">
                    <span class="translation-target">Urus senario dan soalan untuk modul</span> <span class="theme-text-gold font-bold db-translate" data-ms="{{ $room->title }}" data-en="{{ $room->title_en ?: $room->title }}">{{ $room->title }}</span>.
                </p>
            </div>
            
            <div class="flex flex-wrap items-center gap-3">
                <a href="{{ route('admin.builder.map', $room->id) }}" class="btn-outline-gold flex items-center gap-2 px-6 py-3 rounded-xl text-sm uppercase tracking-widest shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path></svg>
                    <span class="translation-target">Lihat Peta Visual</span>
                </a>

                <a href="{{ route('admin.builder.node.create', $room->id) }}" class="btn-gold flex items-center gap-2 px-6 py-3 rounded-xl text-sm uppercase tracking-widest shadow-lg">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                <span class="translation-target">Tambah Soalan Baru</span>
                </a>
            </div>
        </div>

        @if(session('success'))
        <div class="mb-8 bg-green-500/10 border border-green-500 text-green-500 font-semibold px-4 py-3 rounded-lg flex items-center gap-3 shadow-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <span class="translation-target">{{ session('success') }}</span>
        </div>
        @endif

        <div class="glass-card overflow-hidden shadow-lg">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="text-xs uppercase tracking-widest font-bold border-b theme-border" style="background: var(--table-header); color: var(--text-gold-dark);">
                            <th class="px-6 py-4 w-24 text-center translation-target">No. / ID</th>
                            <th class="px-6 py-4 translation-target">Teks Soalan / Situasi</th>
                            <th class="px-6 py-4 text-center translation-target">Pilihan Jawapan</th>
                            <th class="px-6 py-4 text-center translation-target">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y theme-border text-sm">
                        @forelse($questions as $index => $q)
                        <!-- SOALAN UTAMA -->
                        <tr class="transition duration-300 hover:bg-black/5 dark:hover:bg-white/5">
                            <td class="px-6 py-4 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <span class="text-lg font-bold theme-text-main opacity-80">{{ $index + 1 }}</span>
                                    <span class="text-[10px] theme-text-gold px-2 py-0.5 rounded border theme-border mt-1 font-mono tracking-widest" style="background: rgba(0,0,0,0.05);">
                                        ID: {{ $q->id }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-bold theme-text-gold text-lg leading-relaxed db-translate" data-ms="{{ $q->text }}" data-en="{{ $q->text_en ?: $q->text }}">{{ $q->text }}</div>
                                @if($index == 0)
                                    <span class="inline-block mt-2 text-[10px] theme-blue-badge border px-2 py-0.5 rounded-full tracking-widest uppercase font-bold translation-target">Soalan Permulaan</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="inline-flex items-center justify-center w-8 h-8 rounded-full theme-text-gold font-bold border theme-border" style="background: rgba(0,0,0,0.05);">
                                    {{ $q->options->count() ?? 0 }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-center gap-2">
                                    <button onclick="toggleJawapan('jawapan-{{ $q->id }}')" class="btn-outline-gold px-3 py-1.5 rounded-lg text-xs font-bold uppercase tracking-wider transition shadow-sm flex items-center gap-1 translation-target" title="Urus Pilihan Jawapan">
                                        Jawapan
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                    </button>
                                    
                                    <a href="{{ route('admin.builder.node.edit', $q->id) }}" class="bg-blue-500/10 border border-blue-500/30 text-blue-500 hover:bg-blue-500 hover:text-white p-2 rounded-lg transition" title="Edit Soalan">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </a>

                                    <form action="{{ route('admin.builder.node.destroy', $q->id) }}" method="POST" onsubmit="return confirm('Padam soalan ini? Semua pilihan jawapannya juga akan hilang.');" class="m-0">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="bg-red-500/10 border border-red-500/30 text-red-500 hover:bg-red-500 hover:text-white p-2 rounded-lg transition" title="Padam Soalan">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>

                        <!-- HIDDEN ANSWERS ACCORDION (REFACTORED FOR READABILITY) -->
                        <tr id="jawapan-{{ $q->id }}" class="hidden">
                            <td colspan="4" class="p-0 border-0">
                                <div class="p-6 m-4 rounded-xl border border-dashed theme-border" style="background: var(--glass-card);">
                                    <div class="flex justify-between items-center mb-6">
                                        <h4 class="font-bold theme-text-gold text-sm tracking-widest uppercase translation-target">Senarai Pilihan Jawapan</h4>
                                        <a href="{{ route('admin.builder.choice.create', $q->id) }}" class="btn-outline-gold px-3 py-1 text-xs font-bold rounded flex items-center gap-1 translation-target">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                            TAMBAH JAWAPAN
                                        </a>
                                    </div>
                                    
                                    @if($q->options->count() > 0)
                                        <!-- TABLE SEPARATE FOR BOX STYLING -->
                                        <table class="w-full text-sm text-left border-separate" style="border-spacing: 0 10px;">
                                            <thead class="theme-text-muted text-xs uppercase tracking-widest">
                                                <tr>
                                                    <th class="pb-2 pl-4 font-semibold translation-target">Teks Jawapan</th>
                                                    <th class="pb-2 font-semibold text-center w-24 translation-target">Mata</th>
                                                    <th class="pb-2 font-semibold translation-target">Maklum Balas</th>
                                                    <th class="pb-2 font-semibold w-64 translation-target">Pautan Branching</th>
                                                    <th class="pb-2 pr-4 font-semibold text-right w-16"></th>
                                                </tr>
                                            </thead>
                                            <tbody class="opacity-100">
                                                
                                                <!-- CALC HIGHEST POINT FOR THIS SPECIFIC QUESTION -->
                                                @php
                                                    $maxPts = $q->options->max('points');
                                                @endphp
                                                
                                                @foreach($q->options as $option)
                                                    @php
                                                        // Color Logic Box Styling
                                                        $isCorrect = ($option->points === $maxPts && $option->points !== null);
                                                        
                                                        // Soft background colors
                                                        $boxBg = $isCorrect ? 'bg-emerald-500/10' : 'bg-red-500/10';
                                                        $boxBorder = $isCorrect ? 'border-emerald-500' : 'border-red-500';
                                                        $ptColor = $isCorrect ? 'text-emerald-600 dark:text-emerald-400' : 'text-red-600 dark:text-red-400';
                                                    @endphp
                                                    
                                                    <!-- OPTION BOX ROW -->
                                                    <tr class="{{ $boxBg }} shadow-sm hover:opacity-80 transition-opacity">
                                                        <td class="py-4 pl-4 border-l-4 {{ $boxBorder }} theme-text-main font-medium rounded-l-lg">{{ $option->text }}</td>
                                                        
                                                        <td class="py-4 text-center font-black text-lg {{ $ptColor }}">
                                                            {{ $option->points > 0 ? '+'.$option->points : $option->points }}
                                                        </td>
                                                        
                                                        <td class="py-4 theme-text-muted text-xs italic pr-2">{{ $option->feedback ?? '-' }}</td>
                                                        
                                                        <td class="py-4">
                                                            <form action="{{ route('admin.builder.choice.link', $option->id) }}" method="POST" class="flex gap-2 m-0">
                                                                @csrf
                                                                <!-- Transparent select box fits both modes -->
                                                                <select name="next_question_id" class="bg-transparent border theme-border theme-text-main text-xs rounded p-1.5 focus:outline-none focus:border-[#f0d56f] w-full">
                                                                    <option value="" class="bg-gray-800 text-white">-- TAMAT / END --</option>
                                                                    @foreach($questions as $targetNode)
                                                                        <option value="{{ $targetNode->id }}" class="bg-gray-800 text-white" {{ $option->next_question_id == $targetNode->id ? 'selected' : '' }}>
                                                                            ID: {{ $targetNode->id }} ({{ Str::limit($targetNode->text, 20) }})
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                                <button type="submit" class="bg-blue-600/20 border border-blue-500/30 hover:bg-blue-500 text-blue-600 dark:text-blue-400 hover:text-white px-2 rounded text-xs font-bold transition-colors">LINK</button>
                                                            </form>
                                                        </td>
                                                        
                                                        <td class="py-4 pr-4 text-right rounded-r-lg">
                                                            <form action="{{ route('admin.builder.choice.destroy', $option->id) }}" method="POST" class="m-0">
                                                                @csrf @method('DELETE')
                                                                <button type="submit" class="text-red-500 hover:text-white transition-colors bg-red-500/10 hover:bg-red-500 p-1.5 rounded border border-transparent hover:border-red-400">
                                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                                </button>
                                                            </form>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    @else
                                        <p class="theme-text-muted text-sm text-center py-4 italic translation-target">Tiada pilihan jawapan direkodkan. Sila tambah jawapan untuk elak pemain tersangkut.</p>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center theme-text-muted opacity-60">
                                <div class="text-4xl mb-3 opacity-50">📝</div>
                                <span class="translation-target">Belum ada soalan untuk modul ini. Klik "Tambah Soalan Baru" untuk mula membina senario!</span>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
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

        if (body.classList.contains('light-mode')) { themeToggle.checked = true; toggleCircle.innerHTML = sunIcon; } 
        else { toggleCircle.innerHTML = moonIcon; }

        themeToggle.addEventListener('change', () => {
            if (themeToggle.checked) { body.classList.add('light-mode'); localStorage.setItem('theme', 'light'); toggleCircle.innerHTML = sunIcon; } 
            else { body.classList.remove('light-mode'); localStorage.setItem('theme', 'dark'); toggleCircle.innerHTML = moonIcon; }
        });

        function toggleJawapan(id) {
            const el = document.getElementById(id);
            if(el.classList.contains('hidden')) { el.classList.remove('hidden'); } 
            else { el.classList.add('hidden'); }
        }

        const enDictionary = {
            "Pengurusan Soalan": "Question Management",
            "Kembali": "Back",
            "Senarai Soalan": "Question List",
            "Urus senario dan soalan untuk modul": "Manage scenarios and questions for the module",
            "Lihat Peta Visual": "View Visual Map",
            "Tambah Soalan Baru": "Add New Question",
            "No. / ID": "No. / ID",
            "Teks Soalan / Situasi": "Question Text / Situation",
            "Pilihan Jawapan": "Options",
            "Tindakan": "Action",
            "Soalan Permulaan": "Starting Question",
            "Jawapan": "Answers",
            "Belum ada soalan untuk modul ini. Klik \"Tambah Soalan Baru\" untuk mula membina senario!": "No questions for this module yet. Click \"Add New Question\" to start building scenarios!",
            "Soalan berjaya dicipta & diterjemah secara automatik!": "Question successfully created & auto-translated!",
            "Maklumat soalan berjaya dikemas kini!": "Question information successfully updated!",
            "Soalan berserta pilihan jawapan berjaya dipadam!": "Question and its options successfully deleted!",
            "Batal": "Cancel",
            "Simpan Soalan": "Save Question",
            "Kemaskini Soalan": "Update Question",
            "Kemaskini": "Update",
            "Senarai Pilihan Jawapan": "List of Answer Options",
            "TAMBAH JAWAPAN": "ADD ANSWER",
            "Teks Jawapan": "Answer Text",
            "Mata": "Points",
            "Maklum Balas": "Feedback",
            "Pautan Branching": "Branching Link",
            "Tambah Jawapan": "Add Answer",
            "Teks Pilihan Jawapan": "Answer Option Text",
            "Markah (Points)": "Points",
            "Maklum Balas (Feedback)": "Feedback",
            "SIMPAN JAWAPAN": "SAVE ANSWER",
            "Tiada pilihan jawapan direkodkan. Sila tambah jawapan untuk elak pemain tersangkut.": "No answers recorded. Please add answers to prevent players from getting stuck."
        };

        const currentLang = localStorage.getItem('lang') || 'ms';

        function applyTranslation() {
            const btnEn = document.getElementById('lang-en'); const btnMs = document.getElementById('lang-ms');
            if (currentLang === 'en') {
                if(btnEn) btnEn.className = "px-2.5 py-1 text-[10px] font-bold rounded bg-[#10b981] text-white shadow-sm transition-all";
                if(btnMs) btnMs.className = "px-2.5 py-1 text-[10px] font-bold rounded text-gray-400 hover:text-white transition-all";
                document.querySelectorAll('.translation-target').forEach(el => { const text = el.textContent.trim(); if (text && enDictionary[text]) el.textContent = enDictionary[text]; });
                document.querySelectorAll('.db-translate').forEach(el => { el.textContent = el.getAttribute('data-en') || el.getAttribute('data-ms'); });
            } else {
                if(btnMs) btnMs.className = "px-2.5 py-1 text-[10px] font-bold rounded bg-[#10b981] text-white shadow-sm transition-all";
                if(btnEn) btnEn.className = "px-2.5 py-1 text-[10px] font-bold rounded text-gray-400 hover:text-white transition-all";
                document.querySelectorAll('.db-translate').forEach(el => { el.textContent = el.getAttribute('data-ms'); });
            }
        }

        function switchLanguage(lang) { localStorage.setItem('lang', lang); window.location.reload(); }
        document.addEventListener('DOMContentLoaded', () => { applyTranslation(); setTimeout(() => document.body.classList.remove('preload'), 100); });
    </script>
    @include('partials.cursor')
</body>
</html>