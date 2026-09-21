<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>S.H.I.E.L.D // Performance Analytics</title>
    @vite('resources/css/app.css')
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@700;900&family=Share+Tech+Mono&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <style>
        /* BASE HIGH CONTRAST SOLID DESIGN */
        body { 
            font-family: 'Share Tech Mono', monospace; 
            background-color: var(--bg-color); 
            color: var(--text-main); 
            overflow-x: hidden; 
            transition: background-color 0.3s ease, color 0.3s ease; 
        }
        
        .title-font { font-family: 'Poppins', sans-serif; font-weight: 900; }

        /* THEME VARIABLES - MATCHING THE SCREENSHOTS */
        :root {
            /* DARK MODE */
            --bg-color: #05000a; 
            --card-bg: #111111; 
            --text-main: #d1d5db; 
            --text-title: #c084fc; 
            --border-color: rgba(168, 85, 247, 0.4); 
            
            --success-text: #4ade80; 
            --danger-text: #f87171; 
            --info-text: #60a5fa; 
            
            --chart-grid: rgba(168, 85, 247, 0.15);
            
            /* Natural Green Cyber Video styling */
            --video-overlay: rgba(5, 0, 10, 0.85); 
            --video-filter: saturate(120%) brightness(90%);
            --header-glow: 0 0 15px rgba(168,85,247,0.7);
        }
        
        .light-mode {
            /* LIGHT MODE */
            --bg-color: #e2e8f0; 
            --card-bg: #ffffff; 
            --text-main: #1f2937; 
            --text-title: #6b21a8; 
            --border-color: rgba(147, 51, 234, 0.3);
            
            --success-text: #16a34a; 
            --danger-text: #dc2626; 
            --info-text: #2563eb; 
            
            --chart-grid: rgba(0, 0, 0, 0.1);

            /* Invert video to make dark cyber lines on a light background */
            --video-overlay: rgba(248, 250, 252, 0.85); 
            --video-filter: invert(100%) hue-rotate(180deg) saturate(150%) brightness(120%);
            --header-glow: none;
        }

        /* INTEGRATED VIDEO BACKGROUND */
        .fixed-video-container {
            position: fixed;
            inset: 0;
            z-index: 0;
            overflow: hidden;
        }

        .theme-video { 
            width: 100%; height: 100%; object-fit: cover;
            filter: var(--video-filter);
            transition: filter 0.5s ease;
        }

        .video-overlay {
            position: absolute; inset: 0;
            background-color: var(--video-overlay); 
            transition: background-color 0.5s ease;
        }

        /* SOLID CARDS */
        .theme-card { 
            background-color: var(--card-bg) !important; 
            border-color: var(--border-color);
            transition: background-color 0.3s ease, border-color 0.3s ease; 
        }

        /* DYNAMIC TEXT LINKERS */
        .text-dynamic-main { color: var(--text-main) !important; transition: color 0.3s ease; }
        .text-dynamic-title { color: var(--text-title) !important; transition: color 0.3s ease; }
        .text-dynamic-success { color: var(--success-text) !important; transition: color 0.3s ease; }
        .text-dynamic-danger { color: var(--danger-text) !important; transition: color 0.3s ease; }
        .text-dynamic-info { color: var(--info-text) !important; transition: color 0.3s ease; }

        /* Slide In Animations */
        .sc-anim { opacity: 0; transition: all 0.6s cubic-bezier(0.16, 1, 0.3, 1); pointer-events: none; }
        .sc-in-left { transform: translateX(-100vw); }
        .sc-in-right { transform: translateX(100vw); }
        .sc-in-up { transform: translateY(100vh); }
        .d-1 { transition-delay: 0.1s; } .d-2 { transition-delay: 0.2s; } .d-3 { transition-delay: 0.3s; } .d-4 { transition-delay: 0.4s; }

        body.loaded .sc-anim { opacity: 1; transform: translate(0, 0) !important; pointer-events: auto; }
        body.exiting .sc-anim { transition: all 0.5s cubic-bezier(0.7, 0, 0.84, 0); transition-delay: 0s !important; opacity: 0; pointer-events: none; }

        /* Scrollbar styling */
        .custom-scrollbar::-webkit-scrollbar { width: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: var(--border-color); border-radius: 3px; }
    </style>
