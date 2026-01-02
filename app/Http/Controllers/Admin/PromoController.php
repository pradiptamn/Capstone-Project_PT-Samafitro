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
        // 1. Bersihkan dulu tanda % sebelum validasi dimulai
        if ($request->has('discount')) {
            $request->merge([
                'discount' => str_replace('%', '', $request->discount)
            ]);
        }

        // 2. Validasi ketat
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'vendor' => 'required|string',
            'label'    => 'nullable|string',
            'discount' => 'required|numeric|min:0|max:100',
            'periode' => 'required|date',
            'image' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'terms' => 'required',
        ], [
            // Custom pesan error bahasa Indonesia
            'discount.numeric' => 'Kolom diskon harus berupa angka (contoh: 20).',
            'discount.max' => 'Diskon tidak boleh lebih dari 100%.',
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
        if ($request->has('discount')) {
            $request->merge([
                'discount' => str_replace('%', '', $request->discount)
            ]);
        }

        $data = $request->validate([
            'name' => 'required|string',
            'vendor' => 'required|string',
            'label' => 'nullable|string',
            'discount' => 'nullable|numeric|min:0|max:100',
            'periode' => 'required|date',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'description' => 'nullable|string',
            'terms' => 'nullable|string',
        ], [
            // Custom pesan error bahasa Indonesia
            'discount.numeric' => 'Kolom diskon harus berupa angka (contoh: 20).',
            'discount.max' => 'Diskon tidak boleh lebih dari 100%.',
        ]);

        if ($request->hasFile('image')) {
            if ($promo->image && Storage::disk('public')->exists($promo->image)) {
                Storage::disk('public')->delete($promo->image);
            }

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
