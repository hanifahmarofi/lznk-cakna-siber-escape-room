<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Room;
use App\Models\UserScore;
use App\Models\GameProgress;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    // ==========================================
    // 1. DASHBOARD UTAMA
    // ==========================================
    public function index()
    {
        $users = User::all();
        $customRooms = Room::where('is_active', true)->get();
        $totalRooms = $customRooms->count();
        $maxScore = $customRooms->sum('pass_mark');

        // Tarik data dari jadual GameProgress (Markah Sebenar)
        $allProgress = GameProgress::whereNotNull('user_id')->get()->keyBy('user_id');

        $staffRanking = $users->map(function ($user) use ($customRooms, $allProgress) {
            $progress = $allProgress->get($user->id);
            
            // Ekstrak markah dari JSON
            $customScores = [];
            if ($progress) {
                $customScores = is_string($progress->custom_room_scores) 
                    ? json_decode($progress->custom_room_scores, true) 
                    : ($progress->custom_room_scores ?? []);
            }

            // Tarik jejak audit/history dari jadual UserScore
            $userScoresDb = UserScore::where('user_id', $user->id)->get()->keyBy('room_id');

            $rooms_completed = 0;
            $breakdown = [];
            $calculated_score = 0;

            foreach ($customRooms as $cRoom) {
                $score = null;
                $history = [];
                
                // Jika staf ada markah dalam JSON GameProgress
                if (isset($customScores[$cRoom->id])) {
                    $rooms_completed++;
                    $score = $customScores[$cRoom->id];
                    $calculated_score += $score;
                }

                // Jika staf ada sejarah jawapan
                if ($userScoresDb->has($cRoom->id)) {
                    $history = $userScoresDb->get($cRoom->id)->history ?? [];
                }

                $breakdown[] = [
                    'room_title' => $cRoom->title,
                    'score' => $score,
                    'history' => $history
                ];
            }

            return (object) [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'staff_id' => $user->agent_id ?? ($user->staff_id ?? 'TIADA'),
                'department' => $user->department ?? 'AGENT',
                'is_suspended' => $user->is_suspended ?? false,
                
                'calculated_score' => $calculated_score,
                'rooms_completed' => $rooms_completed,
                'breakdown' => $breakdown
            ];
        })->sortByDesc('calculated_score')->values();

        $totalStaff = $staffRanking->count();
        $highestScore = $staffRanking->max('calculated_score') ?? 0;
        
        $activeUsers = $staffRanking->where('calculated_score', '>', 0);
        $averageScore = $activeUsers->count() > 0 ? round($activeUsers->avg('calculated_score')) : 0;
        $completedStaff = $staffRanking->where('rooms_completed', '>=', $totalRooms)->where('rooms_completed', '>', 0)->count();

        // Chart 1: Distribution
        $distribution = [0, 0, 0, 0, 0];
        $distributionDetails = [0 => [], 1 => [], 2 => [], 3 => [], 4 => []]; 
        $overallDetails = []; 

        foreach ($activeUsers as $staff) {
            $score = $staff->calculated_score;
            $uData = ['name' => $staff->name, 'staff_id' => $staff->staff_id, 'score' => $score];
            $overallDetails[] = $uData;

            if ($score <= 20) { $distribution[0]++; $distributionDetails[0][] = $uData; }
            elseif ($score <= 40) { $distribution[1]++; $distributionDetails[1][] = $uData; }
            elseif ($score <= 60) { $distribution[2]++; $distributionDetails[2][] = $uData; }
            elseif ($score <= 80) { $distribution[3]++; $distributionDetails[3][] = $uData; }
            else { $distribution[4]++; $distributionDetails[4][] = $uData; }
        }
        usort($overallDetails, function($a, $b) { return $b['score'] <=> $a['score']; });

        // Room Specific Stats
        $roomLabels = [];
        $roomProgress = [];
        $roomAverages = [];
        $roomTotals = [];
        $roomDetails = [];

        foreach ($customRooms as $idx => $room) {
            $roomLabels[] = $room->title;
            
            $playedCount = $staffRanking->whereNotNull("breakdown.{$idx}.score")->count();
            $roomProgress[] = $totalStaff > 0 ? round(($playedCount / $totalStaff) * 100) : 0;
            
            $roomAvg = $staffRanking->whereNotNull("breakdown.{$idx}.score")->avg("breakdown.{$idx}.score");
            $roomAverages[] = $roomAvg ? round($roomAvg) : 0;
            
            $roomTotals[] = $staffRanking->sum("breakdown.{$idx}.score");

            $rDetails = [];
            foreach($staffRanking as $staff) {
                if($staff->breakdown[$idx]['score'] !== null) {
                    $rDetails[] = [
                        'name' => $staff->name, 
                        'staff_id' => $staff->staff_id, 
                        'score' => $staff->breakdown[$idx]['score']
                    ];
                }
            }
            usort($rDetails, function($a, $b) { return $b['score'] <=> $a['score']; });
            $roomDetails[$room->title] = $rDetails;
        }

        // Pagination Selamat
        $currentPage = request()->get('page', 1); 
        $perPage = 5; 
        $currentPageItems = $staffRanking->forPage($currentPage, $perPage)->values();

        $paginatedStaff = new \Illuminate\Pagination\LengthAwarePaginator(
            $currentPageItems, 
            $staffRanking->count(), 
            $perPage, 
            $currentPage, 
            ['path' => request()->url(), 'query' => request()->query()]
        );

        $bucketLabels = ['0-20', '21-40', '41-60', '61-80', '81+'];

        return view('admin.dashboard', compact(
            'totalStaff', 'highestScore', 'averageScore', 'completedStaff', 
            'paginatedStaff', 'totalRooms', 'distribution', 'roomLabels', 'roomProgress',
            'roomAverages', 'roomTotals', 
            'distributionDetails', 'roomDetails', 'overallDetails', 'maxScore', 'bucketLabels'
        ));
    }

    // ==========================================
    // 2. KAWALAN PENGGUNA (AJAX JSON / RESET)
    // ==========================================
    
    // 🔥 FUNGSI RESET DIPERBAIKI: Reset Keseluruhan Game (Core, Arcade, Branching, Failed Attempts)
    public function resetScore(Request $request, \App\Models\User $user)
    {
        // 1. Padam rekod sejarah bilik bercabang
        \App\Models\UserScore::where('user_id', $user->id)->delete();
        
        // 2. Padam log kegagalan untuk bilik 1-5 (Jika modul FailedAttempt wujud)
        if (class_exists(\App\Models\FailedAttempt::class)) {
            \App\Models\FailedAttempt::where('user_id', $user->id)->delete();
        }

        // 3. Reset keseluruhan markah di dalam jadual utama GameProgress
        $progress = \App\Models\GameProgress::where('user_id', $user->id)->first();
        
        if ($progress) {
            $progress->update([
                // Reset Core Scores (Room 1-5)
                'level_1_score' => 0, 'level_1_correct' => 0, 'level_1_incorrect' => 0, 'level_1_completed' => false, 'level_1_wrong_answers' => null,
                'level_2_score' => 0, 'level_2_correct' => 0, 'level_2_incorrect' => 0, 'level_2_completed' => false, 'level_2_wrong_answers' => null,
                'level_3_score' => 0, 'level_3_correct' => 0, 'level_3_incorrect' => 0, 'level_3_completed' => false, 'level_3_wrong_answers' => null,
                'level_4_score' => 0, 'level_4_correct' => 0, 'level_4_incorrect' => 0, 'level_4_completed' => false, 'level_4_wrong_answers' => null,
                'level_5_score' => 0, 'level_5_correct' => 0, 'level_5_incorrect' => 0, 'level_5_completed' => false, 'level_5_wrong_answers' => null,
                
                // Reset Arcade Score (Room 6)
                'level_6_score' => 0, 'level_6_completed' => false, 'completed_minigames' => null, 'level_6_wrong_answers' => null,
                
                // Reset Master Score
                'total_score' => 0,
                
                // Reset Custom Branching Rooms
                'custom_room_scores' => null,
            ]);
        }
        
        return back()->with('success', 'Semua rekod markah teras dan modul bercabang bagi staf ' . $user->name . ' telah berjaya dikosongkan.');
    }

    public function toggleSuspend(\App\Models\User $user)
    {
        try {
            if (auth()->id() == $user->id) {
                return back()->with('error', 'Anda tidak boleh menyekat akaun anda sendiri!');
            }

            $user->is_suspended = !$user->is_suspended;
            $user->save();

            $statusText = $user->is_suspended ? 'SUSPENDED' : 'RESTORED';
            
            // FIX: Return a redirect back with a session flash message instead of JSON
            return back()->with('success', "SYSTEM UPDATE: Agent {$user->name} access {$statusText}.");
            
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function destroyUser(\App\Models\User $user)
    {
        try {
            if (auth()->id() == $user->id) {
                return back()->with('error', 'Anda tidak boleh memadam akaun anda sendiri!');
            }

            $name = $user->name;

            // Padam semua rekod yang berkaitan dengan user ini
            UserScore::where('user_id', $user->id)->delete();
            GameProgress::where('user_id', $user->id)->delete();
            if (class_exists(\App\Models\FailedAttempt::class)) {
                \App\Models\FailedAttempt::where('user_id', $user->id)->delete();
            }

            $user->delete();

            // FIX: Return a redirect back with a session flash message instead of JSON
            return back()->with('success', "SYSTEM UPDATE: Agent {$name} has been permanently terminated.");
            
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    // ==========================================
    // 3. FUNGSI ANALYTICS & EXPORT
    // ==========================================
    public function analytics($room = 1)
    {
        $room = (int) $room;
        if (!in_array($room, [1, 2, 3, 4, 5, 6])) $room = 1;

        if ($room === 6) {
            $miniGames = \App\Models\MiniGame::orderBy('sort_order')->get();
            $allProgress = GameProgress::with('user')->has('user')->whereNotNull('completed_minigames')->get();
            $totalStaff = User::count();
            return view('admin-analytics', compact('room', 'miniGames', 'allProgress', 'totalStaff'));
        }

        $performances = GameProgress::with('user')->has('user')->orderByDesc("level_{$room}_score")->paginate(10);
        return view('admin-analytics', compact('performances', 'room'));
    }

    public function exportLogs()
    {
        $allProgress = GameProgress::with('user')->has('user')->get();
        $customRooms = Room::where('is_active', 1)->get();
        
        $filename = "shield_agent_logs_" . date('Y-m-d_H-i-s') . ".csv";
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = [
            'Agent ID', 'Agent Name', 'Status', 
            'Room 1 (Phishing Net)', 'Room 2 (Brute Force)', 'Room 3 (Human Firewall)', 
            'Room 4 (Mirror Web)', 'Room 5 (Mainframe)', 'Room 6 (Arcade Modules)'
        ];

        foreach ($customRooms as $cRoom) $columns[] = 'Custom: ' . $cRoom->title;
        $columns[] = 'True Total Score';

        $callback = function() use($allProgress, $columns, $customRooms) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($allProgress as $progress) {
                $status = 'Active';
                if ($progress->user && $progress->user->is_suspended) $status = 'Suspended';
                elseif ($progress->level_5_completed) $status = 'Cleared';

                $customScores = is_string($progress->custom_room_scores) ? json_decode($progress->custom_room_scores, true) : ($progress->custom_room_scores ?? []);
                
                $customTotal = 0;
                $dynamicScores = [];
                foreach ($customRooms as $cRoom) {
                    $score = $customScores[$cRoom->id] ?? 0;
                    $dynamicScores[] = $score;
                    $customTotal += $score;
                }

                $trueTotal = ($progress->level_1_score ?? 0) + ($progress->level_2_score ?? 0) + ($progress->level_3_score ?? 0) + 
                             ($progress->level_4_score ?? 0) + ($progress->level_5_score ?? 0) + ($progress->level_6_score ?? 0) + $customTotal;

                $row = [
                    $progress->user->agent_id ?? 'N/A', $progress->user->name ?? 'Deleted User', $status,
                    $progress->level_1_score ?? 0, $progress->level_2_score ?? 0, $progress->level_3_score ?? 0,
                    $progress->level_4_score ?? 0, $progress->level_5_score ?? 0, $progress->level_6_score ?? 0,
                ];

                $row = array_merge($row, $dynamicScores);
                $row[] = $trueTotal;
                fputcsv($file, $row);
            }
            fclose($file);
        };
        return response()->stream($callback, 200, $headers);
    }
    
    public function failureAnalytics()
    {
        // Group by User and Room, and count the total failures
        $failedStats = \App\Models\FailedAttempt::with('user')
            ->select('user_id', 'room_number', \Illuminate\Support\Facades\DB::raw('count(*) as total_fails'))
            ->groupBy('user_id', 'room_number')
            ->orderBy('total_fails', 'desc')
            ->get();

        return view('admin.analytics', compact('failedStats'));
    }
}