<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PhishingEmail;
use App\Models\GameProgress;
use App\Models\MiniGame;

class AgentController extends Controller
{
    // 1. Load the Main Mission Hub
    public function missionHub()
    {
        $progress = GameProgress::firstOrCreate(['user_id' => auth()->id() ?? 1]);
        return view('mission', compact('progress'));
    }

    // --- LEVEL 1: THE PHISHING NET ---
    public function playLevelOne()
    {
        $emails = PhishingEmail::inRandomOrder()->get();
        return view('agent-level1', compact('emails'));
    }

    public function completeLevelOne(\Illuminate\Http\Request $request)
    {
        $progress = \App\Models\GameProgress::firstOrCreate(['user_id' => auth()->id()]);
        if ($progress->level_1_completed) return redirect()->route('agent.mission');
        
        $level1Score = (int) $request->query('score', 0);
        $correct = (int) $request->query('correct', 0);
        $incorrect = (int) $request->query('incorrect', 0);

        $wrongAnswers = $request->input('wrong_answers', []);
        if (is_string($wrongAnswers)) $wrongAnswers = json_decode($wrongAnswers, true) ?? [];

        $customTotal = is_array($progress->custom_room_scores) ? array_sum($progress->custom_room_scores) : 0;
        
        $newTotal = $level1Score + $progress->level_2_score + $progress->level_3_score + $progress->level_4_score + $progress->level_5_score + $progress->level_6_score + $customTotal;

        $progress->update([
            'level_1_completed' => true, 'level_1_score' => $level1Score, 'level_1_correct' => $correct, 'level_1_incorrect' => $incorrect,
            'level_1_wrong_answers' => $wrongAnswers, 'total_score' => $newTotal
        ]);

        return redirect()->route('agent.mission');
    }

    // --- LEVEL 2: THE BRUTE FORCE GATE ---
    public function playLevelTwo()
    {
        $questions = \App\Models\LevelTwoQuestion::inRandomOrder()->get();
        return view('agent-level2', compact('questions'));
    }

    public function completeLevelTwo(\Illuminate\Http\Request $request)
    {
        $progress = \App\Models\GameProgress::firstOrCreate(['user_id' => auth()->id()]);
        if ($progress->level_2_completed) return redirect()->route('agent.mission');
        
        $level2Score = (int) $request->query('score', 0);
        $correct = (int) $request->query('correct', 0);
        $incorrect = (int) $request->query('incorrect', 0);

        $wrongAnswers = $request->input('wrong_answers', []);
        if (is_string($wrongAnswers)) $wrongAnswers = json_decode($wrongAnswers, true) ?? [];

        $customTotal = is_array($progress->custom_room_scores) ? array_sum($progress->custom_room_scores) : 0;
        
        $newTotal = $progress->level_1_score + $level2Score + $progress->level_3_score + $progress->level_4_score + $progress->level_5_score + $progress->level_6_score + $customTotal;

        $progress->update([
            'level_2_completed' => true, 'level_2_score' => $level2Score, 'level_2_correct' => $correct, 'level_2_incorrect' => $incorrect,
            'level_2_wrong_answers' => $wrongAnswers, 'total_score' => $newTotal
        ]);

        return redirect()->route('agent.mission');
    }

    // --- LEVEL 3: THE HUMAN FIREWALL ---
    public function playLevelThree()
    {
        $scenarios = \App\Models\LevelThreeScenario::inRandomOrder()->get();
        return view('agent-level3', compact('scenarios'));
    }

