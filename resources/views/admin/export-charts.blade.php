<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistik Carta Pai Modul - Cyber Escape Room</title>
    <script src="https://cdn.tailwindcss.com/3.4.17"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        :root {
            --bg-1: #0f2818; --bg-2: #1a3a2a;
            --text-main: #e5e7eb; --text-gold: #f0d56f; --text-muted: #a8d5ba;
            --glass-bg: rgba(10, 40, 25, 0.7); --glass-border: rgba(212, 175, 55, 0.3);
            --table-hover: rgba(212, 175, 55, 0.1);
        }

        body.light-mode {
            --bg-1: #f2fcf5; --bg-2: #e6f7ec;
            --text-main: #064e3b; --text-gold: #b45309; --text-muted: #047857;
            --glass-bg: rgba(255, 255, 255, 0.85); --glass-border: rgba(16, 185, 129, 0.4);
            --table-hover: rgba(16, 185, 129, 0.1);
        }

        body { 
            font-family: 'Segoe UI', sans-serif; 
            background: linear-gradient(135deg, var(--bg-1) 0%, var(--bg-2) 50%, var(--bg-1) 100%); 
            color: var(--text-main); min-height: 100vh; background-attachment: fixed; transition: all 0.5s ease;
        }

        .glass-card { background: var(--glass-bg); backdrop-filter: blur(8px); border: 1px solid var(--glass-border); border-radius: 16px; }
        .theme-text-main { color: var(--text-main); }
        .theme-text-gold { color: var(--text-gold); }
        .theme-text-muted { color: var(--text-muted); }
        .theme-border { border-color: var(--glass-border); }
        
        @media print {
            body { background: white; color: black; }
            .no-print { display: none !important; }
            .glass-card { border: 1px solid #ccc; box-shadow: none; break-inside: avoid; page-break-inside: avoid; margin-bottom: 2rem; }
            .grid { display: grid !important; }
            canvas { max-width: 100% !important; height: auto !important; margin: 0 auto; }
        }
    </style>
</head>
<body class="p-6">
    <script>if (localStorage.getItem('theme') === 'light') document.body.classList.add('light-mode');</script>

    <div class="max-w-6xl mx-auto">
        <div class="flex justify-between items-center mb-8 border-b theme-border pb-6 no-print">
            <div>
                <h1 class="text-3xl font-black theme-text-gold translation-target">Laporan Visual: Analisis Soalan Modul</h1>
                <p class="theme-text-muted mt-2 translation-target">Kadar kejayaan staf bagi setiap soalan. Klik pada carta untuk melihat senarai nama staf.</p>
            </div>
            <div class="flex gap-4">
                <a href="{{ route('admin.export.index') }}" class="px-6 py-2 rounded-lg border theme-border theme-text-gold font-bold uppercase text-sm hover:bg-black/10 transition translation-target">Kembali</a>
                <button onclick="window.print()" class="px-6 py-2 rounded-lg bg-blue-600 text-white font-bold uppercase text-sm hover:bg-blue-700 transition flex items-center gap-2 shadow-lg">
                    🖨️ <span class="translation-target">Cetak / Simpan PDF</span>
                </button>
            </div>
        </div>

        <div class="hidden print:block text-center mb-8">
            <h1 class="text-2xl font-bold uppercase border-b-2 pb-2">Laporan Analisis Soalan - Pusat Kawalan LZNK</h1>
            <p class="text-sm mt-2 text-gray-600">Dicetak pada: {{ date('d/m/Y H:i:s') }}</p>
        </div>

        <div class="space-y-8">
            @forelse($chartData as $index => $data)
            <div class="glass-card p-8">
                <h2 class="text-2xl font-black theme-text-gold mb-8 text-center uppercase tracking-widest border-b theme-border pb-4">{{ $data['title'] }}</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                    <div class="flex flex-col items-center cursor-pointer group" title="Klik carta untuk senarai staf">
                        <div class="bg-green-500/10 text-green-500 border border-green-500 px-4 py-1.5 rounded-full font-bold text-sm uppercase tracking-widest mb-4 translation-target">✅ Jawapan Betul</div>
                        <div class="w-full relative h-64 transition-transform group-hover:scale-105">
                            <canvas id="correct-chart-{{ $index }}"></canvas>
                        </div>
                    </div>

                    <div class="flex flex-col items-center cursor-pointer group" title="Klik carta untuk senarai staf">
                        <div class="bg-red-500/10 text-red-500 border border-red-500 px-4 py-1.5 rounded-full font-bold text-sm uppercase tracking-widest mb-4 translation-target">❌ Jawapan Salah</div>
                        <div class="w-full relative h-64 transition-transform group-hover:scale-105">
                            <canvas id="wrong-chart-{{ $index }}"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="glass-card p-12 text-center theme-text-muted translation-target">
                Belum ada staf yang merekodkan jawapan untuk dianalisis.
            </div>
            @endforelse
        </div>
    </div>

    <div id="pieModal" class="fixed inset-0 z-[100] hidden items-center justify-center p-4 no-print" style="background: rgba(0, 0, 0, 0.7); backdrop-filter: blur(8px);">
        <div class="glass-card w-full max-w-4xl p-6 relative border-2 theme-border shadow-2xl">
            <div class="absolute top-4 right-4 z-50">
                <button type="button" onclick="closePieModal()" class="theme-text-muted hover:text-red-500 bg-black/10 hover:bg-red-500/20 p-2 rounded-lg transition-all duration-300">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            
            <div class="mb-5 border-b theme-border pb-4 mt-2 pr-10">
                <div id="modalStatusBadge" class="inline-block px-3 py-1 rounded text-[10px] font-bold uppercase tracking-widest mb-2 text-white">STATUS</div>
                <h2 class="text-lg font-black theme-text-gold" id="modalQuestionText">Soalan...</h2>
            </div>

            <div class="overflow-y-auto max-h-[50vh] pr-2">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="text-[10px] uppercase tracking-widest font-bold border-b theme-border" style="color: var(--text-gold-dark);">
                            <th class="py-2 px-3 w-12">NO.</th>
                            <th class="py-2 px-3 translation-target">NAMA STAF</th>
                            <th class="py-2 px-3 translation-target">JAWAPAN DIPILIH</th> <th class="py-2 px-3 text-right translation-target">ID STAF</th>
                        </tr>
                    </thead>
                    <tbody id="modalTableBody" class="divide-y theme-border text-sm">
                        </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        const chartData = @json($chartData);
        
        Chart.defaults.color = document.body.classList.contains('light-mode') ? '#047857' : '#e5e7eb';
        Chart.defaults.font.family = "'Segoe UI', sans-serif";

        const colorPalette = [
            '#10b981', '#3b82f6', '#f59e0b', '#8b5cf6', '#ec4899', '#06b6d4', '#84cc16', '#f43f5e', '#f97316', '#14b8a6',
            '#6366f1', '#eab308', '#d946ef', '#0ea5e9', '#22c55e', '#ef4444', '#64748b', '#78350f', '#0284c7', '#059669',
            '#4338ca', '#be123c', '#ca8a04', '#4d7c0f', '#0369a1', '#be185d', '#a21caf', '#6d28d9', '#1d4ed8', '#15803d',
            '#b45309', '#047857', '#0f766e', '#1e3a8a', '#4c1d95'
        ];

        // 🌐 KAMUS TERJEMAHAN MAGIS
        const enDictionary = {
            "Laporan Visual: Analisis Soalan Modul": "Visual Report: Module Question Analysis",
            "Kadar kejayaan staf bagi setiap soalan. Klik pada carta untuk melihat senarai nama staf.": "Staff success rate for each question. Click on the chart to view the staff list.",
            "Kembali": "Back",
            "Cetak / Simpan PDF": "Print / Save PDF",
            "✅ Jawapan Betul": "✅ Correct Answer",
            "❌ Jawapan Salah": "❌ Wrong Answer",
            "Belum ada staf yang merekodkan jawapan untuk dianalisis.": "No staff have recorded answers for analysis yet.",
            "NAMA STAF": "STAFF NAME",
            "JAWAPAN DIPILIH": "CHOSEN ANSWER",
            "ID STAF": "STAFF ID",
            "Tiada staf dalam kategori ini.": "No staff in this category."
        };

        const currentLang = localStorage.getItem('lang') || 'ms';

        function applyTranslation() {
            if (currentLang === 'en') {
                document.querySelectorAll('.translation-target').forEach(el => {
                    const text = el.textContent.trim();
                    // Keep emoji for specific buttons if exists
                    const hasCheck = text.includes('✅');
                    const hasCross = text.includes('❌');
                    
                    let lookupText = text;
                    if(hasCheck || hasCross) lookupText = text; // exact match
                    
                    if (lookupText && enDictionary[lookupText]) {
                        el.textContent = enDictionary[lookupText];
                    }
                });
            }
        }
        document.addEventListener('DOMContentLoaded', applyTranslation);

        // FUNGSI BUKA MODAL DENGAN JAWAPAN!
        function openPieModal(statusLabel, isCorrect, fullQuestion, contributors) {
            const badge = document.getElementById('modalStatusBadge');
            badge.textContent = statusLabel;
            badge.className = `inline-block px-3 py-1 rounded text-[10px] font-bold uppercase tracking-widest mb-2 text-white shadow-sm ${isCorrect ? 'bg-green-500' : 'bg-red-500'}`;
            
            document.getElementById('modalQuestionText').textContent = fullQuestion;
            
            const tbody = document.getElementById('modalTableBody');
            tbody.innerHTML = '';

            if(!contributors || contributors.length === 0) {
                const emptyText = currentLang === 'en' ? 'No staff in this category.' : 'Tiada staf dalam kategori ini.';
                tbody.innerHTML = `<tr><td colspan="4" class="py-8 text-center theme-text-muted text-xs italic">${emptyText}</td></tr>`;
            } else {
                contributors.forEach((user, idx) => {
                    // 🔥 MASUKKAN JAWAPAN DENGAN GAYA ITALIC & WARNA KHAS 🔥
                    let answerColor = isCorrect ? 'text-emerald-500 font-bold' : 'text-rose-400 font-medium';
                    
                    tbody.innerHTML += `
                        <tr class="transition" style="background: transparent;" onmouseover="this.style.background='var(--table-hover)'" onmouseout="this.style.background='transparent'">
                            <td class="py-3 px-3 font-bold theme-text-muted">${idx + 1}</td>
                            <td class="py-3 px-3 font-bold theme-text-main">${user.name}</td>
                            <td class="py-3 px-3 text-sm italic ${answerColor}">"${user.answer}"</td>
                            <td class="py-3 px-3 text-right theme-text-gold font-semibold text-xs tracking-wider">${user.staff_id}</td>
                        </tr>
                    `;
                });
            }

            const modal = document.getElementById('pieModal');
            modal.classList.remove('hidden'); modal.classList.add('flex');
            modal.children[0].animate([{ opacity: 0, transform: 'scale(0.95) translateY(10px)' }, { opacity: 1, transform: 'scale(1) translateY(0)' }], { duration: 200, easing: 'ease-out' });
        }

        function closePieModal() {
            document.getElementById('pieModal').classList.add('hidden');
            document.getElementById('pieModal').classList.remove('flex');
        }
        document.getElementById('pieModal').addEventListener('click', function(e) { if (e.target === this) closePieModal(); });

        // LUKIS CARTA
        chartData.forEach((data, index) => {
            const commonOptions = {
                responsive: true, maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'right', labels: { padding: 20, usePointStyle: true, font: {size: 12, weight: 'bold'} } },
                    tooltip: { 
                        callbacks: { 
                            label: function(context) { 
                                let value = context.raw || 0;
                                let total = context.dataset.data.reduce((a, b) => a + b, 0);
                                let percentage = total > 0 ? Math.round((value / total) * 100) + '%' : '0%';
                                return ` ${value} staf (${percentage})`; 
                            } 
                        } 
                    }
                }
            };

            const bgColors = data.labels.map((_, i) => colorPalette[i % colorPalette.length]);
            const wrongBgColors = data.labels.map((_, i) => colorPalette[(colorPalette.length - 1 - i) % colorPalette.length]);

            const labelSuffixCorrect = currentLang === 'en' ? ' - CORRECT ANSWER' : ' - JAWAPAN BETUL';
            const labelSuffixWrong = currentLang === 'en' ? ' - WRONG ANSWER' : ' - JAWAPAN SALAH';

            // 1. Carta Betul
            const ctxCorrect = document.getElementById(`correct-chart-${index}`).getContext('2d');
            new Chart(ctxCorrect, {
                type: 'pie',
                data: {
                    labels: data.labels,
                    datasets: [{
                        data: data.correct_counts,
                        backgroundColor: bgColors,
                        borderWidth: 2, borderColor: document.body.classList.contains('light-mode') ? '#ffffff' : '#0f2818',
                        hoverOffset: 15
                    }]
                },
                options: {
                    ...commonOptions,
                    onClick: (e, elements) => {
                        if (!elements.length) return;
                        const idx = elements[0].index;
                        openPieModal(
                            data.labels[idx] + labelSuffixCorrect, true, 
                            data.full_texts[idx], data.correct_contributors[idx]
                        );
                    }
                }
            });

            // 2. Carta Salah
            const ctxWrong = document.getElementById(`wrong-chart-${index}`).getContext('2d');
            new Chart(ctxWrong, {
                type: 'pie',
                data: {
                    labels: data.labels,
                    datasets: [{
                        data: data.wrong_counts,
                        backgroundColor: wrongBgColors, 
                        borderWidth: 2, borderColor: document.body.classList.contains('light-mode') ? '#ffffff' : '#0f2818',
                        hoverOffset: 15
                    }]
                },
                options: {
                    ...commonOptions,
                    onClick: (e, elements) => {
                        if (!elements.length) return;
                        const idx = elements[0].index;
                        openPieModal(
                            data.labels[idx] + labelSuffixWrong, false, 
                            data.full_texts[idx], data.wrong_contributors[idx]
                        );
                    }
                }
            });
        });
    </script>
    @include('partials.cursor')
</body>
</html>