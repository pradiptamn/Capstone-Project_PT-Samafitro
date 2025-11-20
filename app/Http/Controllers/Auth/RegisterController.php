<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('pages.auth.register');
    }

    public function register(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|max:20',
            'address' => 'nullable|string',
            'password' => 'required|string|confirmed|min:8',
            'ktp_number' => 'required|string|unique:users,ktp_number',
            'ktp_photo' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'company_name' => 'nullable|string|max:255',
            'npwp' => 'nullable|string',
            'security_question' => 'required|string',
            'security_answer' => 'required|string',
        ], [
            'ktp_photo.required' => 'Foto KTP wajib diunggah.',
            'ktp_photo.image' => 'File harus berupa gambar.',
            'ktp_photo.mimes' => 'Format gambar harus JPG, JPEG, atau PNG.',
            'ktp_photo.max' => 'Ukuran gambar maksimal 2MB.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Simpan foto KTP
        $ktpPhotoPath = null;
        if ($request->hasFile('ktp_photo') && $request->file('ktp_photo')->isValid()) {
            $ktpPhotoPath = $request->file('ktp_photo')->store('ktp_photos', 'public');
        }

        // Buat user baru
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'password' => Hash::make($request->password),
            'ktp_number' => $request->ktp_number,
            'ktp_photo' => $ktpPhotoPath,
            'company_name' => $request->company_name,
            'npwp' => $request->npwp,
            'security_question' => $request->security_question,
            'security_answer' => Hash::make($request->security_answer),
        ]);

        return redirect('/login')->with('status', 'Pendaftaran berhasil! Silakan masuk.');
    }
}