    public function completeLevelThree(\Illuminate\Http\Request $request)
    {
        $progress = \App\Models\GameProgress::firstOrCreate(['user_id' => auth()->id()]);
        if ($progress->level_3_completed) return redirect()->route('agent.mission');
        
        $level3Score = (int) $request->query('score', 0);
        $correct = (int) $request->query('correct', 0);
        $incorrect = (int) $request->query('incorrect', 0);

        $wrongAnswers = $request->input('wrong_answers', []);
        if (is_string($wrongAnswers)) $wrongAnswers = json_decode($wrongAnswers, true) ?? [];

        $customTotal = is_array($progress->custom_room_scores) ? array_sum($progress->custom_room_scores) : 0;
        
        $newTotal = $progress->level_1_score + $progress->level_2_score + $level3Score + $progress->level_4_score + $progress->level_5_score + $progress->level_6_score + $customTotal;

        $progress->update([
            'level_3_completed' => true, 'level_3_score' => $level3Score, 'level_3_correct' => $correct, 'level_3_incorrect' => $incorrect,
            'level_3_wrong_answers' => $wrongAnswers, 'total_score' => $newTotal
        ]);

        return redirect()->route('agent.mission');
    }

    // --- LEVEL 4: THE MIRROR WEB ---
    public function playLevelFour()
    {
        $scenarios = \App\Models\LevelFourScenario::inRandomOrder()->get();
        return view('agent-level4', compact('scenarios'));
    }

    public function completeLevelFour(\Illuminate\Http\Request $request)
    {
        $progress = \App\Models\GameProgress::firstOrCreate(['user_id' => auth()->id()]);
        if ($progress->level_4_completed) return redirect()->route('agent.mission');
        
        $level4Score = (int) $request->query('score', 0);
        $correct = (int) $request->query('correct', 0);
        $incorrect = (int) $request->query('incorrect', 0);

        $wrongAnswers = $request->input('wrong_answers', []);
        if (is_string($wrongAnswers)) $wrongAnswers = json_decode($wrongAnswers, true) ?? [];

        $customTotal = is_array($progress->custom_room_scores) ? array_sum($progress->custom_room_scores) : 0;
        
        $newTotal = $progress->level_1_score + $progress->level_2_score + $progress->level_3_score + $level4Score + $progress->level_5_score + $progress->level_6_score + $customTotal;

        $progress->update([
            'level_4_completed' => true, 'level_4_score' => $level4Score, 'level_4_correct' => $correct, 'level_4_incorrect' => $incorrect,
            'level_4_wrong_answers' => $wrongAnswers, 'total_score' => $newTotal
        ]);

        return redirect()->route('agent.mission');
    }

    // --- LEVEL 5: S.H.I.E.L.D MAINFRAME (FINAL BOSS) ---
    public function playLevelFive()
    {
        $questions = \App\Models\LevelFiveQuestion::inRandomOrder()->get();
        $settings = \App\Models\MainframeSetting::first();
        $progress = \App\Models\GameProgress::where('user_id', auth()->id())->first();
        
        return view('agent-level5', compact('questions', 'settings', 'progress'));
    }

    public function completeLevelFive(\Illuminate\Http\Request $request)
    {
        $progress = \App\Models\GameProgress::firstOrCreate(['user_id' => auth()->id()]);
        if ($progress->level_5_completed) return redirect()->route('agent.certificate', ['room' => 5]); 
        
        $level5Score = (int) $request->query('score', 0);
        $correct = (int) $request->query('correct', 0);
        $incorrect = (int) $request->query('incorrect', 0);

        $wrongAnswers = $request->input('wrong_answers', []);
        if (is_string($wrongAnswers)) $wrongAnswers = json_decode($wrongAnswers, true) ?? [];

        $customTotal = is_array($progress->custom_room_scores) ? array_sum($progress->custom_room_scores) : 0;
        
        $newTotal = $progress->level_1_score + $progress->level_2_score + $progress->level_3_score + $progress->level_4_score + $level5Score + $progress->level_6_score + $customTotal;

        $progress->update([
            'level_5_completed' => true, 'level_5_score' => $level5Score, 'level_5_correct' => $correct, 'level_5_incorrect' => $incorrect,
            'level_5_wrong_answers' => $wrongAnswers, 'total_score' => $newTotal
        ]);

        return redirect()->route('agent.certificate', ['room' => 5]); 
    }