</head>
<body class="min-h-screen relative pb-10">

    @php
        // ==========================================
        // 1. DATA EXTRACTION ENGINE
        // ==========================================
        
        $allStats = \App\Models\GameProgress::with('user')->has('user')->whereNotNull("level_{$room}_score")->get();
        $globalTotalStaff = \App\Models\User::count();
        
        // 🔥 DATA ENGINE: FETCH FAILED ATTEMPTS STATISTICS FOR ACTIVE ROOM 🔥
        $failedRoomStats = class_exists(\App\Models\FailedAttempt::class)
            ? \App\Models\FailedAttempt::with('user')
                ->where('room_number', $room)
                ->select('user_id', 'room_number', \Illuminate\Support\Facades\DB::raw('count(*) as total_fails'))
                ->groupBy('user_id', 'room_number')
                ->orderBy('total_fails', 'desc')
                ->get()
            : collect();

        $chartDetails = [
            'all_agents' => [],
            'accuracy_correct' => [], 'accuracy_incorrect' => [],
            'dist_0' => [], 'dist_1' => [], 'dist_2' => [], 'dist_3' => [],
            'comp_secured' => [], 'comp_standby' => []
        ];

        $maxScore = $allStats->max("level_{$room}_score") ?: 100;

        foreach($allStats as $s) {
            $name = $s->user->name ?? 'Unknown Agent';
            $staffId = $s->user->agent_id ?? 'N/A'; 
            $correctCount = $s->{"level_{$room}_correct"} ?? 0;
            $incorrectCount = $s->{"level_{$room}_incorrect"} ?? 0;
            $score = $s->{"level_{$room}_score"} ?? 0;
            
            $wrongAnswersData = $s->{"level_{$room}_wrong_answers"} ?? '[]';
            $wrongAnswersList = is_string($wrongAnswersData) ? json_decode($wrongAnswersData, true) : (is_array($wrongAnswersData) ? $wrongAnswersData : []);
            if(!is_array($wrongAnswersList)) $wrongAnswersList = [];

            $agentData = [
                'name' => $name, 
                'staff_id' => $staffId, 
                'score' => $score,
                'correct' => $correctCount,
                'incorrect' => $incorrectCount,
                'wrong_list' => $wrongAnswersList
            ];
            
            $chartDetails['all_agents'][] = $agentData;

            if ($correctCount > 0) $chartDetails['accuracy_correct'][] = array_merge($agentData, ['score' => $correctCount]);
            if ($incorrectCount > 0) $chartDetails['accuracy_incorrect'][] = array_merge($agentData, ['score' => $incorrectCount]);
            
            if($score < ($maxScore * 0.4)) $chartDetails['dist_0'][] = $agentData;
            elseif($score < ($maxScore * 0.7)) $chartDetails['dist_1'][] = $agentData;
            elseif($score < $maxScore) $chartDetails['dist_2'][] = $agentData;
            else $chartDetails['dist_3'][] = $agentData;
            
            if ($score > 0) {
                $chartDetails['comp_secured'][] = array_merge($agentData, ['score' => 'CLEARED']);
            } else {
                $chartDetails['comp_standby'][] = array_merge($agentData, ['score' => 'STANDBY']);
            }
        }

        $top10 = $allStats->sortByDesc("level_{$room}_score")->take(10);
        $topLabels = $top10->count() > 0 ? array_values($top10->map(fn($p) => $p->user->name ?? 'Agent')->toArray()) : [];
        $topData = $top10->count() > 0 ? array_values($top10->map(fn($p) => $p->{"level_{$room}_score"})->toArray()) : [];
        
        $topDetails = $top10->count() > 0 ? array_values($top10->map(function($p) use($room) {
            return [[
                'name' => $p->user->name ?? 'Agent',
                'staff_id' => $p->user->agent_id ?? 'N/A',
                'score' => $p->{"level_{$room}_score"} ?? 0,
                'correct' => $p->{"level_{$room}_correct"} ?? 0,
                'incorrect' => $p->{"level_{$room}_incorrect"} ?? 0
            ]];
        })->toArray()) : [];

        $totalCorrect = $allStats->sum("level_{$room}_correct") ?? 0;
        $totalIncorrect = $allStats->sum("level_{$room}_incorrect") ?? 0;
        $accuracyData = [$totalCorrect, $totalIncorrect];

        $distributionData = [count($chartDetails['dist_0']), count($chartDetails['dist_1']), count($chartDetails['dist_2']), count($chartDetails['dist_3'])];
        $completionData = [count($chartDetails['comp_secured']), count($chartDetails['comp_standby'])];

        // ==========================================
        // 🚨 UPGRADED ARCADE DATA ENGINE
        // ==========================================
        $arcadeData = [];
        if($room == 6 && isset($miniGames) && $miniGames->count() > 0) {
            foreach($miniGames as $mg) {
                $clearedUsers = [];
                $moduleCorrect = 0;
                $moduleIncorrect = 0;
                $moduleWrongList = [];
                
                if(isset($allStats)) {
                    foreach($allStats as $prog) {
                        $completedArr = is_string($prog->completed_minigames) ? json_decode($prog->completed_minigames, true) : ($prog->completed_minigames ?? []);
                        
                        if (is_array($completedArr) && in_array($mg->id, $completedArr)) {
                            
                            // Grab the master dictionary
                            $wListStr = $prog->level_6_wrong_answers ?? '[]';
                            $arcadeStatsAll = is_string($wListStr) ? json_decode($wListStr, true) : (is_array($wListStr) ? $wListStr : []);
                            if(!is_array($arcadeStatsAll)) $arcadeStatsAll = [];

                            $c = 0;
                            $i = 0;
                            $wList = [];

                            // Extract the specific data for THIS minigame ID
                            if (isset($arcadeStatsAll[$mg->id]) && is_array($arcadeStatsAll[$mg->id])) {
                                $c = (int) ($arcadeStatsAll[$mg->id]['correct'] ?? 0);
                                $i = (int) ($arcadeStatsAll[$mg->id]['incorrect'] ?? 0);
                                $wList = $arcadeStatsAll[$mg->id]['logs'] ?? [];
                            }

                            // Accumulate the global chart stats
                            $moduleCorrect += $c;
                            $moduleIncorrect += $i;

                            $agentInfo = [
                                'name' => $prog->user->name ?? 'Unknown',
                                'staff_id' => $prog->user->agent_id ?? 'N/A',
                                'score' => $mg->base_score,
                                'correct' => $c,
                                'incorrect' => $i,
                                'wrong_list' => $wList
                            ];

                            $clearedUsers[] = $agentInfo;

                            // If they got anything wrong in THIS module, add them to the list
                            if ($i > 0) {
                                $moduleWrongList[] = array_merge($agentInfo, ['score' => $prog->level_6_score ?? 0]);
                            }
                        }
                    }
                }
                
                $clearedCount = count($clearedUsers);
                $arcadeData[$mg->id] = [
                    'title' => $mg->title,
                    'type' => str_replace('_', ' ', $mg->game_type),
                    'base_score' => $mg->base_score,
                    'cleared_count' => $clearedCount,
                    'standby_count' => max(0, $globalTotalStaff - $clearedCount),
                    'total_generated' => $clearedCount * $mg->base_score,
                    'cleared_users' => $clearedUsers,
                    'accuracy_data' => [$moduleCorrect, $moduleIncorrect],
                    'accuracy_incorrect' => $moduleWrongList
                ];
            }
        }
    @endphp

    <script>
        window.AdminData = {
            room: {{ $room }},
            chartData: {
                topLabels: @json($topLabels),
                topData: @json($topData),
                topDetails: @json($topDetails),
                accuracyData: @json($accuracyData),
                distributionData: @json($distributionData),
                completionData: @json($completionData),
                chartDetailsData: @json($chartDetails)
            },
            arcadeData: @json($arcadeData)
        };
        window.activeArcadeId = null;
    </script>

    <div class="fixed-video-container">
        <video autoplay loop muted playsinline class="theme-video">
            <source src="{{ asset('video/videoplayback.webm') }}" type="video/webm">
        </video>
        <div class="video-overlay pointer-events-none"></div>
    </div>

    <div class="fixed top-4 right-4 z-50 flex gap-3 sc-anim sc-in-right d-4">
        <button id="lang-toggle" class="theme-card border-2 px-4 py-2 text-xs font-bold tracking-wider text-dynamic-title rounded-full hover:bg-purple-600 hover:text-white transition">
            🇲🇾 BAHASA
        </button>
        <button id="theme-toggle" class="theme-card border-2 px-4 py-2 text-xs font-bold tracking-wider text-dynamic-title rounded-full hover:bg-purple-600 hover:text-white transition">
            ☀️ LIGHT MODE
        </button>
    </div>

    <div class="max-w-6xl mx-auto relative z-10 p-4 md:p-8 mt-4">
        
        <header class="flex flex-col md:flex-row justify-between items-start md:items-end mb-8 pb-4 border-b-2" style="border-color: var(--border-color);">
            <div class="sc-anim sc-in-left d-1">
                <h1 class="title-font text-3xl tracking-wider uppercase mb-1 text-dynamic-title" style="text-shadow: var(--header-glow);" data-en="PERFORMANCE ANALYTICS" data-ms="ANALISIS PRESTASI">PERFORMANCE ANALYTICS</h1>
                <p class="font-bold tracking-widest text-xs uppercase text-dynamic-title opacity-80" data-en="{{ $room == 6 ? 'ARCADE MODULES BREAKDOWN' : 'ROOM-BY-ROOM BREAKDOWN' }}" data-ms="{{ $room == 6 ? 'PECAHAN MODUL ARKED' : 'PECAHAN MENGIKUT BILIK' }}">{{ $room == 6 ? 'ARCADE MODULES BREAKDOWN' : 'ROOM-BY-ROOM BREAKDOWN' }}</p>
            </div>
            
            <a href="{{ route('admin.dashboard') }}" class="sc-anim sc-in-right d-1 mt-4 md:mt-0 px-6 py-3 theme-card border-2 rounded-full text-xs font-bold tracking-widest uppercase flex items-center gap-2 text-dynamic-title hover:bg-purple-600 hover:text-white transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                <span data-en="Return to Overview" data-ms="Kembali ke Ringkasan">Return to Overview</span>
            </a>
        </header>

        <div class="flex flex-wrap gap-3 mb-8 sc-anim sc-in-left d-2">
            @for ($i = 1; $i <= 6; $i++)
                <a href="{{ route('admin.analytics', $i) }}" 
                   class="nav-trigger px-6 py-3 uppercase tracking-widest text-sm font-bold border-2 rounded transition-all 
                   {{ $room == $i ? 'bg-purple-600 text-white border-purple-600' : 'theme-card text-dynamic-title' }}">
                    Room 0{{ $i }}
                </a>
            @endfor
        </div>

        @if($room == 6)
            {{-- ========================================== --}}
            {{-- 🎮 ROOM 6: ARCADE ANALYTICS MATRIX         --}}
            {{-- ========================================== --}}
            @if(count($arcadeData) > 0)
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 sc-anim sc-in-up d-3">
                    
                    <div class="col-span-1 flex flex-col gap-4 max-h-[600px] overflow-y-auto custom-scrollbar pr-2">
                        <h3 class="text-sm font-bold text-dynamic-main uppercase tracking-widest border-b-2 pb-2 mb-2" style="border-color: var(--border-color);" data-en="Deployed Modules" data-ms="Modul Dilancarkan">Deployed Modules</h3>
                        
                        @foreach($arcadeData as $id => $data)
                            <button onclick="loadArcadeData({{ $id }})" id="btn-mg-{{ $id }}" class="mg-selector theme-card p-4 border-2 rounded-lg text-left transition-all hover:border-purple-500 cursor-pointer w-full focus:outline-none">
                                <div class="text-[10px] text-purple-500 font-bold uppercase tracking-widest mb-1">{{ $data['type'] }}</div>
                                <h4 class="text-dynamic-main font-bold text-lg leading-tight uppercase">{{ $data['title'] }}</h4>
                                <div class="flex justify-between items-center mt-3">
                                    <span class="text-xs text-dynamic-main font-mono opacity-70 border border-gray-500 px-2 py-1 rounded">{{ $data['base_score'] }} PTS</span>
                                    <span class="text-xs text-dynamic-success font-bold">{{ $data['cleared_count'] }} <span data-en="Cleared" data-ms="Selesai">Cleared</span></span>
                                </div>
                            </button>
                        @endforeach
                    </div>

                    <div class="col-span-1 lg:col-span-2 flex flex-col gap-6">
                        <div class="theme-card p-6 border-2 rounded-lg relative">
                            <h2 id="active-mg-title" class="title-font text-3xl text-dynamic-main uppercase mb-2">Select a Module</h2>
                            <p id="active-mg-type" class="text-dynamic-title font-mono text-base font-black mb-4 tracking-widest">Awaiting Selection...</p>
                            
                            <div class="grid grid-cols-2 gap-4 border-t-2 pt-4" style="border-color: var(--border-color);">
                                <div>
                                    <p class="text-[10px] text-dynamic-main font-bold uppercase tracking-widest opacity-80" data-en="TOTAL POINTS GENERATED" data-ms="JUMLAH MATA DIHASILKAN">TOTAL POINTS GENERATED</p>
                                    <p id="active-mg-generated" class="text-3xl font-black text-dynamic-info mt-1">0</p>
                                </div>
                                <div>
                                    <p class="text-[10px] text-dynamic-main font-bold uppercase tracking-widest opacity-80" data-en="CLEAR RATE" data-ms="KADAR PENYELESAIAN">CLEAR RATE</p>
                                    <p id="active-mg-rate" class="text-3xl font-black text-dynamic-success mt-1">0%</p>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 flex-grow">
                            <div class="theme-card p-6 border-2 rounded-lg flex flex-col">
                                <div class="flex justify-between items-center border-b-2 pb-2 mb-4" style="border-color: var(--border-color);">
                                    <h3 class="text-sm font-bold text-dynamic-title uppercase tracking-widest" data-en="Mission Status" data-ms="Status Misi">Mission Status</h3>
                                    <button onclick="exportCSV('cleared_users', 'Module_Status_Room_6', 'standard')" class="text-[10px] uppercase font-bold text-dynamic-title border-2 rounded px-3 py-1 transition-colors hover:bg-purple-600 hover:text-white" style="border-color: var(--border-color);">CSV</button>
                                </div>
                                <div class="h-48 relative w-full flex-grow"><canvas id="arcadeChart"></canvas></div>
                            </div>

                            <div class="theme-card p-6 border-2 rounded-lg flex flex-col">
                                <div class="flex justify-between items-center border-b-2 pb-2 mb-4" style="border-color: var(--border-color);">
                                    <h3 class="text-sm font-bold text-dynamic-title uppercase tracking-widest" data-en="Accuracy Ratio" data-ms="Nisbah Ketepatan">Accuracy Ratio</h3>
                                    <button onclick="exportCSV('accuracy_incorrect', 'Module_Accuracy_Room_6', 'incorrect')" class="text-[10px] uppercase font-bold text-dynamic-title border-2 rounded px-3 py-1 transition-colors hover:bg-purple-600 hover:text-white" style="border-color: var(--border-color);">CSV</button>
                                </div>
                                <div class="h-48 relative w-full flex-grow"><canvas id="arcadeAccuracyChart"></canvas></div>
                            </div>
                        </div>

                        <div class="theme-card p-6 border-2 rounded-lg flex flex-col max-h-[300px]">
                            <div class="flex justify-between items-center border-b-2 pb-2 mb-4" style="border-color: var(--border-color);">
                                <h3 class="text-sm font-bold text-dynamic-title uppercase tracking-widest" data-en="Successful Operatives" data-ms="Operatif Berjaya">Successful Operatives</h3>
                                <button onclick="exportCSV('cleared_users', 'Operatives_List_Room_6', 'standard')" class="text-[10px] uppercase font-bold text-dynamic-title border-2 rounded px-3 py-1 transition-colors hover:bg-purple-600 hover:text-white" style="border-color: var(--border-color);">CSV</button>
                            </div>
                            <div class="overflow-y-auto custom-scrollbar pr-2 flex-grow">
                                <table class="w-full text-left border-collapse">
                                    <thead>
                                        <tr class="text-[10px] uppercase tracking-widest font-bold border-b-2" style="border-color: var(--border-color);">
                                            <th class="py-2 px-3 text-dynamic-main">AGENT</th>
                                            <th class="py-2 px-3 text-right text-dynamic-main">ID</th>
                                        </tr>
                                    </thead>
                                    <tbody id="active-mg-table" class="text-sm font-mono font-bold">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="col-span-full theme-card rounded-lg p-10 border-4 border-red-500 text-center sc-anim d-3">
                    <h3 class="title-font text-2xl text-red-600 font-bold mb-2 uppercase" data-en="NO MODULES DETECTED" data-ms="TIADA MODUL DIKESAN">NO MODULES DETECTED</h3>
                    <p class="text-dynamic-main font-bold font-mono" data-en="The arcade mainframe is currently empty." data-ms="Sistem arked utama sedang kosong.">The arcade mainframe is currently empty.</p>
                </div>
            @endif

        @else
            {{-- ========================================== --}}
            {{-- 📊 ROOMS 1-5: STANDARD ANALYTICS            --}}
            {{-- ========================================== --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8 sc-anim sc-in-up d-2">
                
                <div class="theme-card p-6 border-2 rounded-lg flex flex-col">
                    <div class="flex justify-between items-center border-b-2 pb-2 mb-2" style="border-color: var(--border-color);">
                        <h3 class="text-sm font-bold text-dynamic-title uppercase tracking-widest flex gap-2 items-center">
                            <span data-en="Top 10 Operatives" data-ms="10 Operatif Terbaik">Top 10 Operatives</span>
                        </h3>
                        <button onclick="exportCSV('top', 'Top_Operatives_Room_{{ $room }}', 'standard')" class="text-[10px] uppercase font-bold text-dynamic-title border-2 rounded px-3 py-1 transition-colors hover:bg-purple-600 hover:text-white" style="border-color: var(--border-color);">CSV</button>
                    </div>
                    <p class="text-[10px] text-dynamic-main mb-4 font-bold opacity-80" data-en="Shows the top performing agents based on total points." data-ms="Menunjukkan ejen berprestasi tertinggi berdasarkan jumlah mata.">Shows the top performing agents based on total points.</p>
                    <div class="h-64 relative w-full flex-grow"><canvas id="topAgentsChart"></canvas></div>
                </div>
                
                <div class="theme-card p-6 border-2 rounded-lg flex flex-col">
                    <div class="flex justify-between items-center border-b-2 pb-2 mb-2" style="border-color: var(--border-color);">
                        <h3 class="text-sm font-bold text-dynamic-title uppercase tracking-widest flex gap-2 items-center">
                            <span data-en="Accuracy Ratio" data-ms="Nisbah Ketepatan">Accuracy Ratio</span>
                        </h3>
                        <button onclick="exportCSV('accuracy_incorrect', 'Accuracy_Incorrect_Room_{{ $room }}', 'incorrect')" class="text-[10px] uppercase font-bold text-dynamic-title border-2 rounded px-3 py-1 transition-colors hover:bg-purple-600 hover:text-white" style="border-color: var(--border-color);">CSV</button>
                    </div>
                    <p class="text-[10px] text-dynamic-main mb-4 font-bold opacity-80" data-en="Compares the total correct answers against incorrect ones." data-ms="Membandingkan jumlah jawapan betul dengan yang salah.">Compares the total correct answers against incorrect ones.</p>
                    <div class="h-64 relative w-full flex-grow"><canvas id="accuracyChart"></canvas></div>
                </div>
                
                <div class="theme-card p-6 border-2 rounded-lg flex flex-col">
                    <div class="flex justify-between items-center border-b-2 pb-2 mb-2" style="border-color: var(--border-color);">
                        <h3 class="text-sm font-bold text-dynamic-title uppercase tracking-widest flex gap-2 items-center">
                            <span data-en="Score Distribution" data-ms="Taburan Markah">Score Distribution</span>
                        </h3>
                        <button onclick="exportCSV('all_agents', 'Score_Distribution_Room_{{ $room }}', 'standard')" class="text-[10px] uppercase font-bold text-dynamic-title border-2 rounded px-3 py-1 transition-colors hover:bg-purple-600 hover:text-white" style="border-color: var(--border-color);">CSV</button>
                    </div>
                    <p class="text-[10px] text-dynamic-main mb-4 font-bold opacity-80" data-en="Groups agents into performance tiers based on their scores." data-ms="Mengelompokkan ejen mengikut tahap prestasi berdasarkan markah.">Groups agents into performance tiers based on their scores.</p>
                    <div class="h-64 relative w-full flex-grow"><canvas id="distributionChart"></canvas></div>
                </div>
                
                <div class="theme-card p-6 border-2 rounded-lg flex flex-col">
                    <div class="flex justify-between items-center border-b-2 pb-2 mb-2" style="border-color: var(--border-color);">
                        <h3 class="text-sm font-bold text-dynamic-title uppercase tracking-widest flex gap-2 items-center">
                            <span data-en="Mission Status" data-ms="Status Misi">Mission Status</span>
                        </h3>
                        <button onclick="exportCSV('all_agents', 'Mission_Status_Room_{{ $room }}', 'standard')" class="text-[10px] uppercase font-bold text-dynamic-title border-2 rounded px-3 py-1 transition-colors hover:bg-purple-600 hover:text-white" style="border-color: var(--border-color);">CSV</button>
                    </div>
                    <p class="text-[10px] text-dynamic-main mb-4 font-bold opacity-80" data-en="Displays the ratio of agents who have cleared the room versus those attempting." data-ms="Memaparkan nisbah ejen yang telah melepasi bilik berbanding yang masih mencuba.">Displays the ratio of agents who have cleared the room versus those attempting.</p>
                    <div class="h-64 relative w-full flex-grow"><canvas id="completionChart"></canvas></div>
                </div>
            </div>

            <div class="theme-card border-2 rounded-lg sc-anim sc-in-up d-3 overflow-hidden mb-8">
                <div class="overflow-x-auto p-0 m-0">
                    <table class="w-full text-left whitespace-nowrap border-collapse">
                        <thead class="uppercase tracking-widest text-sm md:text-base font-extrabold border-b-4" style="border-color: var(--border-color);">
                            <tr>
                                <th class="px-6 py-5 text-dynamic-main" data-en="Rank" data-ms="Kedudukan">Rank</th>
                                <th class="px-6 py-5 text-dynamic-main" data-en="Agent Name" data-ms="Nama Ejen">Agent Name</th>
                                <th class="px-6 py-5 text-center text-dynamic-main" data-en="Correct" data-ms="Betul">Correct</th>
                                <th class="px-6 py-5 text-center text-dynamic-main" data-en="Incorrect" data-ms="Salah">Incorrect</th>
                                <th class="px-6 py-5 text-right text-dynamic-main" data-en="Room Score" data-ms="Markah Bilik">Room Score</th>
                            </tr>
                        </thead>
                        <tbody class="font-mono font-bold">
                            @forelse($performances ?? [] as $index => $p)
                                <tr style="border-bottom: 1px solid var(--border-color);">
                                    <td class="px-6 py-5 text-dynamic-main text-xl opacity-70">
                                        #{{ ($performances->currentPage() - 1) * $performances->perPage() + $loop->iteration }}
                                    </td>
                                    <td class="px-6 py-5 text-dynamic-main text-2xl">{{ $p->user->name ?? 'Unknown' }}</td>
                                    <td class="px-6 py-5 text-center text-dynamic-success text-xl">{{ $p->{"level_{$room}_correct"} ?? 0 }}</td>
                                    <td class="px-6 py-5 text-center text-dynamic-danger text-xl">{{ $p->{"level_{$room}_incorrect"} ?? 0 }}</td>
                                    <td class="px-6 py-5 text-right text-dynamic-info text-2xl">{{ $p->{"level_{$room}_score"} }} pts</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-10 text-center text-dynamic-main uppercase tracking-widest text-sm font-bold opacity-50" data-en="No performance data recorded for this room yet." data-ms="Tiada data prestasi direkodkan untuk bilik ini lagi.">No performance data recorded for this room yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="first-letter:px-6 py-4">{{ isset($performances) ? $performances->links() : '' }}</div>
            </div>
        @endif

        {{-- ========================================================================== --}}
        {{-- 🚨 NEW INJECTED DRAWER: CONTEXTUAL FAILED ATTEMPTS TRACKING SYSTEM 🚨 --}}
        {{-- ========================================================================== --}}
        <div class="theme-card border-2 rounded-lg sc-anim sc-in-up d-4 overflow-hidden mt-4">
            <div class="p-5 border-b-2 flex justify-between items-center bg-black/20" style="border-color: var(--border-color);">
                <h3 class="text-base font-bold text-dynamic-danger uppercase tracking-widest flex items-center gap-3">
                    <span class="w-2.5 h-2.5 bg-red-500 rounded-full animate-pulse shadow-[0_0_10px_rgba(239,68,68,0.7)]"></span>
                    <span data-en="System Integrity Breach Logs (Failures)" data-ms="Log Kegagalan Integriti Sistem (Gagal)">System Integrity Breach Logs (Failures)</span>
                </h3>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left whitespace-nowrap border-collapse">
                    <thead class="uppercase tracking-widest text-xs font-bold border-b-2" style="border-color: var(--border-color);">
                        <tr>
                            <th class="px-6 py-4 text-dynamic-main" data-en="Operative Identity" data-ms="Identiti Operatif">Operative Identity</th>
                            <th class="px-6 py-4浏览 text-dynamic-main" data-en="Staff ID" data-ms="ID Kakitangan">Staff ID</th>
                            <th class="px-6 py-4 text-center text-dynamic-main" data-en="Target Location" data-ms="Lokasi Sasaran">Target Location</th>
                            <th class="px-6 py-4 text-right text-dynamic-main" data-en="Total Lockouts" data-ms="Jumlah Kegagalan">Total Lockouts</th>
                        </tr>
                    </thead>
                    <tbody class="font-mono font-bold divide-y" style="border-color: var(--chart-grid);">
                        @forelse($failedRoomStats as $fs)
                            <tr class="hover:bg-red-500/5 transition-colors">
                                <td class="px-6 py-4 text-dynamic-main text-lg">{{ $fs->user->name ?? 'Unknown Agent' }}</td>
                                <td class="px-6 py-4 text-gray-400 text-sm tracking-widest">{{ $fs->user->agent_id ?? 'N/A' }}</td>
                                <td class="px-6 py-4 text-center">
                                    <span class="bg-red-950/40 text-red-400 border border-red-500/30 px-3 py-1 rounded text-xs tracking-widest font-sans font-black">
                                        @if($room == 6)
                                            ARCADE MODULE
                                        @else
                                            ROOM 0{{ $fs->room_number }}
                                        @endif
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right text-dynamic-danger text-xl font-black">
                                    {{ $fs->total_fails }} <span class="text-xs font-normal opacity-60">Attempts</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center text-dynamic-main uppercase tracking-widest text-sm font-bold opacity-40 italic" data-en="No recorded system integrity failures detected for this asset context." data-ms="Tiada rekod kegagalan integriti dikesan bagi konteks aset ini.">No recorded system integrity failures detected for this asset context.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        {{-- ========================================================================== --}}

    </div>

    <div id="chartModal" class="fixed inset-0 hidden items-center justify-center p-4" style="z-index: 9999; background: rgba(0, 0, 0, 0.9);">
        <div class="theme-card w-full max-w-4xl relative border-4 rounded-lg overflow-hidden">
            
            <div class="absolute top-4 right-4" style="z-index: 10000;">
                <button type="button" onclick="closeChartModal()" class="text-dynamic-main border-2 rounded px-3 py-1 transition-all duration-300 font-bold hover:bg-purple-600 hover:text-white hover:border-purple-600" style="border-color: var(--border-color);" title="Close">
                    X CLOSE
                </button>
            </div>
            
            <div class="p-6 pb-0 mb-5 mt-2">
                <h2 class="text-2xl md:text-3xl font-black text-dynamic-title tracking-wide uppercase" id="chartModalTitle">Chart Title</h2>
                <p class="text-sm text-dynamic-main font-bold mt-1 uppercase tracking-widest opacity-80" data-en="Data Contributors" data-ms="Penyumbang Data">Data Contributors</p>
            </div>

            <div class="overflow-y-auto max-h-[60vh] custom-scrollbar">
                <table class="w-full text-left border-collapse">
                    <thead id="chartModalHead" class="border-b-2" style="border-color: var(--border-color);">
                        </thead>
                    <tbody id="chartModalBody" class="text-base md:text-lg font-mono font-bold">
                        </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        // ==========================================
        // 3. MASTER JAVASCRIPT ENGINE
        // ==========================================
        let isDomReady = false;
        let chartInstances = {};

        const bodyEl = document.body;
        const themeBtn = document.getElementById('theme-toggle');
        const langBtn = document.getElementById('lang-toggle');
        const translatables = document.querySelectorAll('[data-en]');

        let currentTheme = localStorage.getItem('shield_theme') || 'dark';
        let currentLang = localStorage.getItem('shield_lang') || 'en';

        if (currentTheme === 'light') { bodyEl.classList.add('light-mode'); if(themeBtn) themeBtn.innerHTML = '🌙 DARK MODE'; }

        window.addEventListener('DOMContentLoaded', () => { 
            isDomReady = true;
            setTimeout(() => { document.body.classList.add('loaded'); }, 50); 
            applyLanguage(currentLang); 
            setTimeout(() => { window.parent.postMessage('ensureMusicPlaying', '*'); }, 100);
        });

        document.querySelectorAll('.nav-trigger').forEach(trigger => {
            trigger.addEventListener('click', function(e) {
                e.preventDefault(); 
                const targetUrl = this.getAttribute('href');
                document.body.classList.remove('loaded');
                document.body.classList.add('exiting');
                setTimeout(() => { window.location.href = targetUrl; }, 500); 
            });
        });

        function applyLanguage(lang) {
            if(langBtn) langBtn.innerHTML = lang === 'en' ? '🇲🇾 BAHASA' : '🇬🇧 ENGLISH';
            translatables.forEach(el => {
                if (['SPAN', 'P', 'H1', 'H3', 'TH', 'TD', 'DIV'].includes(el.tagName)) {
                    if(el.getAttribute(`data-${lang}`)) {
                        const textSpan = el.querySelector('span');
                        if(textSpan && !el.hasAttribute('data-en')) textSpan.innerText = el.getAttribute(`data-${lang}`);
                        else el.innerHTML = el.getAttribute(`data-${lang}`);
                    }
                }
            });
            
            if (window.AdminData.room === 6) {
                const activeBtn = document.querySelector('.mg-selector.border-purple-500') || document.querySelector('.mg-selector.bg-gray-800') || document.querySelector('.mg-selector.dark\\:bg-gray-200');
                if(activeBtn) {
                    loadArcadeData(activeBtn.id.replace('btn-mg-', ''));
                } else if(Object.keys(window.AdminData.arcadeData).length > 0) {
                    loadArcadeData(Object.keys(window.AdminData.arcadeData)[0]);
                }
            } else {
                renderStandardCharts(); 
            }
        }

        if(themeBtn) {
            themeBtn.addEventListener('click', () => {
                currentTheme = currentTheme === 'dark' ? 'light' : 'dark';
                localStorage.setItem('shield_theme', currentTheme);
                if(currentTheme === 'light') { bodyEl.classList.add('light-mode'); themeBtn.innerHTML = '🌙 DARK MODE'; } 
                else { bodyEl.classList.remove('light-mode'); themeBtn.innerHTML = '☀️ LIGHT MODE'; }
                
                if (window.AdminData.room === 6) {
                    const activeBtn = document.querySelector('.mg-selector.border-purple-500') || document.querySelector('.mg-selector.bg-gray-800') || document.querySelector('.mg-selector.dark\\:bg-gray-200');
                    if(activeBtn) loadArcadeData(activeBtn.id.replace('btn-mg-', ''));
                } else {
                    renderStandardCharts(); 
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

        // --- 🚨 EXPORT CSV ENGINE ---
        function exportCSV(category, filename, viewMode = 'standard') {
            let dataArray = [];
            
            if (window.AdminData.room === 6) {
                const activeId = window.activeArcadeId;
                if (!activeId || !window.AdminData.arcadeData[activeId]) return;
                dataArray = window.AdminData.arcadeData[activeId][category] || [];
            } else {
                dataArray = window.AdminData.chartData.chartDetailsData[category] || [];
                if (category === 'top') {
                    dataArray = window.AdminData.chartData.topDetails.flat();
                }
            }

            if (!dataArray || dataArray.length === 0) {
                const msg = currentLang === 'en' ? "No data available to export!" : "Tiada data untuk dieksport!";
                alert(msg);
                return;
            }

            let csvContent = "data:text/csv;charset=utf-8,\uFEFF";
            
            if (viewMode === 'incorrect') {
                csvContent += "Agent Name,Staff ID,Incorrect Answer(s),Score\n";
                dataArray.forEach(row => {
                    let wrongList = (row.wrong_list && row.wrong_list.length > 0) ? row.wrong_list.join(" | ").replace(/"/g, '""') : 'No specific logs';
                    csvContent += `"${row.name}","${row.staff_id}","${wrongList}","${row.score}"\n`;
                });
            } else {
                csvContent += "Agent Name,Staff ID,Correct,Incorrect,Score\n";
                dataArray.forEach(row => {
                    let c = row.correct !== undefined ? row.correct : '-';
                    let i = row.incorrect !== undefined ? row.incorrect : '-';
                    csvContent += `"${row.name}","${row.staff_id}","${c}","${i}","${row.score}"\n`;
                });
            }

            const encodedUri = encodeURI(csvContent);
            const link = document.createElement("a");
            link.setAttribute("href", encodedUri);
            link.setAttribute("download", filename + ".csv");
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }

        // --- ROOM 6 ARCADE LOGIC ---
        function loadArcadeData(gameId) {
            window.activeArcadeId = gameId; 
            const data = window.AdminData.arcadeData[gameId];
            if(!data) return;

            document.querySelectorAll('.mg-selector').forEach(btn => {
                btn.classList.remove('bg-gray-800', 'dark:bg-gray-200');
            });
            const activeBtn = document.getElementById('btn-mg-' + gameId);
            if(activeBtn) activeBtn.classList.add('bg-gray-800', 'dark:bg-gray-200');

            document.getElementById('active-mg-title').innerText = data.title;
            document.getElementById('active-mg-type').innerText = "ENGINE: " + data.type;
            document.getElementById('active-mg-generated').innerText = data.total_generated;
            
            const totalAgents = data.cleared_count + data.standby_count;
            const rate = totalAgents > 0 ? Math.round((data.cleared_count / totalAgents) * 100) : 0;
            document.getElementById('active-mg-rate').innerText = rate + '%';

            const tbody = document.getElementById('active-mg-table');
            tbody.innerHTML = '';
            if(data.cleared_users.length === 0) {
                tbody.innerHTML = `<tr><td colspan="2" class="py-4 text-center text-dynamic-main opacity-50 italic text-xs">No operatives have cleared this module yet.</td></tr>`;
            } else {
                data.cleared_users.forEach(u => {
                    tbody.innerHTML += `
                        <tr style="border-bottom: 1px solid var(--chart-grid);">
                            <td class="py-3 px-3 text-dynamic-main text-base">${u.name}</td>
                            <td class="py-3 px-3 text-right text-dynamic-main opacity-100 text-sm font-black tracking-widest">${u.staff_id}</td>
                        </tr>
                    `;
                });
            }

            if(chartInstances.arcade) chartInstances.arcade.destroy();
            if(chartInstances.arcadeAcc) chartInstances.arcadeAcc.destroy();
            
            const isLight = document.body.classList.contains('light-mode');
            const chartColor = isLight ? '#000000' : '#ffffff';
            const chartColorSec = isLight ? '#cccccc' : '#333333';
            const purpleBarColor = isLight ? '#9333ea' : '#a855f7'; 
            const successColor = isLight ? '#16a34a' : '#4ade80';
            const dangerColor = isLight ? '#dc2626' : '#f87171';
            const standbyColor = isLight ? '#e5e7eb' : '#333333';
            
            const l_comp = currentLang === 'en' ? ['Cleared', 'Standby'] : ['Selesai', 'Sedia'];
            const l_accuracy = currentLang === 'en' ? ['Correct', 'Incorrect'] : ['Betul', 'Salah'];

            Chart.defaults.color = chartColor;
            Chart.defaults.font.family = "'Share Tech Mono', monospace";
            
            const compData = (data.cleared_count === 0 && data.standby_count === 0) ? [0, 1] : [data.cleared_count, data.standby_count];

            chartInstances.arcade = new Chart(document.getElementById('arcadeChart').getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: l_comp,
                    datasets: [{
                        data: compData,
                        backgroundColor: [purpleBarColor, standbyColor],
                        borderColor: isLight ? '#ffffff' : '#000000', borderWidth: 2
                    }]
                },
                options: { 
                    responsive: true, maintainAspectRatio: false, cutout: '70%',
                    plugins: { legend: { position: 'bottom', labels: { color: Chart.defaults.color } } } 
                }
            });

            chartInstances.arcadeAcc = new Chart(document.getElementById('arcadeAccuracyChart').getContext('2d'), {
                type: 'bar',
                data: {
                    labels: l_accuracy,
                    datasets: [{
                        data: data.accuracy_data,
                        backgroundColor: [successColor, dangerColor], borderRadius: 2
                    }]
                },
                options: { 
                    responsive: true, maintainAspectRatio: false,
                    onClick: (e, elements) => {
                        if (!elements.length) return;
                        openChartModal('ACCURACY: INCORRECT', 'SCORE', data.accuracy_incorrect, 'incorrect');
                    },
                    scales: { y: { grid: { color: isLight ? 'rgba(0,0,0,0.1)' : 'rgba(255,255,255,0.1)' } }, x: { grid: { display: false } } }, plugins: { legend: { display: false } } 
                }
            });
        }

        // --- ROOM 1-5 STANDARD LOGIC ---
        function renderStandardCharts() {
            if (!isDomReady) return;

            if (chartInstances.top) chartInstances.top.destroy();
            if (chartInstances.acc) chartInstances.acc.destroy();
            if (chartInstances.dist) chartInstances.dist.destroy();
            if (chartInstances.comp) chartInstances.comp.destroy();

            const isLight = document.body.classList.contains('light-mode');
            const chartColor = isLight ? '#000000' : '#ffffff';
            const chartColorSec = isLight ? '#cccccc' : '#333333';
            const gridColor = isLight ? 'rgba(0, 0, 0, 0.1)' : 'rgba(255, 255, 255, 0.1)';
            const purpleBarColor = isLight ? '#9333ea' : '#a855f7'; 
            const successColor = isLight ? '#16a34a' : '#4ade80';
            const dangerColor = isLight ? '#dc2626' : '#f87171';
            const standbyColor = isLight ? '#e5e7eb' : '#333333';

            Chart.defaults.color = chartColor;
            Chart.defaults.font.family = "'Share Tech Mono', monospace";
            Chart.defaults.font.weight = 'bold';

            const d = window.AdminData.chartData;
            const compDataRaw = d.completionData;
            const compData = (compDataRaw[0] === 0 && compDataRaw[1] === 0) ? [0, 1] : compDataRaw;

            const l_accuracy = currentLang === 'en' ? ['Correct', 'Incorrect'] : ['Betul', 'Salah'];
            const l_dist = currentLang === 'en' ? ['Needs Training', 'Average', 'Elite', 'Perfect'] : ['Perlu Latihan', 'Sederhana', 'Elit', 'Sempurna'];
            const l_comp = currentLang === 'en' ? ['Secured', 'Standby'] : ['Selesai', 'Sedia'];

            const hoverCursor = (event, chartElement) => { event.native.target.style.cursor = chartElement[0] ? 'pointer' : 'default'; };

            const ctxTop = document.getElementById('topAgentsChart');
            if (ctxTop) {
                chartInstances.top = new Chart(ctxTop.getContext('2d'), {
                    type: 'bar',
                    data: {
                        labels: d.topLabels.length > 0 ? d.topLabels : ['No Data'],
                        datasets: [{
                            label: 'Points', data: d.topData.length > 0 ? d.topData : [0],
                            backgroundColor: purpleBarColor, borderRadius: 2
                        }]
                    },
                    options: { 
                        responsive: true, maintainAspectRatio: false, onHover: hoverCursor,
                        onClick: (e, elements) => {
                            if (!elements.length || d.topLabels.length === 0 || d.topLabels[0] === 'No Data') return;
                            openChartModal('TOP OPERATIVE: ' + d.topLabels[elements[0].index], 'SCORE', d.topDetails[elements[0].index], 'standard');
                        },
                        scales: { y: { grid: { color: gridColor } }, x: { grid: { display: false } } }, plugins: { legend: { display: false } } 
                    }
                });
            }

            const ctxAcc = document.getElementById('accuracyChart');
            if (ctxAcc) {
                chartInstances.acc = new Chart(ctxAcc.getContext('2d'), {
                    type: 'bar',
                    data: {
                        labels: l_accuracy,
                        datasets: [{
                            data: d.accuracyData,
                            backgroundColor: [successColor, dangerColor], borderRadius: 2
                        }]
                    },
                    options: { 
                        responsive: true, maintainAspectRatio: false, onHover: hoverCursor,
                        onClick: (e, elements) => {
                            if (!elements.length) return;
                            const idx = elements[0].index;
                            if (idx === 0) {
                                openChartModal('ACCURACY: CORRECT', 'TOTAL CORRECT', d.chartDetailsData['accuracy_correct'], 'standard');
                            } else {
                                openChartModal('ACCURACY: INCORRECT', 'TOTAL INCORRECT', d.chartDetailsData['accuracy_incorrect'], 'incorrect');
                            }
                        },
                        scales: { y: { grid: { color: gridColor } }, x: { grid: { display: false } } }, plugins: { legend: { display: false } } 
                    }
                });
            }

            const ctxDist = document.getElementById('distributionChart');
            if (ctxDist) {
                chartInstances.dist = new Chart(ctxDist.getContext('2d'), {
                    type: 'line',
                    data: {
                        labels: l_dist,
                        datasets: [{
                            label: 'Agents', data: d.distributionData,
                            backgroundColor: gridColor, borderColor: purpleBarColor,
                            borderWidth: 3, pointBackgroundColor: purpleBarColor, fill: true, tension: 0.1
                        }]
                    },
                    options: { 
                        responsive: true, maintainAspectRatio: false, onHover: hoverCursor,
                        onClick: (e, elements) => {
                            if (!elements.length) return;
                            const idx = elements[0].index;
                            openChartModal('TIER: ' + l_dist[idx], 'SCORE', d.chartDetailsData['dist_' + idx], 'standard');
                        },
                        scales: { y: { grid: { color: gridColor }, beginAtZero: true, ticks: { stepSize: 1 } }, x: { grid: { display: false } } }, plugins: { legend: { display: false } } 
                    }
                });
            }

            const ctxComp = document.getElementById('completionChart');
            if (ctxComp) {
                chartInstances.comp = new Chart(ctxComp.getContext('2d'), {
                    type: 'doughnut',
                    data: {
                        labels: l_comp,
                        datasets: [{
                            data: compData,
                            backgroundColor: [purpleBarColor, standbyColor],
                            borderColor: isLight ? '#ffffff' : '#000000', borderWidth: 2
                    }]
                },
                options: { 
                    responsive: true, maintainAspectRatio: false, cutout: '70%', onHover: hoverCursor,
                    onClick: (e, elements) => {
                        if (!elements.length) return;
                        const idx = elements[0].index;
                        openChartModal('STATUS: ' + l_comp[idx], 'STATUS', d.chartDetailsData[idx === 0 ? 'comp_secured' : 'comp_standby'], 'standard');
                    },
                    plugins: { legend: { position: 'bottom', labels: { color: Chart.defaults.color } } } 
                }
            });
        }
    }

    // --- 🚨 UPGRADED MODAL UTILITIES ---
    function openChartModal(title, colMark, dataArray, viewMode = 'standard') {
        document.getElementById('chartModalTitle').textContent = title;
        
        const thead = document.getElementById('chartModalHead');
        const tbody = document.getElementById('chartModalBody');
        
        tbody.innerHTML = '';
        thead.innerHTML = '';
        
        if (viewMode === 'incorrect') {
            thead.innerHTML = `
                <tr class="text-xs md:text-sm uppercase tracking-widest font-bold">
                    <th class="py-4 px-6 text-dynamic-main" data-en="AGENT NAME" data-ms="NAMA EJEN">AGENT NAME</th>
                    <th class="py-4 px-6 text-dynamic-main">INCORRECT ANSWER(S)</th>
                    <th class="py-4 px-6 text-right text-dynamic-main">${colMark}</th>
                </tr>
            `;
        } else {
            thead.innerHTML = `
                <tr class="text-xs md:text-sm uppercase tracking-widest font-bold">
                    <th class="py-4 px-6 text-dynamic-main" data-en="AGENT NAME" data-ms="NAMA EJEN">AGENT NAME</th>
                    <th class="py-4 px-6 text-center text-dynamic-main" data-en="CORRECT" data-ms="BETUL">CORRECT</th>
                    <th class="py-4 px-6 text-center text-dynamic-main" data-en="INCORRECT" data-ms="SALAH">INCORRECT</th>
                    <th class="py-4 px-6 text-right text-dynamic-main">${colMark}</th>
                </tr>
            `;
        }
        
        if (!dataArray || dataArray.length === 0) {
            const noDataText = currentLang === 'en' ? 'No records found in this category.' : 'Tiada rekod ditemui dalam kategori ini.';
            tbody.innerHTML = `<tr><td colspan="4" class="py-8 px-6 text-center text-dynamic-main opacity-50 italic text-sm">${noDataText}</td></tr>`;
        } else {
            dataArray.forEach((row, index) => {
                let hiddenClass = index >= 10 ? 'hidden extra-row' : '';
                
                if (viewMode === 'incorrect') {
                    let wrongAnswersHtml = '';
                    if (row.wrong_list && row.wrong_list.length > 0) {
                        wrongAnswersHtml = row.wrong_list.join('<br><br>'); 
                    } else {
                        wrongAnswersHtml = '<span class="italic opacity-50 text-xs">Detailed logs not found in database</span>';
                    }
                    
                    tbody.innerHTML += `
                        <tr class="${hiddenClass}" style="border-bottom: 1px solid var(--border-color);">
                            <td class="py-5 px-6 text-dynamic-main text-lg md:text-xl font-bold align-top">
                                ${row.name}
                                <span class="block text-xs font-normal opacity-60 mt-1">${row.staff_id}</span>
                            </td>
                            <td class="py-5 px-6 text-dynamic-danger text-sm md:text-base font-mono leading-relaxed align-top">${wrongAnswersHtml}</td>
                            <td class="py-5 px-6 text-right text-dynamic-title text-2xl md:text-3xl font-black align-top">${row.score}</td>
                        </tr>
                    `;
                } else {
                    let correctVal = row.correct !== undefined ? row.correct : '-';
                    let incorrectVal = row.incorrect !== undefined ? row.incorrect : '-';

                    tbody.innerHTML += `
                        <tr class="${hiddenClass}" style="border-bottom: 1px solid var(--border-color);">
                            <td class="py-5 px-6 text-dynamic-main text-lg md:text-xl font-bold">
                                ${row.name}
                                <span class="block text-xs font-normal opacity-60 mt-1">${row.staff_id}</span>
                            </td>
                            <td class="py-5 px-6 text-center text-dynamic-success text-2xl font-black">${correctVal}</td>
                            <td class="py-5 px-6 text-center text-dynamic-danger text-2xl font-black">${incorrectVal}</td>
                            <td class="py-5 px-6 text-right text-dynamic-title text-2xl md:text-3xl font-black">${row.score}</td>
                        </tr>
                    `;
                }
            });

            if (dataArray.length > 10) {
                const viewAllText = currentLang === 'en' ? 'View All' : 'Lihat Semua';
                tbody.innerHTML += `
                    <tr id="expand-row">
                        <td colspan="4" class="py-6 px-6 text-center">
                            <button type="button" onclick="expandChartList()" class="inline-flex items-center gap-2 px-6 py-4 text-xs font-bold uppercase tracking-widest rounded border-2 text-dynamic-title hover:bg-purple-600 hover:text-white transition" style="border-color: var(--border-color);">
                                <span>${viewAllText} (${dataArray.length})</span>
                            </button>
                        </td>
                    </tr>
                `;
            }
        }
        
        const modal = document.getElementById('chartModal');
        modal.classList.remove('hidden'); 
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden'; 
    }

    function expandChartList() {
        const tbody = document.getElementById('chartModalBody');
        tbody.querySelectorAll('.extra-row').forEach(row => row.classList.remove('hidden'));
        const expandRow = document.getElementById('expand-row');
        if (expandRow) expandRow.remove();
    }

    function closeChartModal() {
        const modal = document.getElementById('chartModal');
        modal.classList.add('hidden'); 
        modal.classList.remove('flex');
        document.body.style.overflow = ''; 
    }
</script>
@include('partials.cursor')
</body>
</html>