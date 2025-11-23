<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        $products = Product::with('category')->get();
        return view('pages.admin.produk', compact('categories', 'products'));
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'nama_produk' => 'required|string|max:255',
                'kategori_id' => 'required|exists:categories,id',
                'spec_labels' => 'required|array',
                'spec_labels.*' => 'required|string|max:100',
                'spec_values' => 'required|array',
                'spec_values.*' => 'required|string|max:255',
                'gambar' => 'required|image|mimes:png,jpg,jpeg|max:2048',
            ]);

            // Format spesifikasi
            $specifications = [];
            for ($i = 0; $i < count($request->spec_labels); $i++) {
                $specifications[] = [
                    'label' => $request->spec_labels[$i],
                    'value' => $request->spec_values[$i]
                ];
            }

            // Simpan gambar
            $path = $request->file('gambar')->store('products', 'public');
            $gambarPath = '/storage/' . $path;

            // Generate unique ID untuk produk
            $productId = 'prod_' . uniqid() . '_' . time();

            // Buat produk baru
            $product = Product::create([
                'id' => $productId,
                'nama_produk' => $request->nama_produk,
                'kategori_id' => $request->kategori_id,
                'deskripsi' => $specifications,
                'gambar' => $gambarPath,
            ]);

            return redirect()->back()->with('success', 'Produk berhasil ditambahkan dengan ID: ' . $product->id);
        } catch (\Exception $e) {
            \Log::error('Error creating product: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menambahkan produk: ' . $e->getMessage())->withInput();
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'nama_produk' => 'required|string|max:255',
                'kategori_id' => 'required|exists:categories,id',
                'spec_labels' => 'required|array',
                'spec_labels.*' => 'required|string|max:100',
                'spec_values' => 'required|array',
                'spec_values.*' => 'required|string|max:255',
                'gambar' => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
            ]);

            $product = Product::findOrFail($id);

            // Format spesifikasi
            $specifications = [];
            for ($i = 0; $i < count($request->spec_labels); $i++) {
                $specifications[] = [
                    'label' => $request->spec_labels[$i],
                    'value' => $request->spec_values[$i]
                ];
            }

            // Jika ada gambar baru, simpan
            if ($request->hasFile('gambar')) {
                $path = $request->file('gambar')->store('products', 'public');
                $gambarPath = '/storage/' . $path;
            } else {
                $gambarPath = $product->gambar;
            }

            // Update produk
            $product->update([
                'kategori_id' => $request->kategori_id,
                'nama_produk' => $request->nama_produk,
                'deskripsi' => $specifications,
                'gambar' => $gambarPath,
            ]);

            return redirect()->back()->with('success', 'Produk berhasil diperbarui');
        } catch (\Exception $e) {
            \Log::error('Error updating product: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal memperbarui produk: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $product = Product::findOrFail($id);
            $product->delete();
            return redirect()->back()->with('success', 'Produk berhasil dihapus');
        } catch (\Exception $e) {
            \Log::error('Error deleting product: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menghapus produk: ' . $e->getMessage());
        }
    }
}