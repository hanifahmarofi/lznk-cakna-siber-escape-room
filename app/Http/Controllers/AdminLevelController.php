<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PhishingEmail;
use Illuminate\Support\Facades\Storage;
use App\Models\MiniGame;

class AdminLevelController extends Controller
{
    // Display the Level 1 Configuration Page
    public function showLevelOne()
    {
        // Fetch all existing emails to display them in a table/list
        $emails = PhishingEmail::all();
        return view('admin-level1', compact('emails'));
    }

    // Save a new email from the form
    public function storeLevelOne(Request $request)
    {
        $request->validate([
            'sender_name_or_address' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'body' => 'required|string',
        ]);

        PhishingEmail::create([
            'sender_name_or_address' => $request->sender_name_or_address,
            'subject' => $request->subject,
            'body' => $request->body,
            // Checkbox value will be 'on' if checked, null if not
            'is_phishing' => $request->has('is_phishing') ? true : false, 
        ]);

        return redirect()->route('admin.level1')->with('success', 'Email Template Added Successfully!');
    }

    // Update an existing email payload
    public function updateLevelOne(Request $request, $id)
    {
        $request->validate([
            'sender_name_or_address' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'body' => 'required|string',
        ]);

        $email = PhishingEmail::findOrFail($id);
        
        $email->update([
            'sender_name_or_address' => $request->sender_name_or_address,
            'subject' => $request->subject,
            'body' => $request->body,
            // Checkbox value will be true if checked, false if not
            'is_phishing' => $request->has('is_phishing') ? true : false, 
        ]);

        return redirect()->route('admin.level1')->with('success', 'Email Template Updated Successfully!');
    }

    // Delete an email payload
    public function destroyLevelOne($id)
    {
        $email = PhishingEmail::findOrFail($id);
        $email->delete();

        return redirect()->back()->with('success', 'Payload Purged Successfully!');
    }

    // --- LEVEL 2: THE BRUTE FORCE GATE --- //

    public function showLevelTwo()
    {
        $questions = \App\Models\LevelTwoQuestion::all();
        return view('admin-level2', compact('questions'));
    }

    public function storeLevelTwo(Request $request)
    {
        $request->validate([
            'category' => 'nullable|string|max:255',
            'question' => 'required|string',
            'option_1' => 'required|string|max:255',
            'option_2' => 'required|string|max:255',
            'option_3' => 'required|string|max:255',
            'option_4' => 'required|string|max:255',
            'correct_answer' => 'required|integer|in:1,2,3,4',
        ]);

        \App\Models\LevelTwoQuestion::create($request->all());

        return redirect()->route('admin.level2')->with('success', 'Objective Question Added Successfully!');
    }

    public function destroyLevelTwo($id)
    {
        $question = \App\Models\LevelTwoQuestion::findOrFail($id);
        $question->delete();

        return redirect()->back()->with('success', 'Question Purged Successfully!');
    }

    // Update an existing Level 2 question
    public function updateLevelTwo(Request $request, $id)
    {
        $request->validate([
            'category' => 'nullable|string|max:255',
            'question' => 'required|string',
            'option_1' => 'required|string|max:255',
            'option_2' => 'required|string|max:255',
            'option_3' => 'required|string|max:255',
            'option_4' => 'required|string|max:255',
            'correct_answer' => 'required|integer|in:1,2,3,4',
        ]);

        $question = \App\Models\LevelTwoQuestion::findOrFail($id);
        $question->update($request->all());

        return redirect()->route('admin.level2')->with('success', 'Objective Protocol Updated Successfully!');
    }

    // --- LEVEL 3: THE HUMAN FIREWALL --- //

    public function showLevelThree()
    {
        $scenarios = \App\Models\LevelThreeScenario::all();
        return view('admin-level3', compact('scenarios'));
    }

    public function storeLevelThree(Request $request)
    {
        $request->validate([
            'platform' => 'required|string',
            'sender_name' => 'required|string|max:255',
            'message' => 'required|string',
            'explanation' => 'required|string',
        ]);

        \App\Models\LevelThreeScenario::create([
            'platform' => $request->platform,
            'sender_name' => $request->sender_name,
            'message' => $request->message,
            'explanation' => $request->explanation,
            'is_threat' => $request->has('is_threat') ? true : false,
        ]);

        return redirect()->route('admin.level3')->with('success', 'Social Engineering Scenario Added!');
    }

