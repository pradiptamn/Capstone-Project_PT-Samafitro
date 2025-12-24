<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class CourierController extends Controller
{
    /**
     * Menampilkan daftar kurir
     */
    public function index()
    {
        // Ambil hanya user dengan role 'courier'
        $couriers = User::where('role', 'courier')->latest()->paginate(10);
        return view('pages.admin.couriers.index', compact('couriers'));
    }

    /**
     * Menampilkan form tambah kurir
     */
    public function create()
    {
        return view('pages.admin.couriers.create');
    }

    /**
     * Menyimpan data kurir baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'phone' => 'required|numeric',
            'ktp_number' => 'required|numeric|unique:users,ktp_number',
            'photo' => 'required|image|max:1024',
            'ktp_photo' => 'required|image|max:2048',
        ]);

        $data = $request->all();
        $data['role'] = 'courier'; // Paksa role jadi courier
        $data['password'] = Hash::make($request->password);

        $data['security_question'] = 'Not Set';
        $data['security_answer'] = Hash::make('Not Set');

        // Simpan Foto Profil ke folder profile_photos
        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('profile_photos', 'public');
        }

        // Simpan Foto KTP ke folder ktp_photos
        if ($request->hasFile('ktp_photo')) {
            $data['ktp_photo'] = $request->file('ktp_photo')->store('ktp_photos', 'public');
        }

        User::create($data);

        return redirect()->route('admin.couriers.index')->with('success', 'Kurir berhasil ditambahkan.');
    }

    /**
     * Menampilkan form edit kurir
     */
    public function edit($id)
    {
        $courier = User::findOrFail($id);
        return view('pages.admin.couriers.edit', compact('courier'));
    }

    /**
     * Mengupdate data kurir
     */
    public function update(Request $request, $id)
    {
        $courier = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'phone' => 'required|numeric',
            'ktp_number' => 'required|numeric|unique:users,ktp_number,' . $id,
            'photo' => 'nullable|image|max:1024',
            'ktp_photo' => 'nullable|image|max:2048',
        ]);

        $data = $request->except(['password']);

        // Update password hanya jika diisi
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        // Update Foto Profil
        if ($request->hasFile('photo')) {
            if ($courier->photo) {
                Storage::disk('public')->delete($courier->photo);
            }
            $data['photo'] = $request->file('photo')->store('profile_photos', 'public');
        }

        // Update Foto KTP
        if ($request->hasFile('ktp_photo')) {
            if ($courier->ktp_photo) {
                Storage::disk('public')->delete($courier->ktp_photo);
            }
            $data['ktp_photo'] = $request->file('ktp_photo')->store('ktp_photos', 'public');
        }

        $courier->update($data);

        return redirect()->route('admin.couriers.index')->with('success', 'Data kurir diperbarui.');
    }

    /**
     * Menghapus kurir
     */
    public function destroy($id)
    {
        $courier = User::findOrFail($id);

        if ($courier->photo) {
            Storage::disk('public')->delete($courier->photo);
        }

        if ($courier->ktp_photo) {
            Storage::disk('public')->delete($courier->ktp_photo);
        }

        $courier->delete();

        return back()->with('success', 'Kurir berhasil dihapus.');
    }
}
