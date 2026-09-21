<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Stichoza\GoogleTranslate\GoogleTranslate; // 👈 Wajib tambah untuk Auto-Translate

class QuestionController extends Controller
{
    public function index(Room $room)
    {
        $questions = $room->questions()->get();
        return view('admin.questions.index', compact('room', 'questions'));
    }

    public function create(Room $room)
    {
        $otherQuestions = $room->questions()->get();
        return view('admin.questions.create', compact('room', 'otherQuestions'));
    }

    public function store(Request $request, Room $room)
    {
        $request->validate([
            'text' => 'required|string',
            'level' => 'required|integer',
            'video_url' => 'nullable|url',
            'video_upload' => 'nullable|mimes:mp4,m4v,mov|max:51200',
            'video_description' => 'nullable|string',
            'video_duration' => 'nullable|integer|min:0',
            'options' => 'nullable|array',
            'options.*.text' => 'required_with:options|string',
            'options.*.points' => 'required_with:options|integer',
            'options.*.next_question_id' => 'nullable|exists:questions,id',
            'options.*.feedback' => 'nullable|string',
        ]);

        $videoPath = null;
        if ($request->hasFile('video_upload')) {
            $videoPath = $request->file('video_upload')->store('videos/questions', 'public');
        }

        // 🤖 SETUP ENJIN TRANSLATE
        $tr = new GoogleTranslate('en');
        $tr->setSource('ms');

        // Translate Teks Soalan Utama
        $text_en = null;
        try {
            if (!empty($request->text)) {
                $text_en = $tr->translate($request->text);
            }
        } catch (\Exception $e) {}

        // 2. Simpan Teras Soalan + Tetapan Video
        $question = $room->questions()->create([
            'text' => $request->text,
            'text_en' => $text_en, // 👈 Simpan teks soalan EN
            'level' => $request->level,
            'video_url' => $request->video_url,
            'video_path' => $videoPath,
            'video_description' => $request->video_description,
            'video_duration' => $request->video_duration,
        ]);

        // 3. Simpan Semua Pilihan Jawapan (Jika Ada)
        if ($request->has('options')) {
            foreach ($request->options as $opt) {
                // Translate Teks Pilihan Jawapan
                $opt_text_en = null;
                try {
                    if (!empty($opt['text'])) {
                        $opt_text_en = $tr->translate($opt['text']);
                    }
                } catch (\Exception $e) {}

                $question->options()->create([
                    'text' => $opt['text'],
                    'text_en' => $opt_text_en, // 👈 Simpan teks jawapan EN
                    'points' => $opt['points'] ?? 0,
                    'next_question_id' => !empty($opt['next_question_id']) ? $opt['next_question_id'] : null,
                    'feedback' => $opt['feedback'] ?? null,
                ]);
            }
        }

        return redirect()->route('admin.rooms.questions.index', $room->id)
                         ->with('success', 'Soalan dan pilihan jawapan berjaya ditambah & diterjemah!');
    }

    public function edit(Room $room, Question $question)
    {
        $otherQuestions = $room->questions()->where('id', '!=', $question->id)->get();
        return view('admin.questions.edit', compact('room', 'question', 'otherQuestions'));
    }

    public function update(Request $request, Room $room, Question $question)
    {
        $request->validate([
            'text' => 'required|string',
            'level' => 'required|integer',
            'video_url' => 'nullable|url',
            'video_upload' => 'nullable|mimes:mp4,m4v,mov|max:51200',
            'video_description' => 'nullable|string',
            'video_duration' => 'nullable|integer|min:0',
            'options' => 'nullable|array',
            'options.*.text' => 'required_with:options|string',
            'options.*.points' => 'required_with:options|integer',
            'options.*.next_question_id' => 'nullable|exists:questions,id',
            'options.*.feedback' => 'nullable|string',
        ]);

        $videoPath = $question->video_path; 
        
        if ($request->hasFile('video_upload')) {
            if ($videoPath && Storage::disk('public')->exists($videoPath)) {
                Storage::disk('public')->delete($videoPath);
            }
            $videoPath = $request->file('video_upload')->store('videos/questions', 'public');
        }

        // 🤖 SETUP ENJIN TRANSLATE
        $tr = new GoogleTranslate('en');
        $tr->setSource('ms');

        // Translate Teks Soalan Utama
        $text_en = null;
        try {
            if (!empty($request->text)) {
                $text_en = $tr->translate($request->text);
            }
        } catch (\Exception $e) {}

        // 2. Kemas kini rekod soalan
        $question->update([
            'text' => $request->text,
            'text_en' => $text_en, // 👈 Update teks soalan EN
            'level' => $request->level,
            'video_url' => $request->video_url,
            'video_path' => $videoPath,
            'video_description' => $request->video_description,
            'video_duration' => $request->video_duration,
        ]);

        // 3. Cara paling selamat: Padam jawapan lama, masukkan yang baru berserta translation
        if ($request->has('options')) {
            $question->options()->delete(); 
            foreach ($request->options as $opt) {
                // Translate Teks Pilihan Jawapan
                $opt_text_en = null;
                try {
                    if (!empty($opt['text'])) {
                        $opt_text_en = $tr->translate($opt['text']);
                    }
                } catch (\Exception $e) {}

                $question->options()->create([
                    'text' => $opt['text'],
                    'text_en' => $opt_text_en, // 👈 Simpan teks jawapan EN
                    'points' => $opt['points'] ?? 0,
                    'next_question_id' => !empty($opt['next_question_id']) ? $opt['next_question_id'] : null,
                    'feedback' => $opt['feedback'] ?? null,
                ]);
            }
        }

        return redirect()->route('admin.rooms.questions.index', $room->id)
                         ->with('success', 'Soalan, video, dan pilihan jawapan berjaya dikemas kini & diterjemah!');
    }

    public function destroy(Room $room, Question $question)
    {
        if ($question->video_path && Storage::disk('public')->exists($question->video_path)) {
            Storage::disk('public')->delete($question->video_path);
        }

        $question->delete();
        return redirect()->route('admin.rooms.questions.index', $room->id)
                         ->with('success', 'Soalan dan pilihan jawapannya telah berjaya dipadam!');
    }
}