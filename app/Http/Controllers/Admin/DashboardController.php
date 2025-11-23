<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DashboardItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DashboardController extends Controller
{
    /**
     * Menampilkan semua item dashboard.
     */
    public function index()
    {
        $items = DashboardItem::latest()->get();
        return view('pages.admin.dashboard', compact('items'));
    }

    /**
     * Menyimpan item baru ke dashboard.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'gambar' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('dashboard', 'public');
        }

        DashboardItem::create($data);

        return redirect()->route('pages.admin.dashboard')->with('success', 'Item berhasil ditambahkan.');
    }

    /**
     * Menghapus item dari dashboard berdasarkan ID.
     */
    public function destroy($id)
    {
        $item = DashboardItem::findOrFail($id);

        if ($item->gambar) {
            Storage::disk('public')->delete($item->gambar);
        }

        $item->delete();

        return back()->with('success', 'Item berhasil dihapus.');
    }
}