    // --- DYNAMIC CERTIFICATE GENERATOR ---
    public function viewCertificate(Request $request)
    {
        $user = auth()->user(); 
        $userScore = \App\Models\GameProgress::where('user_id', auth()->id())->first();
        if (!$userScore) $userScore = (object) ['total_score' => 0, 'created_at' => now(), 'updated_at' => now()]; 

        // 🔥 FIX: Check if this is a Custom Branching Room!
        if ($request->has('custom_room')) {
            $roomId = $request->query('custom_room');
            
            // 1. Fetch the room first so we know the Admin's passing mark
            $customRoom = \App\Models\Room::find($roomId);
            
            if (!$customRoom) {
                return redirect()->route('agent.branching-hub')->with('error', 'Module not found.');
            }

            // 2. Security Check: Use the dynamic pass_mark from the database
            $dynamicPassMark = $customRoom->pass_mark ?? 0; 
            
            if (!$userScore instanceof \App\Models\GameProgress || !$userScore->hasPassedCustomRoom($roomId, $dynamicPassMark)) {
                return redirect()->route('agent.branching-hub')->with('error', 'ACCESS DENIED: Minimum marks not achieved to print this certificate.');
            }
            
            $roomName = ($request->query('lang') === 'en' && $customRoom->title_en) ? $customRoom->title_en : $customRoom->title;
            $roomTitle = 'LZNK CAKNA SIBER ESCAPE ROOM : BRANCHING ROOM (' . mb_strtoupper($roomName) . ')';
        } else {
            // It's a standard Core Module
            $roomId = $request->query('room');
            $roomTitle = 'LZNK CAKNA SIBER ESCAPE ROOM : S.H.I.E.L.D';

            switch ($roomId) {
                case 1: $roomTitle .= ' (ROOM 1 : THE PHISHING NET)'; break;
                case 2: $roomTitle .= ' (ROOM 2 : THE BRUTE FORCE GATE)'; break;
                case 3: $roomTitle .= ' (ROOM 3 : THE HUMAN FIREWALL)'; break;
                case 4: $roomTitle .= ' (ROOM 4 : THE MIRROR WEB)'; break;
                case 5: $roomTitle .= ' (ROOM 5 : S.H.I.E.L.D MAINFRAME)'; break;
            }
        }

        return view('certificate', compact('userScore', 'roomTitle', 'user'));
    }

    // --- LEVEL 6: MINI-GAME ARCADE ---
    public function arcadeHub()
    {
        $miniGames = MiniGame::where('is_active', true)->orderBy('sort_order')->get();
        $progress = \App\Models\GameProgress::firstOrCreate(['user_id' => auth()->id() ?? 1]);
        return view('agent-arcade-hub', compact('miniGames', 'progress'));
    }

    public function playMiniGame($id)
    {
        $game = MiniGame::findOrFail($id);
        $progress = \App\Models\GameProgress::firstOrCreate(['user_id' => auth()->id() ?? 1]);
        return view('minigames.' . $game->game_type, compact('game', 'progress'));
    }

