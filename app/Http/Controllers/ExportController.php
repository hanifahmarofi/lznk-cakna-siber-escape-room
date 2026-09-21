<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Room;
use App\Models\UserScore; 
use App\Models\GameProgress; 
use Illuminate\Http\Request;

class ExportController extends Controller
{
    public function index()
    {
        return view('admin.builder.export_hub'); 
    }

    public function summary()
    {
        $fileName = 'Ringkasan_Markah_Staf_LZNK_' . date('Ymd_His') . '.csv';
        $users = User::all();
        $rooms = Room::where('is_active', true)->get();

        $handle = fopen('php://temp', 'w+');
        fputs($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));

        $headers = ['No.', 'Nama Staf', 'ID Staf', 'Jabatan'];
        foreach ($rooms as $room) {
            $headers[] = 'Modul: ' . $room->title; 
        }
        $headers[] = 'Jumlah Mata Keseluruhan';
        $headers[] = 'Jumlah Purata Markah';
        fputcsv($handle, $headers);

        $count = 1;
        $totalRooms = $rooms->count();

        foreach ($users as $user) {
            $coreScores = UserScore::where('user_id', $user->id)->get();
            $progress = GameProgress::where('user_id', $user->id)->first();
            $customScoresArray = [];
            
            if ($progress) {
                $customScoresArray = is_string($progress->custom_room_scores) 
                    ? json_decode($progress->custom_room_scores, true) 
                    : ($progress->custom_room_scores ?? []);
            }

            $totalScore = 0;
            
            // 🔥 FIX: Checks both agent_id AND staff_id dynamically!
            $staffId = !empty($user->agent_id) ? $user->agent_id : (!empty($user->staff_id) ? $user->staff_id : '-');
            $department = !empty($user->department) ? $user->department : '-';

            $rowData = [
                $count++,
                $user->name ?? 'Tiada Nama',
                $staffId, 
                $department
            ];

            foreach ($rooms as $room) {
                $roomScore = $coreScores->firstWhere('room_id', $room->id);
                if ($roomScore) {
                    $points = $roomScore->score;
                } else {
                    $points = $customScoresArray[$room->id] ?? 0;
                }
                $rowData[] = $points;
                $totalScore += $points;
            }

            $averageScore = $totalRooms > 0 ? round($totalScore / $totalRooms) : 0;
            $rowData[] = $totalScore;
            $rowData[] = $averageScore;

            fputcsv($handle, $rowData);
        }

        rewind($handle);
        $content = stream_get_contents($handle);
        fclose($handle);

