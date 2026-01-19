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

        // --- Logika Validasi Dinamis ---
        // Cek apakah data di database kosong
        $isKtpNumberEmpty = empty($user->ktp_number);
        $isKtpPhotoEmpty = empty($user->ktp_photo);
        $isSecurityEmpty = empty($user->security_question) || empty($user->security_answer);

        $rules = [
            'name'             => 'required|string|max:255',
            'phone'            => 'nullable|string|max:20',
            'address'          => 'nullable|string|max:500',
            'company_name'     => 'nullable|string|max:255',
            'npwp'             => 'nullable|string|max:50',
            'photo'            => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'current_password' => 'nullable|required_with:new_password',
            'new_password'     => ['nullable', 'confirmed', \Illuminate\Validation\Rules\Password::defaults()],
        ];

        // Jika data KTP/Keamanan kosong di DB, maka WAJIB diisi di form
        $rules['ktp_number'] = $isKtpNumberEmpty ? 'required|string|max:50' : 'nullable|string|max:50';
        $rules['ktp_photo']  = $isKtpPhotoEmpty ? 'required|image|mimes:jpeg,png,jpg,gif|max:2048' : 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048';

        $securityQuestions = 'Siapa nama ibu kandung Anda,Apa nama sekolah pertama Anda,Apa makanan favorite Anda';
        $rules['security_question'] = $isSecurityEmpty ? "required|string|in:$securityQuestions" : "nullable|string|in:$securityQuestions";
        $rules['security_answer']   = $isSecurityEmpty ? 'required|string|max:255' : 'nullable|string|max:255';

        $request->validate($rules, [
            'ktp_number.required' => 'Nomor KTP wajib diisi karena data Anda belum lengkap.',
            'ktp_photo.required'  => 'Foto KTP wajib diunggah karena data Anda belum lengkap.',
            'security_question.required' => 'Pertanyaan keamanan wajib dipilih.',
            'security_answer.required'   => 'Jawaban keamanan wajib diisi.',
        ]);

        // --- Proses Update Data ---
        $user->name         = $request->name;
        $user->phone        = $request->phone;
        $user->address      = $request->address;
        $user->company_name = $request->company_name;
        $user->npwp         = $request->npwp;
        $user->ktp_number   = $request->ktp_number;

        // Update Pertanyaan Keamanan & Hash Jawaban jika diisi
        if ($request->filled('security_answer')) {
            $user->security_question = $request->security_question;
            $user->security_answer   = Hash::make($request->security_answer);
        }

        // Handle Foto Profil
        if ($request->hasFile('photo')) {
            if ($user->photo && Storage::disk('public')->exists($user->photo)) {
                Storage::disk('public')->delete($user->photo);
            }
            $user->photo = $request->file('photo')->store('profile_photos', 'public');
        }

        // Handle Foto KTP
        if ($request->hasFile('ktp_photo')) {
            if ($user->ktp_photo && Storage::disk('public')->exists($user->ktp_photo)) {
                Storage::disk('public')->delete($user->ktp_photo);
            }
            $user->ktp_photo = $request->file('ktp_photo')->store('ktp_photos', 'public');
        }

        // Handle Password
        if ($request->filled('current_password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Password lama salah.']);
            }
            $user->password = Hash::make($request->new_password);
        }

        $user->save();
        return redirect()->route('profile.edit')->with('success', 'Profil berhasil diperbarui.');
    }
}