    public function updateLevelThree(Request $request, $id)
    {
        $request->validate([
            'platform' => 'required|string',
            'sender_name' => 'required|string|max:255',
            'message' => 'required|string',
            'explanation' => 'required|string',
        ]);

        $scenario = \App\Models\LevelThreeScenario::findOrFail($id);
        $scenario->update([
            'platform' => $request->platform,
            'sender_name' => $request->sender_name,
            'message' => $request->message,
            'explanation' => $request->explanation,
            'is_threat' => $request->has('is_threat') ? true : false,
        ]);

        return redirect()->route('admin.level3')->with('success', 'Scenario Updated Successfully!');
    }

    public function destroyLevelThree($id)
    {
        $scenario = \App\Models\LevelThreeScenario::findOrFail($id);
        $scenario->delete();

        return redirect()->back()->with('success', 'Scenario Purged!');
    }

    // --- LEVEL 4: THE MIRROR WEB --- //

    public function showLevelFour()
    {
        $scenarios = \App\Models\LevelFourScenario::all();
        return view('admin-level4', compact('scenarios'));
    }

    public function storeLevelFour(Request $request)
    {
        $request->validate([
            'url' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'clues' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:30720', // Max 5MB Image
        ]);

        // Automatically upload the image to storage/app/public/mirror_web
        $imagePath = $request->file('image')->store('mirror_web', 'public');

        \App\Models\LevelFourScenario::create([
            'url' => $request->url,
            'title' => $request->title,
            'clues' => $request->clues,
            'image_path' => $imagePath,
            'has_ssl' => $request->has('has_ssl'),
            'is_phishing' => $request->has('is_phishing'),
        ]);

        return redirect()->route('admin.level4')->with('success', 'Website Mockup Uploaded Successfully!');
    }

    public function updateLevelFour(Request $request, $id)
{
    $request->validate([
        'url' => 'required|string|max:255',
        'title' => 'required|string|max:255',
        'clues' => 'required|string',
        'explanation' => 'required|string', // <-- ADDED THIS LINE
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:30720', // Image is optional on update
    ]);

    $scenario = \App\Models\LevelFourScenario::findOrFail($id);
    $data = $request->except('image');
    
    $data['has_ssl'] = $request->has('has_ssl');
    $data['is_phishing'] = $request->has('is_phishing');

    // If the Admin uploaded a NEW image, delete the old one and save the new one
    if ($request->hasFile('image')) {
        if (Storage::disk('public')->exists($scenario->image_path)) {
            Storage::disk('public')->delete($scenario->image_path);
        }
        $data['image_path'] = $request->file('image')->store('mirror_web', 'public');
    }

    $scenario->update($data);

    return redirect()->route('admin.level4')->with('success', 'Website Mockup Updated Successfully!');
}

    public function destroyLevelFour($id)
    {
        $scenario = \App\Models\LevelFourScenario::findOrFail($id);
        
        // Delete the actual image file from the server
        if (Storage::disk('public')->exists($scenario->image_path)) {
            Storage::disk('public')->delete($scenario->image_path);
        }
        
        $scenario->delete();

        return redirect()->back()->with('success', 'Mockup Purged from Server!');
    }

    // --- LEVEL 5: S.H.I.E.L.D MAINFRAME (FINAL BOSS) --- //

    public function showLevelFive()
    {
        $questions = \App\Models\LevelFiveQuestion::all();
        $settings = \App\Models\MainframeSetting::firstOrCreate(['id' => 1]); 
        
        return view('admin-level5', compact('questions', 'settings'));
    }

    public function updateLevelFiveSettings(Request $request)
    {
        $request->validate([
            'total_minutes' => 'required|integer|min:1',
            'seconds_per_question' => 'required|integer|min:3',
            'streak_threshold_seconds' => 'required|integer|min:1',
            'streak_bonus_percent' => 'required|integer|min:0',
            'max_mistakes' => 'required|integer|min:1',
        ]);

        $settings = \App\Models\MainframeSetting::firstOrCreate(['id' => 1]);
        $settings->update($request->all());

        return redirect()->route('admin.level5')->with('settings_success', 'Mainframe Parameters Updated!');
    }

    public function storeLevelFive(Request $request)
    {
        $request->validate([
            'question' => 'required|string',
            'question_en' => 'nullable|string',
            'is_true' => 'required|boolean',
        ]);

        // Force save bypassing mass-assignment
        $q = new \App\Models\LevelFiveQuestion();
        $q->question = $request->question;
        $q->question_en = $request->question_en;
        $q->is_true = $request->is_true;
        $q->save();

        return redirect()->route('admin.level5')->with('success', 'Rapid-Fire Question Added!');
    }

