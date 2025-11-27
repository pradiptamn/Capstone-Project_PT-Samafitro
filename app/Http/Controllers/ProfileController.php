<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules;

class ProfileController extends Controller
{
    // Tampilkan halaman profil
    public function index()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        return view('pages.profile.index', compact('user'));
    }

    // Tampilkan form edit profil
    public function edit()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        return view('pages.profile.edit', compact('user'));
    }

    // Simpan hasil update profil
    public function update(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        // 1. Validasi Input
        $request->validate(
            [
                'name'         => 'required|string|max:255',
                'phone'        => 'nullable|string|max:20',
                'address'      => 'nullable|string|max:500',
                'company_name' => 'nullable|string|max:255',
                'npwp'         => 'nullable|string|max:50',
                'ktp_number'   => 'nullable|string|max:50',

                // Validasi File Gambar
                'photo'        => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Max 2MB
                'ktp_photo'    => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Max 2MB

                // Validasi Password (Opsional, hanya jika diisi)
                'current_password' => 'nullable|required_with:new_password',
                'new_password'     => ['nullable', 'confirmed', Rules\Password::defaults()],
            ],
            [
                'ktp_photo.image' => 'File harus berupa gambar.',
                'ktp_photo.mimes' => 'Format gambar harus JPG, JPEG, atau PNG.',
                'ktp_photo.max' => 'Ukuran gambar maksimal 2MB.',
            ]
        );

        $user = Auth::user();

        // 2. Update Informasi Dasar
        $user->name         = $request->name;
        $user->phone        = $request->phone;
        $user->address      = $request->address;
        $user->company_name = $request->company_name;
        $user->npwp         = $request->npwp;
        $user->ktp_number   = $request->ktp_number;

        // 3. Handle Upload Foto Profil
        if ($request->hasFile('photo')) {
            // Hapus foto lama jika ada (dan bukan foto default/url eksternal)
            if ($user->photo && Storage::disk('public')->exists($user->photo)) {
                Storage::disk('public')->delete($user->photo);
            }

            // Simpan foto baru di folder 'profile_photos'
            $path = $request->file('photo')->store('profile_photos', 'public');
            $user->photo = $path;
        }

        // 4. Handle Upload Foto KTP
        if ($request->hasFile('ktp_photo')) {
            // Hapus foto KTP lama jika ada
            if ($user->ktp_photo && Storage::disk('public')->exists($user->ktp_photo)) {
                Storage::disk('public')->delete($user->ktp_photo);
            }

            // Simpan foto KTP baru di folder 'ktp_photos' (Sesuai Permintaan)
            $path = $request->file('ktp_photo')->store('ktp_photos', 'public');
            $user->ktp_photo = $path;
        }

        // 5. Handle Ganti Password
        if ($request->filled('current_password')) {
            // Cek apakah password lama benar
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Password lama yang Anda masukkan salah.']);
            }

            // Update password baru
            $user->password = Hash::make($request->new_password);
        }

        // 6. Simpan Perubahan ke Database
        $user->save();

        return redirect()->route('profile.edit')->with('success', 'Profil berhasil diperbarui.');
    }
}