        return response($content)
            ->header('Content-Type', 'text/csv; charset=UTF-8')
            ->header('Content-Disposition', 'attachment; filename="' . $fileName . '"')
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }

    public function detailed()
    {
        $fileName = 'Laporan_Terperinci_Siber_LZNK_' . date('Ymd_His') . '.csv';
        $users = User::all();
        $rooms = Room::all()->keyBy('id');

        $handle = fopen('php://temp', 'w+');
        fputs($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));
        fputcsv($handle, ['No.', 'Nama Staf', 'ID Staf', 'Jabatan', 'Bilik/Modul', 'Soalan', 'Jawapan Dipilih', 'Status', 'Mata Diberikan']);

        $index = 1;
        foreach ($users as $user) {
            $coreScores = UserScore::with('room')->where('user_id', $user->id)->get();
            $progress = GameProgress::where('user_id', $user->id)->first();
            
            $customScoresArray = [];
            $customHistoryArray = []; 

            if ($progress) {
                $customScoresArray = is_string($progress->custom_room_scores) ? json_decode($progress->custom_room_scores, true) : ($progress->custom_room_scores ?? []);
                $customHistoryArray = is_string($progress->custom_room_history) ? json_decode($progress->custom_room_history, true) : ($progress->custom_room_history ?? []);
            }

            // 🔥 FIX: Checks both agent_id AND staff_id
            $staffId = !empty($user->agent_id) ? $user->agent_id : (!empty($user->staff_id) ? $user->staff_id : '-');
            $department = !empty($user->department) ? $user->department : '-';

            if ($coreScores->isEmpty() && empty($customScoresArray)) {
                fputcsv($handle, [
                    $index++, $user->name, $staffId, $department,
                    'BELUM MULA', '-', '-', '-', 0
                ]);
                continue;
            }

            // PROSES 1: CORE MODULES
            foreach ($coreScores as $score) {
                $roomTitle = $score->room->title ?? 'Bilik Dipadam';
                if (!empty($score->history) && is_array($score->history)) {
                    foreach ($score->history as $historyItem) {
                        $questionText = $historyItem['question'] ?? '-';
                        $answerText = $historyItem['answer'] ?? '-';
                        $points = $historyItem['points'] ?? 0;

                        if ($points > 0) { $status = 'BETUL'; } 
                        elseif ($points < 0) { $status = 'SALAH (PENALTI)'; } 
                        else { $status = 'NEUTRAL'; }

                        fputcsv($handle, [
                            $index++, $user->name, $staffId, $department,
                            $roomTitle, $questionText, $answerText, $status, $points
                        ]);
                    }
                } else {
                    fputcsv($handle, [
                        $index++, $user->name, $staffId, $department,
                        $roomTitle, 'TIADA REKOD SOALAN', '-', '-', $score->score ?? 0
                    ]);
                }
            }

            // PROSES 2: BRANCHING GAMES (CUSTOM ROOMS)
            if (!empty($customScoresArray)) {
                foreach ($customScoresArray as $roomId => $scoreVal) {
                    if ($coreScores->contains('room_id', $roomId)) continue;
                    $roomTitle = isset($rooms[$roomId]) ? $rooms[$roomId]->title : 'Modul Cabang (' . $roomId . ')';
                    
                    if (isset($customHistoryArray[$roomId]) && is_array($customHistoryArray[$roomId]) && count($customHistoryArray[$roomId]) > 0) {
                        foreach ($customHistoryArray[$roomId] as $historyItem) {
                            $questionText = $historyItem['question'] ?? '-';
                            $answerText = $historyItem['answer'] ?? '-';
                            $points = $historyItem['points'] ?? 0;

                            if ($points > 0) { $status = 'BETUL'; } 
                            elseif ($points < 0) { $status = 'SALAH (PENALTI)'; } 
                            else { $status = 'NEUTRAL'; }

                            fputcsv($handle, [
                                $index++, $user->name, $staffId, $department,
                                $roomTitle, $questionText, $answerText, $status, $points
                            ]);
                        }
                    } else {
                        fputcsv($handle, [
                            $index++, $user->name, $staffId, $department,
                            $roomTitle, 'KEPUTUSAN KESELURUHAN (MODUL CABANG)', '-', 'SELESAI', $scoreVal
                        ]);
                    }
                }
            }
        }

        rewind($handle);
        $content = stream_get_contents($handle);
        fclose($handle);

        return response($content)
            ->header('Content-Type', 'text/csv; charset=UTF-8')
            ->header('Content-Disposition', 'attachment; filename="' . $fileName . '"')
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }

    public function charts()
    {
        $chartData = [];
        
        try {
            $rooms = Room::all();
            $users = User::all()->keyBy('id'); 
            $gameProgresses = GameProgress::all()->keyBy('user_id'); 

            foreach($rooms as $room) {
                $scores = UserScore::where('room_id', $room->id)->get();
                $questionsMap = []; 
                
                foreach($scores as $score) {
                    if (!empty($score->history) && is_array($score->history)) {
                        foreach ($score->history as $idx => $item) {
                            $qLabel = 'Q' . ($idx + 1);
                            $fullQuestion = $item['question'] ?? 'Soalan tidak diketahui';
                            $pts = $item['points'] ?? 0;
                            $userAnswer = $item['answer'] ?? '-'; 
                            
                            $u = $users->get($score->user_id);
                            if(!$u) continue;

                            // 🔥 FIX: Checks both agent_id AND staff_id dynamically!
                            $staffId = !empty($u->agent_id) ? $u->agent_id : (!empty($u->staff_id) ? $u->staff_id : '-');

                            $userData = [
                                'name' => $u->name,
                                'staff_id' => $staffId,
                                'answer' => $userAnswer, 
                            ];
                            
                            if (!isset($questionsMap[$qLabel])) {
                                $questionsMap[$qLabel] = [
                                    'label' => $qLabel,
                                    'full_text' => $fullQuestion,
                                    'correct' => [],
                                    'wrong' => []
                                ];
                            }
                            
                            if ($pts > 0) {
                                $questionsMap[$qLabel]['correct'][] = $userData;
                            } else {
                                $questionsMap[$qLabel]['wrong'][] = $userData;
                            }
                        }
                    }
                }
                
                if (empty($questionsMap)) {
                    foreach ($gameProgresses as $userId => $progress) {
                        
                        $historyArray = is_string($progress->custom_room_history) 
                            ? json_decode($progress->custom_room_history, true) 
                            : ($progress->custom_room_history ?? []);

                        if (isset($historyArray[$room->id]) && is_array($historyArray[$room->id])) {
                            
                            foreach ($historyArray[$room->id] as $idx => $item) {
                                $qLabel = 'Q' . ($idx + 1);
                                $fullQuestion = $item['question'] ?? 'Soalan tidak diketahui';
                                $pts = $item['points'] ?? 0;
                                $userAnswer = $item['answer'] ?? '-'; 

                                $u = $users->get($userId);
                                if(!$u) continue;

                                // 🔥 FIX: Checks both agent_id AND staff_id dynamically!
                                $staffId = !empty($u->agent_id) ? $u->agent_id : (!empty($u->staff_id) ? $u->staff_id : '-');

                                $userData = [
                                    'name' => $u->name,
                                    'staff_id' => $staffId,
                                    'answer' => $userAnswer, 
                                ];

                                if (!isset($questionsMap[$qLabel])) {
                                    $questionsMap[$qLabel] = [
                                        'label' => $qLabel,
                                        'full_text' => $fullQuestion,
                                        'correct' => [],
                                        'wrong' => []
                                    ];
                                }

                                if ($pts > 0) {
                                    $questionsMap[$qLabel]['correct'][] = $userData;
                                } else {
                                    $questionsMap[$qLabel]['wrong'][] = $userData;
                                }
                            }
                        }
                    }
                }
                
                if (count($questionsMap) > 0) {
                    $labels = []; $fullTexts = []; $correctCounts = []; $wrongCounts = [];
                    $correctContribs = []; $wrongContribs = [];
                    
                    foreach ($questionsMap as $qLabel => $data) {
                        $labels[] = $data['label'];
                        $fullTexts[] = $data['full_text'];
                        $correctCounts[] = count($data['correct']);
                        $wrongCounts[] = count($data['wrong']);
                        $correctContribs[] = $data['correct'];
                        $wrongContribs[] = $data['wrong'];
                    }
                    
                    if (array_sum($correctCounts) > 0 || array_sum($wrongCounts) > 0) {
                        $chartData[] = [
                            'title' => $room->title,
                            'labels' => $labels,
                            'full_texts' => $fullTexts,
                            'correct_counts' => $correctCounts,
                            'wrong_counts' => $wrongCounts,
                            'correct_contributors' => $correctContribs,
                            'wrong_contributors' => $wrongContribs,
                        ];
                    }
                }
            }
        } catch (\Exception $e) {
            // Abaikan jika ralat pangkalan data
        }

        return view('admin.export-charts', compact('chartData'));
    }
}