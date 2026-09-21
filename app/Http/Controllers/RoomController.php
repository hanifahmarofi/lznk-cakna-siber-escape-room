<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Room;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Stichoza\GoogleTranslate\GoogleTranslate; 

class RoomController extends Controller
{
    public function index()
    {
        $rooms = Room::withCount('questions')->orderBy('created_at', 'desc')->get();
        return view('admin.rooms.index', compact('rooms'));
    }

    public function create()
    {
        return view('admin.rooms.create');
    }

    public function store(Request $request)
    {
        // 1. VALIDASI DATA DARI BORANG
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'pass_mark' => 'required|integer|min:0', // 👈 Mesti diletakkan di sini
            'title_en' => 'nullable|string|max:255',
            'description_en' => 'nullable|string',
            'video_url' => 'nullable|url',
            'video_upload' => 'nullable|mimes:mp4,mov,ogg|max:50000',
            'video_description' => 'nullable|string',
            'video_duration' => 'nullable|integer|min:0',
        ]);

        $videoPath = null;
        if ($request->hasFile('video_upload')) {
            $videoPath = $request->file('video_upload')->store('videos', 'public');
        }

        // 2. MAGIS AUTO-TRANSLATE
        $title_en = $request->title_en;
        $description_en = $request->description_en;

        try {
            $tr = new GoogleTranslate('en');
            $tr->setSource('ms');

            if (empty($title_en) && !empty($request->title)) {
                $title_en = $tr->translate($request->title);
            }
            if (empty($description_en) && !empty($request->description)) {
                $description_en = $tr->translate($request->description);
            }
        } catch (\Exception $e) {
            // Abaikan ralat translasi
        }

        // 3. SIMPAN KE DATABASE
        Room::create([
            'title' => $request->title,
            'description' => $request->description,
            'pass_mark' => $request->pass_mark, // 👈 Tangkap dan simpan nilai
            'title_en' => $title_en,
            'description_en' => $description_en,
            'video_url' => $request->video_url,
            'video_path' => $videoPath,
            'video_description' => $request->video_description,
            'video_duration' => $request->video_duration ?? 15,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.rooms.index')->with('success', 'Bilik berjaya dicipta & diterjemah secara automatik!');
    }

    public function edit(Room $room)
    {
        return view('admin.rooms.edit', compact('room'));
    }

    public function update(Request $request, Room $room)
    {
        // 1. VALIDASI DATA DARI BORANG
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'pass_mark' => 'required|integer|min:0', // 👈 Mesti diletakkan di sini
            'title_en' => 'nullable|string|max:255',
            'description_en' => 'nullable|string',
            'video_url' => 'nullable|url',
            'video_upload' => 'nullable|mimes:mp4,mov,ogg|max:50000',
            'video_description' => 'nullable|string',
            'video_duration' => 'nullable|integer|min:0',
        ]);

        $videoPath = $room->video_path;
        
        if ($request->hasFile('video_upload')) {
            if ($videoPath) {
                Storage::disk('public')->delete($videoPath);
            }
            $videoPath = $request->file('video_upload')->store('videos', 'public');
        }

        // 2. MAGIS AUTO-TRANSLATE
        $title_en = $request->title_en;
        $description_en = $request->description_en;

        try {
            $tr = new GoogleTranslate('en'); 
            $tr->setSource('ms'); 

            if (empty($title_en) && !empty($request->title)) {
                $title_en = $tr->translate($request->title);
            }
            if (empty($description_en) && !empty($request->description)) {
                $description_en = $tr->translate($request->description);
            }
        } catch (\Exception $e) {
            // Abaikan ralat translasi
        }

        // 3. KEMASKINI DATABASE
        $room->update([
            'title' => $request->title,
            'description' => $request->description,
            'pass_mark' => $request->pass_mark, // 👈 Tangkap dan kemaskini nilai
            'title_en' => $title_en,
            'description_en' => $description_en,
            'video_url' => $request->video_url,
            'video_path' => $videoPath,
            'video_description' => $request->video_description,
            'video_duration' => $request->video_duration ?? 15,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.rooms.index')->with('success', 'Maklumat bilik berjaya dikemas kini!');
    }
    
    public function destroy(Room $room)
    {
        $room->delete();
        return redirect()->route('admin.rooms.index')->with('success', 'Bilik beserta semua soalannya telah berjaya dipadam!');
    }

    public function show($id)
    {
        $room = Room::with(['questions.options'])->findOrFail($id);
        return view('admin.rooms.show', compact('room'));
    }

    public function visualMap(Room $room)
    {
        $room->load('questions.options');
        return view('admin.rooms.visual-map', compact('room'));
    }

    public function visualMapFullscreen(Room $room)
    {
        $room->load('questions.options');

        $mermaidMs = "graph LR\n"; 
        $mermaidEn = "graph LR\n"; 

        $classDef = "classDef questionNode fill:#0f2818,stroke:#d4af37,stroke-width:2px,color:#f0d56f,rx:4px,ry:4px\n";
        $classDef .= "classDef endNode fill:#450a0a,stroke:#ef4444,stroke-width:2px,color:#fca5a5,rx:20px,ry:20px\n\n";
        
        $mermaidMs .= $classDef;
        $mermaidEn .= $classDef;

        foreach($room->questions as $q) {
            // 1. Bersihkan ayat dari simbol Quote (") supaya kod Mermaid tak rosak
            $cleanQTextMs = str_replace(['"', "\n", "\r"], ['', ' ', ''], $q->text);
            $cleanQTextEn = str_replace(['"', "\n", "\r"], ['', ' ', ''], $q->text_en ?: $q->text);
            
            // 2. Patahkan ayat (Wrap) setiap 40 huruf ke baris baharu guna <br/>
            $wrappedQTextMs = wordwrap(trim($cleanQTextMs), 40, "<br/>");
            $wrappedQTextEn = wordwrap(trim($cleanQTextEn), 40, "<br/>");

            $mermaidMs .= "Q{$q->id}[\"ID: {$q->id}<br/>{$wrappedQTextMs}\"]\n";
            $mermaidMs .= "class Q{$q->id} questionNode\n";

            $mermaidEn .= "Q{$q->id}[\"ID: {$q->id}<br/>{$wrappedQTextEn}\"]\n";
            $mermaidEn .= "class Q{$q->id} questionNode\n";

            foreach($q->options as $opt) {
                // 1. Bersihkan ayat pilihan jawapan
                $cleanOptTextMs = str_replace(['"', "\n", "\r"], ['', ' ', ''], $opt->text);
                $cleanOptTextEn = str_replace(['"', "\n", "\r"], ['', ' ', ''], $opt->text_en ?: $opt->text);

                // 2. Patahkan ayat jawapan setiap 30 huruf
                $wrappedOptTextMs = wordwrap(trim($cleanOptTextMs), 30, "<br/>");
                $wrappedOptTextEn = wordwrap(trim($cleanOptTextEn), 30, "<br/>");

                // 3. Format Markah (+ atau -)
                $ptMark = $opt->points > 0 ? "+{$opt->points} Pts" : "{$opt->points} Pts";

                if ($opt->next_question_id) {
                    $mermaidMs .= "Q{$q->id} -->|\"{$wrappedOptTextMs}<br/>[{$ptMark}]\"| Q{$opt->next_question_id}\n";
                    $mermaidEn .= "Q{$q->id} -->|\"{$wrappedOptTextEn}<br/>[{$ptMark}]\"| Q{$opt->next_question_id}\n";
                } else {
                    $mermaidMs .= "Q{$q->id} -->|\"{$wrappedOptTextMs}<br/>[{$ptMark}]\"| End{$opt->id}([\"TAMAT\"])\n";
                    $mermaidMs .= "class End{$opt->id} endNode\n";

                    $mermaidEn .= "Q{$q->id} -->|\"{$wrappedOptTextEn}<br/>[{$ptMark}]\"| End{$opt->id}([\"END\"])\n";
                    $mermaidEn .= "class End{$opt->id} endNode\n";
                }
            }
        }

        return view('admin.rooms.visual-map-fullscreen', compact('room', 'mermaidMs', 'mermaidEn'));
    }
}