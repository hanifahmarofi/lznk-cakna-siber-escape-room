<?php

namespace App\Http\Controllers;

use App\Models\InfoGallery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class InfoGalleryController extends Controller
{
    // 1. Tunjuk halaman pengurusan galeri
    public function index()
    {
        $galleries = InfoGallery::latest()->get();
        return view('admin.gallery-manager', compact('galleries'));
    }

    // 2. Simpan gambar dan info ke database
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:20480', // Maksimum 5MB
            'description' => 'required|string'
        ]);

        // Simpan fail fizikal ke dalam folder 'storage/app/public/gallery'
        $imagePath = $request->file('image')->store('gallery', 'public');

        InfoGallery::create([
            'title' => $request->title,
            'image_path' => $imagePath,
            'description' => $request->description,
            'is_active' => true
        ]);

        return back()->with('success', 'SYSTEM UPDATE: Intel Poster successfully uploaded.');
    }

    // 3. Padam gambar dan rekod
    public function destroy($id)
    {
        $gallery = InfoGallery::findOrFail($id);
        
        // Padam gambar fizikal dari server supaya tidak memenuhi ruang
        if (Storage::disk('public')->exists($gallery->image_path)) {
            Storage::disk('public')->delete($gallery->image_path);
        }
        
        $gallery->delete();

        return back()->with('success', 'SYSTEM ALERT: Intel Poster deleted.');
    }

    // 4. Buka halaman Edit
    public function edit($id)
    {
        $gallery = InfoGallery::findOrFail($id);
        return view('admin.gallery-edit', compact('gallery'));
    }

    // 5. Simpan perubahan (Update)
    public function update(Request $request, $id)
    {
        $gallery = InfoGallery::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:20480', // nullable sebab admin mungkin tak tukar gambar
            'description' => 'required|string'
        ]);

        // Jika admin upload gambar baru, kita padam yang lama dan simpan yang baru
        if ($request->hasFile('image')) {
            if (Storage::disk('public')->exists($gallery->image_path)) {
                Storage::disk('public')->delete($gallery->image_path);
            }
            $imagePath = $request->file('image')->store('gallery', 'public');
            $gallery->image_path = $imagePath;
        }

        // Kemaskini tajuk dan deskripsi
        $gallery->title = $request->title;
        $gallery->description = $request->description;
        $gallery->save();

        return redirect()->route('admin.gallery.index')->with('success', 'SYSTEM UPDATE: Intel Poster successfully updated.');
    }
}