<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Room;
use App\Models\Question;
use App\Models\Option;

class GameBuilderController extends Controller
{
    // ==========================================
    // 1. ROOM MANAGEMENT (The Modules)
    // ==========================================
    public function index()
    {
        // --- EXACT MAX SCORE MATH FOR CORE MODULES ONLY ---
        $maxScore = 0;
        if (class_exists(\App\Models\PhishingEmail::class)) $maxScore += \App\Models\PhishingEmail::count() * 100;
        if (class_exists(\App\Models\LevelTwoQuestion::class)) $maxScore += \App\Models\LevelTwoQuestion::count() * 100;
        if (class_exists(\App\Models\LevelThreeScenario::class)) $maxScore += \App\Models\LevelThreeScenario::count() * 100;
        if (class_exists(\App\Models\LevelFourScenario::class)) $maxScore += \App\Models\LevelFourScenario::count() * 100;

        if (class_exists(\App\Models\LevelFiveQuestion::class)) {
            $level5Count = \App\Models\LevelFiveQuestion::count();
            $level5Base = $level5Count * 100;
            $mainframeSetting = \App\Models\MainframeSetting::first();
            $bonusPercentage = $mainframeSetting ? $mainframeSetting->bonus_streak_percentage : 2;
            $maxLevel5Bonus = 100 * ($bonusPercentage / 100) * (($level5Count * ($level5Count - 1)) / 2);
            $maxScore += ($level5Base + $maxLevel5Bonus);
        }

        if (class_exists(\App\Models\MiniGame::class)) {
            $maxScore += \App\Models\MiniGame::where('is_active', true)->sum('base_score');
        }

        // 1. High-Level Stats
        $totalStaff = \App\Models\User::count();
        $allProgress = \App\Models\GameProgress::with('user')->has('user')->get();

        $processedStaff = $allProgress->map(function ($progress) {
            $rooms_completed = 0;
            $breakdown = [];

            // 🔥 HANYA BASE ROOMS SAHAJA (CORE 1-5)
            $rooms = [
                ['title' => 'The Phishing Net', 'score' => $progress->level_1_score, 'status' => $progress->level_1_completed],
                ['title' => 'The Brute Force Gate', 'score' => $progress->level_2_score, 'status' => $progress->level_2_completed],
                ['title' => 'The Human Firewall', 'score' => $progress->level_3_score, 'status' => $progress->level_3_completed],
                ['title' => 'The Mirror Web', 'score' => $progress->level_4_score, 'status' => $progress->level_4_completed],
                ['title' => 'S.H.I.E.L.D Mainframe', 'score' => $progress->level_5_score, 'status' => $progress->level_5_completed]
            ];

            foreach ($rooms as $rm) {
                if ($rm['status']) {
                    $rooms_completed++;
                    $breakdown[] = ['room_title' => $rm['title'], 'score' => $rm['score']];
                } else {
                    $breakdown[] = ['room_title' => $rm['title'], 'score' => null];
                }
            }

            // Room 6 (Arcade)
            if ($progress->level_6_completed) {
                $rooms_completed++;
            }
            $breakdown[] = ['room_title' => 'Arcade Modules', 'score' => $progress->level_6_score];

            // 🔥 HANYA KIRA MARKAH LEVEL 1-6 (Abaikan Branching Game)
            $trueTotalScore = $progress->level_1_score + $progress->level_2_score + $progress->level_3_score + $progress->level_4_score + $progress->level_5_score + $progress->level_6_score;

            return (object) [
                'id' => $progress->user?->id ?? 'N/A',
                'name' => $progress->user?->name ?? 'Deleted User',
                'email' => $progress->user?->email ?? 'N/A',
                'staff_id' => $progress->user?->agent_id ?? 'N/A', 
                'department' => 'Agent', 
                'is_suspended' => $progress->user?->is_suspended ?? false, 
                
                'calculated_score' => $trueTotalScore,
                'rooms_completed' => $rooms_completed,
                'breakdown' => $breakdown
            ];
        })->sortByDesc('calculated_score')->values();

        $highestScore = $processedStaff->max('calculated_score') ?? 0;
        $averageScore = $processedStaff->count() > 0 ? round($processedStaff->avg('calculated_score')) : 0;
        
        $totalRooms = 6; // 5 Core + 1 Arcade
        $completedStaff = $processedStaff->where('rooms_completed', $totalRooms)->count();

        // 2. Chart 1: Overall Score Distribution
        $distribution = [0, 0, 0, 0, 0];
        $distributionDetails = [[], [], [], [], []];
        
        foreach ($processedStaff as $staff) {
            $score = $staff->calculated_score;
            $staffInfo = ['name' => $staff->name, 'staff_id' => $staff->staff_id, 'score' => $score];
            
            if ($score <= 500) { $distribution[0]++; $distributionDetails[0][] = $staffInfo; }
            elseif ($score <= 1500) { $distribution[1]++; $distributionDetails[1][] = $staffInfo; }
            elseif ($score <= 3000) { $distribution[2]++; $distributionDetails[2][] = $staffInfo; }
            elseif ($score <= 4500) { $distribution[3]++; $distributionDetails[3][] = $staffInfo; }
            else { $distribution[4]++; $distributionDetails[4][] = $staffInfo; }
        }

        // 3. Charts 2, 3, & 4: Room Specific Stats (CORE ONLY)
        $roomLabels = ['The Phishing Net', 'The Brute Force Gate', 'The Human Firewall', 'The Mirror Web', 'S.H.I.E.L.D Mainframe', 'Arcade Modules'];
        
        $roomProgress = [];
        $roomAverages = [];
        $roomTotals = [];

        foreach ($roomLabels as $idx => $label) {
            $playedCount = $processedStaff->whereNotNull("breakdown.{$idx}.score")->count();
            $roomProgress[] = $totalStaff > 0 ? round(($playedCount / $totalStaff) * 100) : 0;
            $roomAverages[] = round($processedStaff->avg("breakdown.{$idx}.score") ?? 0);
            $roomTotals[] = $processedStaff->sum("breakdown.{$idx}.score");
        }

        $roomDetails = [];
        foreach($roomLabels as $idx => $label) {
            $roomDetails[$label] = [];
            foreach($processedStaff as $staff) {
                if($staff->breakdown[$idx]['score'] !== null) {
                    $roomDetails[$label][] = ['name' => $staff->name, 'staff_id' => $staff->staff_id, 'score' => $staff->breakdown[$idx]['score']];
                }
            }
        }

        $overallDetails = $processedStaff->map(function($s) {
            return ['name' => $s->name, 'staff_id' => $s->staff_id, 'score' => $s->calculated_score];
        })->toArray();

        // 4. Pagination
        $currentPage = request()->get('page', 1);
        $perPage = 10; // Kekalkan 10 untuk Dashboard Director
        $currentItems = $processedStaff->forPage($currentPage, $perPage)->values();
        $paginatedStaff = new \Illuminate\Pagination\LengthAwarePaginator(
            $currentItems, 
            $processedStaff->count(), 
            $perPage, 
            $currentPage, 
            ['path' => request()->url(), 'query' => request()->query()]
        );

        return view('admin', compact(
            'totalStaff', 'highestScore', 'averageScore', 'completedStaff', 'totalRooms',
            'distribution', 'distributionDetails',
            'roomLabels', 'roomProgress', 'roomAverages', 'roomTotals', 'roomDetails',
            'overallDetails', 'paginatedStaff', 'maxScore'
        ));
    }

