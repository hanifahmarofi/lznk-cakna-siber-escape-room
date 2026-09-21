<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\Option;
use Illuminate\Http\Request;
use Stichoza\GoogleTranslate\GoogleTranslate; // 👈 Wajib tambah

class OptionController extends Controller
{
    public function index(Question $question)
    {
        $options = $question->options()->get();
        $room = $question->room;

        return view('admin.options.index', compact('room', 'question', 'options'));
    }

    public function create(Question $question)
    {
        $room = $question->room;
        $otherQuestions = $room->questions()->where('id', '!=', $question->id)->get();

        return view('admin.options.create', compact('room', 'question', 'otherQuestions'));
    }

    public function store(Request $request, Question $question)
    {
        $request->validate([
            'text' => 'required|string',
            'points' => 'required|integer',
            'feedback' => 'nullable|string',
            'next_question_id' => 'nullable|exists:questions,id'
        ]);

        // 🤖 AUTO-TRANSLATE PILIHAN JAWAPAN TUNGGAL
        $text_en = null;
        try {
            $tr = new GoogleTranslate('en');
            $tr->setSource('ms');
            if (!empty($request->text)) {
                $text_en = $tr->translate($request->text);
            }
        } catch (\Exception $e) {}

        $question->options()->create([
            'text' => $request->text,
            'text_en' => $text_en, // 👈 Simpan versi EN
            'points' => $request->points,
            'feedback' => $request->feedback,
            'next_question_id' => !empty($request->next_question_id) ? $request->next_question_id : null,
        ]);

        return redirect()->route('admin.questions.options.index', $question->id)
                         ->with('success', 'Pilihan jawapan baharu berjaya ditambah dan diterjemah!');
    }

    public function destroy(Question $question, Option $option)
    {
        $option->delete();
        return back()->with('success', 'Pilihan jawapan telah berjaya dipadam!');
    }
}