    public function updateLevelFive(Request $request, $id)
    {
        $request->validate([
            'question' => 'required|string',
            'question_en' => 'nullable|string',
            'is_true' => 'required|boolean',
        ]);

        // Force update bypassing mass-assignment
        $question = \App\Models\LevelFiveQuestion::findOrFail($id);
        $question->question = $request->question;
        $question->question_en = $request->question_en;
        $question->is_true = $request->is_true;
        $question->save();

        return redirect()->route('admin.level5')->with('success', 'Rapid-Fire Question Updated!');
    }

    public function destroyLevelFive($id)
    {
        $question = \App\Models\LevelFiveQuestion::findOrFail($id);
        $question->delete();

        return redirect()->back()->with('success', 'Question Purged from Mainframe!');
    }

    // --- LEVEL 6: THE MINI-GAME ARCADE --- //

    public function showLevelSix()
    {
        // Simple and clean: Fetch all games. 
        // Laravel's MiniGame model should handle JSON casting automatically.
        $miniGames = MiniGame::orderBy('sort_order')->get();

        return view('admin-level6', compact('miniGames'));
    }

    public function storeLevelSix(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'game_type' => 'required|string|in:scramble,domino,connect,video_abcd,chess',
            'base_score' => 'required|integer|min:0',
            'game_data' => 'required|array',
        ]);

        $gameData = $request->game_data;

        // 🚨 NEW LOGIC: Safely process uploaded MP4 files for the Video Engine
        if ($request->game_type === 'video_abcd') {
            
            // 1. Process Main Default Video Upload
            if ($request->hasFile('game_data.video_file')) {
                $path = $request->file('game_data.video_file')->store('arcade_videos', 'public');
                $gameData['video_url'] = '/storage/' . $path;
            }
            unset($gameData['video_file']); // Strip file object before JSON conversion

            // 2. Process Individual Question Video Uploads
            if (isset($gameData['questions'])) {
                foreach ($gameData['questions'] as $key => $question) {
                    if ($request->hasFile("game_data.questions.{$key}.video_file")) {
                        $path = $request->file("game_data.questions.{$key}.video_file")->store('arcade_videos', 'public');
                        $gameData['questions'][$key]['video_url'] = '/storage/' . $path;
                    }
                    unset($gameData['questions'][$key]['video_file']); // Strip file object
                }
            }
        }

        MiniGame::create([
            'title' => $request->title,
            'game_type' => $request->game_type,
            'base_score' => $request->base_score,
            'instruction' => $request->instruction,
            'game_data' => $gameData, // Save the cleaned JSON data
            'is_active' => $request->has('is_active'),
            'sort_order' => MiniGame::count() + 1,
        ]);

        return redirect()->route('admin.level6')->with('success', 'Mini-Game module compiled successfully!');
    }

    public function updateLevelSix(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'game_type' => 'required|string|in:scramble,domino,connect,video_abcd,chess',
            'base_score' => 'required|integer|min:0',
            'game_data' => 'required|array',
        ]);

        $game = MiniGame::findOrFail($id);
        $gameData = $request->game_data;

        // 🚨 NEW LOGIC: Safely process uploaded MP4 files during edits
        if ($request->game_type === 'video_abcd') {
            
            if ($request->hasFile('game_data.video_file')) {
                $path = $request->file('game_data.video_file')->store('arcade_videos', 'public');
                $gameData['video_url'] = '/storage/' . $path;
            }
            unset($gameData['video_file']);

            if (isset($gameData['questions'])) {
                foreach ($gameData['questions'] as $key => $question) {
                    if ($request->hasFile("game_data.questions.{$key}.video_file")) {
                        $path = $request->file("game_data.questions.{$key}.video_file")->store('arcade_videos', 'public');
                        $gameData['questions'][$key]['video_url'] = '/storage/' . $path;
                    }
                    unset($gameData['questions'][$key]['video_file']); 
                }
            }
        }

        $game->update([
            'title' => $request->title,
            'game_type' => $request->game_type,
            'base_score' => $request->base_score,
            'instruction' => $request->instruction,
            'game_data' => $gameData,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.level6')->with('success', 'Mini-Game parameters updated!');
    }

    public function destroyLevelSix($id)
    {
        $game = MiniGame::findOrFail($id);
        $game->delete();

        return redirect()->back()->with('success', 'Mini-Game purged from the arcade.');
    }

    
}