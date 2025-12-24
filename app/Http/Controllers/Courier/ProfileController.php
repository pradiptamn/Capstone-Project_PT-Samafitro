<?php

namespace App\Http\Controllers\Courier;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
  public function edit()
  {
    $user = Auth::user();

    // Menghitung jumlah kiriman yang sudah selesai
    // Asumsi: kolom kurir di tabel orders adalah 'courier_id'
    $completedDeliveries = Order::where('courier_id', $user->id)
      ->where('status', 'completed')
      ->count();

    return view('pages.courier.profile', compact('user', 'completedDeliveries'));
  }

  public function update(Request $request)
  {
    $user = Auth::user();

    $request->validate([
      'phone' => 'required|numeric',
      'security_question' => 'required|string|max:255',
      'security_answer' => 'nullable|string|min:3',
    ]);

    // Nama dan Foto tidak masuk proses update demi keamanan
    $user->phone = $request->phone;
    $user->security_question = $request->security_question;

    if ($request->filled('security_answer')) {
      $user->security_answer = Hash::make($request->security_answer);
    }

    $user->save();

    return back()->with('success', 'Profil dan Keamanan berhasil diperbarui.');
  }
}
