<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Promo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PromoController extends Controller
{
    public function index()
    {
        $promos = Promo::all();
        return view('pages.admin.promos.index', compact('promos'));
    }

    public function create()
    {
        return view('pages.admin.promos.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'vendor' => 'required|string',
            'label' => 'nullable|string',
            'periode' => 'required|date',
            'discount' => 'nullable|string',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'description' => 'nullable|string',
            'terms' => 'nullable|string',
        ]);

        // Simpan gambar ke disk 'public' di folder 'promo_images'
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('promo_images', 'public'); // => storage/app/public/promo_images/...
            $validated['image'] = $path; // simpan path relatif ke storage (contoh: promo_images/12345.jpg)
        }

        Promo::create($validated);

        return redirect()->route('admin.promos.index')->with('success', 'Promo berhasil ditambahkan');
    }

    public function edit(Promo $promo)
    {
        return view('pages.admin.promos.create', compact('promo')); // reuse create view for edit
    }

    public function update(Request $request, Promo $promo)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'vendor' => 'required|string',
            'label' => 'nullable|string',
            'discount' => 'nullable|string',
            'periode' => 'required|date',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'description' => 'nullable|string',
            'terms' => 'nullable|string',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('promo_images', 'public');
            $data['image'] = $path;
        }

        $promo->update($data);

        return redirect()->route('admin.promos.index')->with('success', 'Promo berhasil diperbarui');
    }

    public function destroy(Promo $promo)
    {
        // optional: hapus file gambar dari storage jika ada
        if ($promo->image && Storage::disk('public')->exists($promo->image)) {
            Storage::disk('public')->delete($promo->image);
        }
        $promo->delete();
        return redirect()->route('admin.promos.index')->with('success', 'Promo berhasil dihapus');
    }
}