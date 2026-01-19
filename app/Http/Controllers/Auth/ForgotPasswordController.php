<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\Rules;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\RateLimiter;

class ForgotPasswordController extends Controller
{
    /**
     * Menampilkan form pertama: masukkan email
     */
    public function showEmailForm()
    {
        return view('pages.auth.forgot-password');
    }

    /**
     * Menampilkan form kedua: pertanyaan keamanan
     */
    public function showChallengeForm(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        $user = User::where('email', $request->email)->first();

        // Jika user tidak ada ATAU tidak punya pertanyaan keamanan
        // Langsung lempar ke alur email standar
        if (!$user || !$user->security_question) {
            return $this->sendResetLinkEmail($request);
        }

        // Tampilkan view baru untuk challenge
        return view('pages.auth.password-question', [
            'email' => $user->email,
            'question' => $user->security_question
        ]);
    }

    /**
     * Memverifikasi jawaban dari challenge
     */
    public function verifyChallenge(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'answer' => 'required|string',
        ]);

        // SECURITY: RATE LIMITING
        $throttleKey = 'challenge-limit:' . Str::lower($request->email) . '|' . $request->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) { // Max 5x
            $seconds = RateLimiter::availableIn($throttleKey);
            return back()->withInput()->withErrors(['answer' => "Terlalu banyak percobaan. Tunggu $seconds detik."]);
        }

        $user = User::where('email', $request->email)->first();

        // Jika user tidak ada, atau jawaban salah (WAJIB pakai Hash::check)
        if (!$user || !Hash::check($request->answer, $user->security_answer)) {
            RateLimiter::hit($throttleKey); // Hitung kesalahan

            return view('pages.auth.password-question', [
                'email' => $request->email,
                'question' => $user ? $user->security_question : 'Pertanyaan tidak ditemukan'
            ])->withErrors(['answer' => 'Jawaban keamanan salah.']);
        }

        // --- JAWABAN BENAR! ---
        // Kita tidak mengirim email. Kita buat token reset SEKARANG JUGA
        // dan langsung redirect ke halaman ganti password.

        // Berhasil: Bersihkan Limit
        RateLimiter::clear($throttleKey);

        $token = Password::broker()->createToken($user);

        // Redirect langsung ke form reset password dengan membawa token
        return redirect()->route('password.reset', [
            'token' => $token,
            'email' => $user->email,
        ]);
    }

    /**
     * Kirim link reset password ke email (Standar Laravel).
     * Digunakan jika user memilih "Lupa Jawaban" atau tidak punya pertanyaan.
     */
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        // Kirim link reset
        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status == Password::RESET_LINK_SENT) {
            return redirect()->route('login')->with('status', __($status));
        }
        return view('pages.auth.forgot-password')
            ->withInput($request->only('email'))
            ->withErrors(['email' => __($status)]);
    }

    /**
     * Tampilkan form ganti password baru.
     */
    public function showResetForm(Request $request)
    {
        return view('pages.auth.reset-password', [
            'token' => $request->route('token'),
            'email' => $request->email,
        ]);
    }

    /**
     * Proses update password ke database.
     */
    public function storeNewPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Gunakan Password Broker bawaan Laravel untuk validasi token & update
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        // Jika berhasil
        if ($status == Password::PASSWORD_RESET) {
            return redirect()->route('login')->with('status', __($status));
        }

        // Jika gagal (token expired/salah)
        return back()->withInput($request->only('email'))
            ->withErrors(['email' => __($status)]);
    }
}