    public function modules()
    {
        // Fetch all rooms/modules to display on the list
        $rooms = \App\Models\Room::orderBy('id', 'desc')->get();
        return view('admin.builder.modules', compact('rooms'));
    }

    public function storeRoom(\Illuminate\Http\Request $request)
    {
        // 1. Validate all the incoming data
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'pass_mark' => 'required|integer|min:0|max:100',
            'video_upload' => 'nullable|file|mimes:mp4,m4v|max:51200', // Max 50MB video
        ]);

        // 2. Create the new Room
        $room = new \App\Models\Room();
        $room->title = $request->title;
        $room->description = $request->description;
        $room->pass_mark = $request->pass_mark;
        $room->is_active = $request->has('is_active') ? 1 : 0;
        
        // Save optional video settings
        if($request->has('video_url')) $room->video_url = $request->video_url;
        if($request->has('video_description')) $room->video_description = $request->video_description;
        if($request->has('video_duration')) $room->video_duration = $request->video_duration;

        // 3. Securely handle MP4 Video Uploads
        if ($request->hasFile('video_upload')) {
            $path = $request->file('video_upload')->store('videos', 'public');
            $room->video_path = $path; 
        }

        $room->save();

        // 4. Redirect back to the Module List with a success message!
        return redirect()->route('admin.builder.modules')->with('success', 'Modul baru berjaya dicipta!');
    }

    // ==========================================
    // 2. SCENARIO BUILDER (The Nodes Map)
    // ==========================================
    public function buildRoom(Room $room)
    {
        // Fetch all questions (nodes) for this room, along with their options (choices)
        $questions = $room->questions()->with('options')->get();
        
        // This will load the visual node-mapping UI
        return view('admin.builder.show', compact('room', 'questions'));
    }

    public function createNode(\App\Models\Room $room)
    {
        // Get other questions to populate the branching dropdowns
        $otherQuestions = \App\Models\Question::where('room_id', $room->id)->get();
        
        return view('admin.builder.create_node', compact('room', 'otherQuestions'));
    }

    public function storeNode(\Illuminate\Http\Request $request, \App\Models\Room $room)
    {
        // 1. Validate the core question
        $request->validate([
            'text' => 'required|string',
            'level' => 'nullable|integer',
        ]);

        // 2. Save the Question
        $question = $room->questions()->create([
            'text' => $request->text,
            'level' => $request->level ?? 1,
        ]);

        // 3. Save all the dynamically added Options (if any exist)
        if ($request->has('options') && is_array($request->options)) {
            foreach ($request->options as $opt) {
                // Only save if they actually typed an answer
                if (!empty($opt['text'])) {
                    $question->options()->create([
                        'text' => $opt['text'],
                        'points' => $opt['points'] ?? 0,
                        'next_question_id' => $opt['next_question_id'] ?: null,
                        'feedback' => $opt['feedback'] ?? null,
                    ]);
                }
            }
        }

        return redirect()->route('admin.builder.show', $room->id)->with('success', 'Soalan dan jawapan berjaya ditambah!');
    }

    public function destroyNode(Question $question)
    {
        $question->delete(); // This will also cascade and delete associated options
        return back()->with('success', 'Node deleted successfully.');
    }

    public function editNode($id)
    {
        $question = \App\Models\Question::with('options')->findOrFail($id);
        $room = \App\Models\Room::findOrFail($question->room_id);
        
        // Get other questions for the branching dropdown
        $otherQuestions = \App\Models\Question::where('room_id', $room->id)
                            ->where('id', '!=', $question->id)
                            ->get();

        return view('admin.builder.edit_node', compact('room', 'question', 'otherQuestions'));
    }

    public function updateNode(\Illuminate\Http\Request $request, $id)
    {
        $question = \App\Models\Question::findOrFail($id);

        $request->validate([
            'text' => 'required|string',
            'level' => 'required|integer|min:1',
            'video_upload' => 'nullable|file|mimes:mp4,m4v|max:51200',
            'options' => 'required|array|min:1',
        ]);

        // Update Question Core Data
        $question->text = $request->text;
        $question->level = $request->level;

        // Update Video Data if provided
        if($request->has('video_url')) $question->video_url = $request->video_url;
        if($request->has('video_duration')) $question->video_duration = $request->video_duration;

        if ($request->hasFile('video_upload')) {
            $path = $request->file('video_upload')->store('videos', 'public');
            $question->video_path = $path;
        }

        $question->save();

        // 3. Smart Sync Answers (Options)
        $existingOptionIds = $question->options->pluck('id')->toArray();
        $submittedOptionIds = [];

        foreach ($request->options as $optData) {
            if (isset($optData['id']) && in_array($optData['id'], $existingOptionIds)) {
                // Update existing option
                $option = \App\Models\Option::find($optData['id']);
                $submittedOptionIds[] = $option->id;
            } else {
                // Create brand new option
                $option = new \App\Models\Option();
                $option->question_id = $question->id;
            }
            
            $option->text = $optData['text'];
            $option->points = $optData['points'] ?? 0;
            $option->next_question_id = $optData['next_question_id'] ?: null;
            $option->feedback = $optData['feedback'] ?? null;
            $option->save();
            
            if(!isset($optData['id'])) {
                $submittedOptionIds[] = $option->id; 
            }
        }

        // Delete any options that the Admin clicked "Remove" on
        $optionsToDelete = array_diff($existingOptionIds, $submittedOptionIds);
        \App\Models\Option::whereIn('id', $optionsToDelete)->delete();

        return redirect()->route('admin.builder.show', $question->room_id)->with('success', 'Soalan dan jawapan berjaya dikemaskini!');
    }

    // ==========================================
    // 3. CHOICES & BRANCHING LOGIC (The Options)
    // ==========================================
    public function storeChoice(Request $request, Question $question)
    {
        $request->validate([
            'text' => 'required|string',
            'points' => 'required|integer'
        ]);

        $question->options()->create($request->all());
        return back()->with('success', 'New Choice added to Node!');
    }

    public function linkChoice(Request $request, Option $option)
    {
        // 🚨 THIS IS THE MAGIC BRANCHING FUNCTION! 🚨
        // It connects a specific button (Option) to the next screen (Question)
        $option->update([
            'next_question_id' => $request->next_question_id
        ]);

        return back()->with('success', 'Branching path linked successfully!');
    }

    public function destroyChoice(Option $option)
    {
        $option->delete();
        return back()->with('success', 'Choice deleted.');
    }

    public function toggleSuspend(\App\Models\User $user)
    {
        // Elakkan admin dari sekat diri sendiri
        if (auth()->id() === $user->id) {
            return response()->json(['error' => 'Anda tidak boleh menyekat akaun anda sendiri!'], 403);
        }

        // Note: Make sure your User migration actually has an 'is_suspended' column!
        // If it doesn't, you will need to add it: $table->boolean('is_suspended')->default(false);
        $user->is_suspended = !$user->is_suspended;
        $user->save();

        return response()->json(['success' => true]);
    }

    // FUNGSI: PADAM STAF SEPENUHNYA
    public function destroyUser(\App\Models\User $user)
    {
        // Elakkan admin dari padam diri sendiri
        if (auth()->id() === $user->id) {
            return response()->json(['error' => 'Anda tidak boleh memadam akaun anda sendiri!'], 403);
        }

        // Padam rekod markah staf ni dulu supaya tak ralat (Foreign Key Constraint)
        \App\Models\UserScore::where('user_id', $user->id)->delete();
        
        // Padam staf
        $user->delete();

        return response()->json(['success' => true]);
    }

    // FUNGSI BAHARU: Reset Markah Staf
    public function resetScore(\App\Models\User $user)
    {
        // Padam semua rekod markah staf ini dari jadual UserScore
        \App\Models\UserScore::where('user_id', $user->id)->delete();
        
        return back()->with('success', 'Rekod markah bagi staf ' . $user->name . ' telah berjaya dikosongkan.');
    }

    public function visualMap(\App\Models\Room $room)
    {
        $questions = $room->questions()->with('options')->get();

        $mermaidMs = "graph TD\n";
        $mermaidEn = "graph TD\n";

        if ($questions->isEmpty()) {
            $mermaidMs .= "    Empty[\"Belum ada soalan dicipta\"]\n";
            $mermaidEn .= "    Empty[\"No questions created yet\"]\n";
        } else {
            $linkIndexMs = 0;
            $linkIndexEn = 0;

            foreach ($questions as $q) {
                // 1. SANITIZE TEXT
                $spacedTextMs = str_replace(['<br>', '<br/>', '</p>', '</div>', '</li>'], ' ', $q->text);
                $rawTextMs = trim(preg_replace('/\s+/', ' ', strip_tags($spacedTextMs)));
                $cleanTextMs = str_replace(['"', '(', ')', '[', ']', '{', '}', '|', '\\', '`'], "", $rawTextMs);
                
                $spacedTextEn = str_replace(['<br>', '<br/>', '</p>', '</div>', '</li>'], ' ', $q->text_en ?? $q->text);
                $rawTextEn = trim(preg_replace('/\s+/', ' ', strip_tags($spacedTextEn)));
                $cleanTextEn = str_replace(['"', '(', ')', '[', ']', '{', '}', '|', '\\', '`'], "", $rawTextEn);

                // Wordwrap the Question Node (Sharp Box [])
                $wrappedTextMs = wordwrap($cleanTextMs, 50, "<br/>");
                $wrappedTextEn = wordwrap($cleanTextEn, 50, "<br/>");

                $mermaidMs .= "    Q{$q->id}[\"<b>ID: {$q->id}</b><br/><br/>{$wrappedTextMs}\"]\n";
                $mermaidEn .= "    Q{$q->id}[\"<b>ID: {$q->id}</b><br/><br/>{$wrappedTextEn}\"]\n";

                $maxPts = $q->options->max('points');

                // 2. CREATE OPTION NODES AND LINKS
                foreach ($q->options as $opt) {
                    $spacedOptMs = str_replace(['<br>', '<br/>', '</p>', '</div>'], ' ', $opt->text);
                    $rawOptMs = trim(preg_replace('/\s+/', ' ', strip_tags($spacedOptMs)));
                    $optTextMs = str_replace(['"', '(', ')', '[', ']', '{', '}', '|', '\\', '`'], "", $rawOptMs);
                    
                    $spacedOptEn = str_replace(['<br>', '<br/>', '</p>', '</div>'], ' ', $opt->text_en ?? $opt->text);
                    $rawOptEn = trim(preg_replace('/\s+/', ' ', strip_tags($spacedOptEn)));
                    $optTextEn = str_replace(['"', '(', ')', '[', ']', '{', '}', '|', '\\', '`'], "", $rawOptEn);
                    
                    // Wordwrap the Option Box
                    $wrappedOptMs = wordwrap($optTextMs, 35, "<br/>");
                    $wrappedOptEn = wordwrap($optTextEn, 35, "<br/>");

                    $pts = $opt->points > 0 ? "+{$opt->points}" : $opt->points;
                    
                    // Define IDs
                    $optNodeId = "Opt{$opt->id}";
                    $targetNode = $opt->next_question_id ? "Q{$opt->next_question_id}" : "End{$opt->id}([\"TAMAT / END\"])";

                    // Create the Option as a Rounded Node ()
                    $mermaidMs .= "    {$optNodeId}(\"<b>{$wrappedOptMs}</b><br/><br/>({$pts} pts)\")\n";
                    $mermaidEn .= "    {$optNodeId}(\"<b>{$wrappedOptEn}</b><br/><br/>({$pts} pts)\")\n";

                    // Draw the Path: Question --> Option Node --> Next Question
                    $mermaidMs .= "    Q{$q->id} --> {$optNodeId}\n";
                    $mermaidMs .= "    {$optNodeId} --> {$targetNode}\n";
                    $mermaidEn .= "    Q{$q->id} --> {$optNodeId}\n";
                    $mermaidEn .= "    {$optNodeId} --> {$targetNode}\n";

                    // 3. APPLY STYLES (Box colors and Arrow colors)
                    if ($maxPts !== null && $opt->points === $maxPts) {
                        // Green Correct Box
                        $mermaidMs .= "    style {$optNodeId} stroke:#10b981,color:#10b981,fill:transparent,stroke-width:2px\n";
                        $mermaidEn .= "    style {$optNodeId} stroke:#10b981,color:#10b981,fill:transparent,stroke-width:2px\n";
                        
                        // Green Lines (Both top and bottom arrows)
                        $mermaidMs .= "    linkStyle {$linkIndexMs} stroke:#10b981,stroke-width:2px\n"; $linkIndexMs++;
                        $mermaidMs .= "    linkStyle {$linkIndexMs} stroke:#10b981,stroke-width:2px\n"; $linkIndexMs++;
                        
                        $mermaidEn .= "    linkStyle {$linkIndexEn} stroke:#10b981,stroke-width:2px\n"; $linkIndexEn++;
                        $mermaidEn .= "    linkStyle {$linkIndexEn} stroke:#10b981,stroke-width:2px\n"; $linkIndexEn++;
                    } else {
                        // Red Wrong Box (Dashed borders)
                        $mermaidMs .= "    style {$optNodeId} stroke:#ef4444,color:#ef4444,fill:transparent,stroke-width:2px,stroke-dasharray: 5 5\n";
                        $mermaidEn .= "    style {$optNodeId} stroke:#ef4444,color:#ef4444,fill:transparent,stroke-width:2px,stroke-dasharray: 5 5\n";
                        
                        // Red Lines (Both top and bottom arrows)
                        $mermaidMs .= "    linkStyle {$linkIndexMs} stroke:#ef4444,stroke-width:2px,stroke-dasharray: 5 5\n"; $linkIndexMs++;
                        $mermaidMs .= "    linkStyle {$linkIndexMs} stroke:#ef4444,stroke-width:2px,stroke-dasharray: 5 5\n"; $linkIndexMs++;
                        
                        $mermaidEn .= "    linkStyle {$linkIndexEn} stroke:#ef4444,stroke-width:2px,stroke-dasharray: 5 5\n"; $linkIndexEn++;
                        $mermaidEn .= "    linkStyle {$linkIndexEn} stroke:#ef4444,stroke-width:2px,stroke-dasharray: 5 5\n"; $linkIndexEn++;
                    }
                }
            }
        }

        return view('admin.builder.map', compact('room', 'mermaidMs', 'mermaidEn'));
    }

    public function createChoice(\App\Models\Question $question)
    {
        // Get the room this question belongs to
        $room = \App\Models\Room::findOrFail($question->room_id);
        
        // Get all questions in this room so we can populate the "Pautkan Ke (Branching)" dropdown
        $otherQuestions = \App\Models\Question::where('room_id', $room->id)->get();

        return view('admin.builder.create_choice', compact('room', 'question', 'otherQuestions'));
    }

