<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kemaskini Soalan - Cyber Escape Room</title>
    <script src="https://cdn.tailwindcss.com/3.4.17"></script>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        .preload * { transition: none !important; }

        :root {
            --bg-1: #0f2818; --bg-2: #1a3a2a; --text-main: #e5e7eb;
            --text-muted: #a8d5ba; --text-gold: #f0d56f; --text-gold-dark: #d4af37;
            --glass-bg: rgba(10, 40, 25, 0.7); --glass-card: rgba(10, 40, 25, 0.6);
            --glass-border: rgba(212, 175, 55, 0.3); --glass-border-hover: rgba(212, 175, 55, 0.6);
            --glass-shadow: rgba(212, 175, 55, 0.15); --particle: rgba(212, 175, 55, 0.15);
            --input-bg: rgba(15, 40, 24, 0.7); --input-focus: rgba(20, 50, 30, 0.9);
            --panel-header: rgba(212, 175, 55, 0.15); --panel-header-2: #0a1a10;
            --option-box-bg: rgba(0, 0, 0, 0.4); --option-input-bg: #0a1a10;
            --btn-outline-bg: rgba(212, 175, 55, 0.1); --btn-outline-hover: rgba(212, 175, 55, 0.2);
            --remove-btn-hover: rgba(248, 113, 113, 0.1);
        }

        body.light-mode {
            --bg-1: #f2fcf5; --bg-2: #e6f7ec; --text-main: #064e3b;
            --text-muted: #047857; --text-gold: #b45309; --text-gold-dark: #92400e;
            --glass-bg: rgba(255, 255, 255, 0.85); --glass-card: rgba(255, 255, 255, 0.7);
            --glass-border: rgba(16, 185, 129, 0.4); --glass-border-hover: rgba(16, 185, 129, 0.8);
            --glass-shadow: rgba(16, 185, 129, 0.2); --particle: rgba(16, 185, 129, 0.25);
            --input-bg: rgba(255, 255, 255, 0.6); --input-focus: rgba(255, 255, 255, 1);
            --panel-header: rgba(16, 185, 129, 0.15); --panel-header-2: rgba(16, 185, 129, 0.1);
            --option-box-bg: rgba(255, 255, 255, 0.4); --option-input-bg: rgba(255, 255, 255, 0.8);
            --btn-outline-bg: rgba(16, 185, 129, 0.1); --btn-outline-hover: rgba(16, 185, 129, 0.2);
            --remove-btn-hover: rgba(239, 68, 68, 0.1);
        }

        html, body { height: 100%; width: 100%; overflow-x: hidden; }
        body { font-family: 'Segoe UI', sans-serif; background: linear-gradient(135deg, var(--bg-1) 0%, var(--bg-2) 50%, var(--bg-1) 100%); color: var(--text-main); background-attachment: fixed; transition: background 0.5s ease, color 0.5s ease; }
        .theme-text-main { color: var(--text-main); transition: color 0.5s ease; }
        .theme-text-muted { color: var(--text-muted); transition: color 0.5s ease; }
        .theme-text-gold { color: var(--text-gold); transition: color 0.5s ease; }
        .theme-border { border-color: var(--glass-border); transition: border-color 0.5s ease; }
        .theme-border-hover:hover { border-color: var(--glass-border-hover); }
        .theme-panel-header { background: var(--panel-header); border-color: var(--glass-border); transition: all 0.5s ease; }
        .theme-panel-header-2 { background: var(--panel-header-2); border-color: var(--glass-border); transition: all 0.5s ease; }
        .theme-option-box { background: var(--option-box-bg); border-color: var(--glass-border); transition: all 0.5s ease; }
        .remove-btn:hover { background-color: var(--remove-btn-hover); }

        .glass-panel { background: var(--glass-bg); backdrop-filter: blur(10px); border: 1px solid var(--glass-border); box-shadow: 0 8px 32px rgba(0, 0, 0, 0.15); transition: all 0.5s ease; }
        .glass-card { background: var(--glass-card); backdrop-filter: blur(8px); border: 1px solid var(--glass-border); border-radius: 16px; transition: all 0.5s ease; }
        .text-gold-gradient { background: linear-gradient(135deg, #d4af37 0%, #f0d56f 50%, #d4af37 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }
        body.light-mode .text-gold-gradient { background: linear-gradient(135deg, #b45309 0%, #d97706 50%, #b45309 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }
        .btn-gold { background: linear-gradient(135deg, #d4af37 0%, #f0d56f 50%, #d4af37 100%); color: #0f2818; border: none; font-weight: 700; transition: all 0.3s ease; box-shadow: 0 4px 15px rgba(212, 175, 55, 0.3); cursor: pointer; }
        .btn-gold:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(212, 175, 55, 0.5); }
        .btn-outline-gold { border: 1px solid var(--text-gold-dark); color: var(--text-gold); background: var(--btn-outline-bg); transition: all 0.3s; }
        .btn-outline-gold:hover { background: var(--btn-outline-hover); box-shadow: 0 0 15px var(--glass-shadow); color: var(--text-main); }
        
        .form-input { width: 100%; padding: 0.85rem 1rem; background: var(--input-bg); border: 1px solid var(--glass-border); border-radius: 10px; color: var(--text-main); transition: all 0.3s ease; }
        .form-input:focus { outline: none; border-color: var(--text-gold-dark); background: var(--input-focus); box-shadow: 0 0 15px var(--glass-shadow); }
        .form-label { display: block; font-size: 0.75rem; font-weight: 700; color: var(--text-gold-dark); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.5rem; transition: color 0.5s ease;}
        select.branching-dropdown, select[class*="branching-dropdown-"] { background-color: var(--option-input-bg) !important; }
        body.light-mode .form-input::placeholder { color: #9ca3af; }
        body:not(.light-mode) .form-input::placeholder { color: #6b7280; }
    </style>
</head>
<body class="relative pb-32 preload">
    <script>if (localStorage.getItem('theme') === 'light') document.body.classList.add('light-mode');</script>

    <nav class="glass-panel sticky top-0 z-50 border-b-2 border-t-0 border-l-0 border-r-0 theme-border">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20 items-center">
                <div class="flex items-center gap-4">
                    <img src="{{ asset('img/logo2.png') }}" onerror="this.style.display='none'" alt="LZNK" class="h-12 drop-shadow-[0_0_8px_rgba(212,175,55,0.6)]">
                    <div class="hidden md:block border-l h-10 pl-4 theme-border">
                        <h1 class="text-xl font-bold text-gold-gradient tracking-wide translation-target">Kemaskini Soalan</h1>
                        <p class="text-xs theme-text-muted tracking-widest uppercase mt-1"><span class="translation-target">Modul:</span> <span class="db-translate" data-ms="{{ $room->title }}" data-en="{{ $room->title_en ?: $room->title }}">{{ $room->title }}</span></p>
                    </div>
                </div>
                
                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.builder.show', $room->id) }}" class="btn-outline-gold flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold uppercase tracking-wider">
                        <svg class="w-4 h-4 hidden md:block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        <span class="translation-target">Kembali</span>
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10 relative z-10">
        
        <form action="{{ route('admin.builder.node.update', $question->id) }}" method="POST" id="questionForm" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="glass-card overflow-hidden mb-10 shadow-lg">
                <div class="theme-panel-header border-b px-6 py-4">
                    <h2 class="text-lg font-bold theme-text-gold tracking-widest uppercase translation-target">Bahagian 1: Teras Soalan</h2>
                </div>
                <div class="p-6 space-y-6">
                    <div>
                        <label class="form-label"><span class="translation-target">Teks Soalan / Situasi</span> <span class="text-red-500">*</span></label>
                        <textarea name="text" rows="4" class="form-input translation-placeholder" required placeholder="Masukkan situasi atau soalan di sini...">{{ old('text', $question->text) }}</textarea>
                    </div>
                    <div>
                        <label class="form-label"><span class="translation-target">Tahap Soalan (Level)</span> <span class="text-red-500">*</span></label>
                        <input type="number" name="level" value="{{ old('level', $question->level) }}" class="form-input w-full md:w-1/3" required min="1">
                        <p class="text-xs theme-text-main opacity-60 mt-2 italic translation-target">Level menentukan kedudukan awal soalan dalam modul.</p>
                    </div>
                    
                    <!-- VIDEO SETTINGS BLOCK -->
                    <div class="mt-6 border-t theme-border pt-6">
                        <h3 class="text-md font-bold theme-text-gold tracking-widest uppercase mb-4 translation-target">Lampiran Video (Pilihan)</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-black/10 p-4 rounded-lg border theme-border mb-6">
                            <div>
                                <label class="form-label translation-target">Pilihan 1: URL Video (YouTube)</label>
                                <input type="url" name="video_url" value="{{ old('video_url', $question->video_url) }}" class="form-input translation-placeholder" placeholder="Contoh: https://www.youtube.com/watch?v=XXXXX">
                                <p class="text-xs theme-text-muted opacity-80 mt-2 translation-target">Guna ini untuk pautkan video dari YouTube.</p>
                            </div>
                            <div>
                                <label class="form-label translation-target">Pilihan 2: Muat Naik MP4</label>
                                <input type="file" name="video_upload" accept="video/mp4,video/x-m4v,video/*" class="form-input file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-[#10b981] file:text-white hover:file:bg-[#059669] transition-all">
                                <p class="text-xs theme-text-muted opacity-80 mt-2 translation-target">Biarkan kosong jika tidak mahu ubah video sedia ada.</p>
                            </div>
                        </div>
                        <div>
                            <label class="form-label translation-target">Masa Wajib Tonton (Saat)</label>
                            <input type="number" name="video_duration" value="{{ old('video_duration', $question->video_duration ?? 0) }}" min="0" class="form-input w-full md:w-1/3 translation-placeholder" placeholder="Contoh: 30">
                            <p class="text-xs theme-text-main opacity-60 mt-2 italic translation-target">Masa sekatan pemain tidak boleh menjawab selagi masa belum tamat.</p>
                        </div>
                    </div>
                    <!-- END VIDEO SETTINGS BLOCK -->

                </div>
            </div>

            <div class="glass-card overflow-hidden mb-8 shadow-lg">
                <div class="theme-panel-header-2 border-b px-6 py-4 flex justify-between items-center">
                    <h2 class="text-lg font-bold theme-text-main tracking-widest uppercase translation-target">Bahagian 2: Pilihan Jawapan</h2>
                    <button type="button" id="addOptionBtn" class="btn-outline-gold px-4 py-1.5 rounded-lg text-xs font-bold uppercase tracking-wider transition shadow-sm">
                        <span class="translation-target">+ Tambah Jawapan</span>
                    </button>
                </div>
                
                <div id="optionsContainer" class="p-6 space-y-8">
                    @foreach($question->options as $index => $option)
                    <div class="option-item theme-option-box border p-6 rounded-xl relative theme-border-hover mt-8" id="optionBox_{{ $index }}">
                        <div class="absolute -top-4 -left-4 w-10 h-10 rounded-full bg-gradient-to-br from-[#d4af37] to-[#8b6508] text-[#0f2818] font-black text-lg flex items-center justify-center border-4 border-[#0f2818] shadow-lg option-number">{{ $index + 1 }}</div>
                        
                        <div class="absolute top-4 right-4">
                            <button type="button" class="text-red-500 hover:text-red-600 p-1.5 rounded transition remove-btn" onclick="removeOption({{ $index }})">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>

                        <div class="space-y-5 pt-2">
                            <!-- Hidden ID to identify existing options -->
                            <input type="hidden" name="options[{{ $index }}][id]" value="{{ $option->id }}">

                            <div>
                                <label class="form-label translation-target">Teks Jawapan</label>
                                <input type="text" name="options[{{ $index }}][text]" value="{{ $option->text }}" class="form-input translation-placeholder" placeholder="Masukkan pilihan jawapan..." required>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <div>
                                    <label class="form-label translation-target">Markah (Pts)</label>
                                    <input type="number" name="options[{{ $index }}][points]" value="{{ $option->points }}" class="form-input font-bold" required>
                                </div>
                                <div>
                                    <label class="form-label theme-text-muted translation-target">Pautkan Ke (Branching)</label>
                                    <select name="options[{{ $index }}][next_question_id]" class="form-input appearance-none branching-dropdown-{{ $index }}">
                                        <option value="" {{ is_null($option->next_question_id) ? 'selected' : '' }} class="translation-target">-- Tamatkan Modul (Tiada Pautan) --</option>
                                        @foreach($otherQuestions as $q)
                                            <option value="{{ $q->id }}" {{ $option->next_question_id == $q->id ? 'selected' : '' }}>#{{ $q->id }} - {{ Str::limit($q->text, 45) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div>
                                <label class="form-label translation-target">Maklum Balas Tersembunyi</label>
                                <input type="text" name="options[{{ $index }}][feedback]" value="{{ $option->feedback }}" class="form-input text-sm italic translation-placeholder" placeholder="Maklum balas jika staf pilih ini...">
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="fixed bottom-0 left-0 w-full glass-panel border-t-2 border-b-0 border-l-0 border-r-0 theme-border py-4 px-6 z-50 flex justify-end items-center gap-4">
                <div class="max-w-5xl mx-auto w-full flex justify-between md:justify-end gap-4">
                    <a href="{{ route('admin.builder.show', $room->id) }}" class="btn-outline-gold px-6 py-2.5 rounded-lg text-sm font-bold uppercase tracking-widest text-center w-full md:w-auto translation-target">Batal</a>
                    <button type="submit" class="btn-gold flex justify-center items-center gap-2 px-8 py-2.5 rounded-lg text-sm font-bold uppercase tracking-widest w-full md:w-auto shadow-md">
                        <svg class="w-5 h-5 hidden md:block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                        <span class="translation-target">Kemaskini Soalan</span>
                    </button>
                </div>
            </div>
        </form>
    </main>

    <script>
        const enDictionary = {
            "Kemaskini Soalan": "Update Question",
            "Kembali": "Back",
            "Bahagian 1: Teras Soalan": "Part 1: Core Question",
            "Teks Soalan / Situasi": "Question Text / Situation",
            "Tahap Soalan (Level)": "Question Level",
            "Lampiran Video (Pilihan)": "Video Attachment (Optional)",
            "Pilihan 1: URL Video (YouTube)": "Option 1: Video URL (YouTube)",
            "Guna ini untuk pautkan video dari YouTube.": "Use this to link a video from YouTube.",
            "Pilihan 2: Muat Naik MP4": "Option 2: Upload MP4",
            "Biarkan kosong jika tidak mahu ubah video sedia ada.": "Leave blank if you do not want to change existing video.",
            "Masa Wajib Tonton (Saat)": "Mandatory Watch Time (Seconds)",
            "Masa sekatan pemain tidak boleh menjawab selagi masa belum tamat.": "Lock time where players cannot answer until timer ends.",
            "Bahagian 2: Pilihan Jawapan": "Part 2: Answer Options",
            "+ Tambah Jawapan": "+ Add Answer",
            "Teks Jawapan": "Answer Text",
            "Markah (Pts)": "Marks (Pts)",
            "Pautkan Ke (Branching)": "Link To (Branching)",
            "-- Tamatkan Modul (Tiada Pautan) --": "-- End Module (No Link) --",
            "Maklum Balas Tersembunyi": "Hidden Feedback",
            "Batal": "Cancel",

            // Placeholders
            "Masukkan situasi atau soalan di sini...": "Enter situation or question here...",
            "Masukkan pilihan jawapan...": "Enter answer option...",
            "Maklum balas jika staf pilih ini...": "Feedback if staff chooses this..."
        };

        function applyTranslation() {
            const lang = localStorage.getItem('lang') || 'ms';
            document.querySelectorAll('.translation-target').forEach(el => {
                const text = el.textContent.trim();
                if (lang === 'en' && enDictionary[text]) el.textContent = enDictionary[text];
            });
            document.querySelectorAll('.translation-placeholder').forEach(el => {
                const placeholder = el.getAttribute('placeholder');
                if (lang === 'en' && enDictionary[placeholder]) el.setAttribute('placeholder', enDictionary[placeholder]);
            });
            document.querySelectorAll('.db-translate').forEach(el => {
                el.textContent = el.getAttribute(`data-${lang}`) || el.getAttribute('data-ms');
            });
        }

        // ==========================================
        // SISTEM DYNAMIC SMART SYNC (TAMBAH JAWAPAN)
        // ==========================================
        const existingQuestions = @json($otherQuestions);
        // Start the index counting from how many options already exist
        let optionIndex = {{ $question->options->count() }}; 
        const currentLang = localStorage.getItem('lang') || 'ms';

        function populateDropdown(selectElement) {
            existingQuestions.forEach(q => {
                const opt = document.createElement('option');
                opt.value = q.id;
                
                const textMs = `#${q.id} - ${q.text.substring(0, 45)}...`;
                const textEn = q.text_en ? `#${q.id} - ${q.text_en.substring(0, 45)}...` : textMs;
                
                opt.textContent = currentLang === 'en' ? textEn : textMs;
                selectElement.appendChild(opt);
            });
        }

        document.getElementById('addOptionBtn').addEventListener('click', function() {
            const container = document.getElementById('optionsContainer');
            const isEn = (localStorage.getItem('lang') === 'en');
            
            const newOptionHtml = `
                <div class="option-item theme-option-box border p-6 rounded-xl relative theme-border-hover mt-8" id="optionBox_${optionIndex}">
                    <div class="absolute -top-4 -left-4 w-10 h-10 rounded-full bg-gradient-to-br from-[#d4af37] to-[#8b6508] text-[#0f2818] font-black text-lg flex items-center justify-center border-4 border-[#0f2818] shadow-lg option-number">${optionIndex + 1}</div>
                    
                    <div class="absolute top-4 right-4">
                        <button type="button" class="text-red-500 hover:text-red-600 p-1.5 rounded transition remove-btn" onclick="removeOption(${optionIndex})">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>

                    <div class="space-y-5 pt-2">
                        <div>
                            <label class="form-label translation-target">${isEn ? 'Answer Text' : 'Teks Jawapan'}</label>
                            <input type="text" name="options[${optionIndex}][text]" class="form-input" placeholder="${isEn ? 'Enter answer option...' : 'Masukkan pilihan jawapan...'}" required>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label class="form-label translation-target">${isEn ? 'Marks (Pts)' : 'Markah (Pts)'}</label>
                                <input type="number" name="options[${optionIndex}][points]" value="0" class="form-input font-bold" required>
                            </div>
                            <div>
                                <label class="form-label theme-text-muted translation-target">${isEn ? 'Link To (Branching)' : 'Pautkan Ke (Branching)'}</label>
                                <select name="options[${optionIndex}][next_question_id]" class="form-input appearance-none branching-dropdown-${optionIndex}">
                                    <option value="" class="translation-target">${isEn ? '-- End Module (No Link) --' : '-- Tamatkan Modul (Tiada Pautan) --'}</option>
                                </select>
                            </div>
                        </div>
                        <div>
                            <label class="form-label translation-target">${isEn ? 'Hidden Feedback' : 'Maklum Balas Tersembunyi'}</label>
                            <input type="text" name="options[${optionIndex}][feedback]" class="form-input text-sm italic" placeholder="${isEn ? 'Feedback if staff chooses this...' : 'Maklum balas jika staf pilih ini...'}">
                        </div>
                    </div>
                </div>
            `;
            
            container.insertAdjacentHTML('beforeend', newOptionHtml);
            
            const newSelect = document.querySelector(`.branching-dropdown-${optionIndex}`);
            populateDropdown(newSelect);
            
            optionIndex++;
            updateOptionNumbers();
        });

        window.removeOption = function(id) {
            const box = document.getElementById(`optionBox_${id}`);
            if(box) {
                box.remove();
                updateOptionNumbers();
            }
        };

        function updateOptionNumbers() {
            const boxes = document.querySelectorAll('.option-item');
            boxes.forEach((box, index) => {
                box.querySelector('.option-number').textContent = index + 1;
            });
        }

        document.addEventListener('DOMContentLoaded', () => {
            applyTranslation();
            setTimeout(() => { document.body.classList.remove('preload'); }, 100);
        });
    </script>
    @include('partials.cursor')
</body>
</html>