    public function completeMiniGame(\Illuminate\Http\Request $request, $id)
    {
        $game = MiniGame::findOrFail($id);
        $progress = \App\Models\GameProgress::firstOrCreate(['user_id' => auth()->id() ?? 1]);

        $completedGames = is_string($progress->completed_minigames) ? json_decode($progress->completed_minigames, true) : ($progress->completed_minigames ?? []);
        if (is_array($completedGames) && in_array($id, $completedGames)) {
            return redirect()->route('agent.arcade')->with('success', 'MODULE ALREADY CLEARED! NO ADDITIONAL PTS AWARDED.');
        }

        $earnedScore = (int) $request->input('score', 0);
        if ($earnedScore > $game->base_score) $earnedScore = $game->base_score;

        $earnedCorrect = (int) $request->input('correct', 0);
        $earnedIncorrect = (int) $request->input('incorrect', 0);
        
        $newWrongAnswers = $request->input('wrong_answers', []);
        if (is_string($newWrongAnswers)) $newWrongAnswers = json_decode($newWrongAnswers, true) ?? [];
        
        $arcadeStats = is_string($progress->level_6_wrong_answers) ? json_decode($progress->level_6_wrong_answers, true) : ($progress->level_6_wrong_answers ?? []);
        if (!is_array($arcadeStats)) $arcadeStats = [];
        
        $arcadeStats[$id] = ['correct' => $earnedCorrect, 'incorrect' => $earnedIncorrect, 'logs' => $newWrongAnswers];

        $newLevel6Score = $progress->level_6_score + $earnedScore;
        $completedGames[] = $id;
        
        $customTotal = is_array($progress->custom_room_scores) ? array_sum($progress->custom_room_scores) : 0;

        $newTotal = $progress->level_1_score + $progress->level_2_score + $progress->level_3_score + $progress->level_4_score + $progress->level_5_score + $newLevel6Score + $customTotal;

        $progress->update([
            'level_6_score' => $newLevel6Score, 'level_6_correct' => $progress->level_6_correct + $earnedCorrect, 'level_6_incorrect' => $progress->level_6_incorrect + $earnedIncorrect,
            'level_6_wrong_answers' => $arcadeStats, 'total_score' => $newTotal, 'completed_minigames' => $completedGames 
        ]);

        return redirect()->route('agent.arcade')->with('success', 'MODULE CLEARED! ' . $earnedScore . ' PTS SECURED.');
    }

    public function arcadeCertificate($id)
    {
        $game = MiniGame::findOrFail($id);
        $user = auth()->user(); 
        $progress = \App\Models\GameProgress::where('user_id', auth()->id())->first();
        
        $completedGamesArray = is_string($progress->completed_minigames) ? json_decode($progress->completed_minigames, true) : ($progress->completed_minigames ?? []);
        if (!is_array($completedGamesArray) || !in_array($id, $completedGamesArray)) return redirect()->route('agent.arcade')->with('error', 'ACCESS DENIED: Module not completed.');

        $userScore = clone $progress;
        $userScore->total_score = $game->base_score; 
        $roomTitle = "LZNK CAKNA SIBER ESCAPE ROOM : S.H.I.E.L.D (ARCADE : " . strtoupper($game->title) . ")";

        return view('certificate', compact('userScore', 'roomTitle', 'user'));
    }

    // ==============================================
    // 🔥 CUSTOM BRANCHING ENGINE CORE 🔥
    // ==============================================
    // ==============================================
    // 🔥 CUSTOM BRANCHING ENGINE CORE 🔥
    // ==============================================
    public function branchingHub()
    {
        $customRooms = \App\Models\Room::where('is_active', 1)->get();
        
        // 1. Dapatkan rekod pemain terkini
        $progress = \App\Models\GameProgress::firstOrCreate(['user_id' => auth()->id() ?? 1]);
        
        // 2. Ekstrak markah Branching Game
        $customScores = is_string($progress->custom_room_scores) 
            ? json_decode($progress->custom_room_scores, true) 
            : ($progress->custom_room_scores ?? []);

        // 3. Hantar data ke Frontend!
        return view('branching-hub', compact('customRooms', 'customScores'));
    }

    public function playRoom($id)
    {
        $room = \App\Models\Room::with(['questions.options'])->findOrFail($id);
        $question = $room->questions->where('level', 1)->first() ?? $room->questions->first();

        if (!$question) return redirect()->route('agent.branching-hub')->with('error', 'Modul ini belum mempunyai sebarang soalan.');

        // 🔥 RESET SCORE & HISTORY BILA MULA MAIN 🔥
        session(['room_' . $room->id . '_score' => 0]);
        session(['room_' . $room->id . '_history' => []]); 
        
        $isFirst = true;

        return view('play_room', compact('room', 'question', 'isFirst'));
    }