// Buka Halaman Kemaskini Modul
    public function editRoom(\App\Models\Room $room)
    {
        return view('admin.builder.edit_module', compact('room'));
    }

    // Proses Kemaskini Modul
    public function updateRoom(\Illuminate\Http\Request $request, \App\Models\Room $room)
    {
        // Validasi asas
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'pass_mark' => 'required|integer|min:0|max:100',
        ]);

        // Simpan data
        $room->title = $request->title;
        $room->description = $request->description;
        $room->pass_mark = $request->pass_mark;
        $room->is_active = $request->has('is_active') ? 1 : 0;
        
        // Simpan data video jika wujud di jadual (database) anda
        if($request->has('video_url')) $room->video_url = $request->video_url;
        if($request->has('video_description')) $room->video_description = $request->video_description;
        if($request->has('video_duration')) $room->video_duration = $request->video_duration;

        $room->save();

        return redirect()->route('admin.builder.modules')->with('success', 'Modul berjaya dikemaskini!');
    }

    // Padam Modul
    public function destroyRoom(\App\Models\Room $room)
    {
        $room->delete();
        return back()->with('success', 'Modul berserta semua soalan berjaya dipadam!');
    }

    // Buka Halaman Cipta Modul Baru
    public function createRoom()
    {
        return view('admin.builder.create_room');
    }

}