    public function submitRoomAnswer(\Illuminate\Http\Request $request, $id)
    {
        $request->validate(['option_id' => 'required|exists:options,id']);
        // Load option with its parent question so we can record the question text
        $option = \App\Models\Option::with('question')->findOrFail($request->option_id);

        $currentScore = session('room_' . $id . '_score', 0);
        session(['room_' . $id . '_score' => $currentScore + $option->points]);

        // 🔥 RECORD STEP-BY-STEP HISTORY (UPDATED WITH is_correct) 🔥
        $currentHistory = session('room_' . $id . '_history', []);
        $currentHistory[] = [
            'question' => $option->question->text ?? 'Unknown Question',
            'answer' => $option->text,
            'points' => $option->points,
            'is_correct' => $option->points > 0 // This tells the UI to make it Green or Red!
        ];
        session(['room_' . $id . '_history' => $currentHistory]);

        // Flash feedback to display on the next screen
        if (!empty($option->feedback)) {
            session()->flash('mission_feedback', $option->feedback);
            // If points are 0 or less, we flag it as a wrong answer for the red UI!
            session()->flash('feedback_is_wrong', $option->points <= 0); 
        }

        if ($option->next_question_id) {
            return redirect()->route('agent.play.room.question', ['room' => $id, 'question' => $option->next_question_id]);
        } else {
            return redirect()->route('agent.play.room.complete', $id);
        }
    }

    public function playRoomQuestion($roomId, $questionId)
    {
        $room = \App\Models\Room::findOrFail($roomId);
        $question = \App\Models\Question::with('options')->findOrFail($questionId);
        
        $isFirst = false; 
        return view('play_room', compact('room', 'question', 'isFirst'));
    }

    // 🚨 THIS SAVES THE SCORE AND HISTORY PERMANENTLY TO THE DATABASE
    public function completeRoom($roomId)
    {
        $room = \App\Models\Room::findOrFail($roomId);
        $finalScore = session('room_' . $roomId . '_score', 0);
        $finalHistory = session('room_' . $roomId . '_history', []); // Grab history from session
        
        $progress = \App\Models\GameProgress::firstOrCreate(['user_id' => auth()->id() ?? 1]);

        // Retrieve existing dictionaries
        $customScores = is_string($progress->custom_room_scores) ? json_decode($progress->custom_room_scores, true) : ($progress->custom_room_scores ?? []);
        $customHistory = is_string($progress->custom_room_history) ? json_decode($progress->custom_room_history, true) : ($progress->custom_room_history ?? []);
        
        // Save score AND history if it's their highest for this specific room
        if (!isset($customScores[$room->id]) || $finalScore >= $customScores[$room->id]) {
            $customScores[$room->id] = $finalScore;
            $customHistory[$room->id] = $finalHistory; // Save new history!
        }

        // Calculate True Global Total
        $coreTotal = $progress->level_1_score + $progress->level_2_score + $progress->level_3_score + $progress->level_4_score + $progress->level_5_score + $progress->level_6_score;
        $customTotal = array_sum($customScores);

        $progress->update([
            'custom_room_scores' => $customScores,
            'custom_room_history' => $customHistory, // Push to DB!
            'total_score' => $coreTotal + $customTotal
        ]);

        // 🔥 PASS THE HISTORY TO THE VIEW AS $reportData 🔥
        $reportData = $finalHistory;
        
        return view('play_complete', compact('room', 'finalScore', 'reportData'));
    }
    // 1. Tunjuk senarai poster kepada Agent
    public function intelGallery()
    {
        // Hanya tarik poster yang 'active'
        $galleries = \App\Models\InfoGallery::where('is_active', true)->latest()->get();
        return view('agent.gallery-index', compact('galleries'));
    }

    // 2. Tunjuk poster saiz penuh berserta maklumat
    public function intelGalleryShow($id)
    {
        $gallery = \App\Models\InfoGallery::findOrFail($id);
        return view('agent.gallery-show', compact('gallery'));
    }
    
public function logFailure(Request $request)
    {
        // Make sure the request has a room_number
        $request->validate([
            'room_number' => 'required|integer'
        ]);

        \App\Models\FailedAttempt::create([
            'user_id' => auth()->id(),
            'room_number' => $request->room_number
        ]);

        return response()->json(['status' => 'logged']);
    }

}