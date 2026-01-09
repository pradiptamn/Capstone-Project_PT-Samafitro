<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
                'harga'       => 'required|numeric|min:0',
                'stok'        => 'required|numeric|min:0', // Validasi Stok
                'spec_labels' => 'required|array',
                'spec_labels.*' => 'required|string|max:100',
                'spec_values' => 'required|array',
                'spec_values.*' => 'required|string|max:255',
                'gambar'      => 'required|image|mimes:png,jpg,jpeg|max:2048',
                'link_brosur' => 'nullable|url',
            ]);

            $specifications = [];
            for ($i = 0; $i < count($request->spec_labels); $i++) {
                $specifications[] = [
                    'label' => $request->spec_labels[$i],
                    'value' => $request->spec_values[$i]
                ];
            }

            $path = $request->file('gambar')->store('products', 'public');
            // Menghapus /storage/ di depan agar penyimpanan di DB konsisten (path asli)
            $gambarPath = $path;

            $productId = 'prod_' . uniqid() . '_' . time();

            $product = Product::create([
                'id' => $productId,
                'nama_produk' => $request->nama_produk,
                'kategori_id' => $request->kategori_id,
                'harga' => $request->harga,
                'stok' => $request->stok, // Simpan Stok
                'deskripsi' => $specifications,
                'gambar' => $gambarPath,
                'link_brosur' => $request->link_brosur,
            ]);

            return redirect()->back()->with('success', 'Produk berhasil ditambahkan');
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
                'harga'       => 'required|numeric|min:0',
                'stok'        => 'required|numeric|min:0', // Validasi Stok
                'spec_labels' => 'required|array',
                'spec_values' => 'required|array',
                'gambar'      => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
                'link_brosur' => 'nullable|url',
            ]);

            $product = Product::findOrFail($id);

            $specifications = [];
            for ($i = 0; $i < count($request->spec_labels); $i++) {
                $specifications[] = [
                    'label' => $request->spec_labels[$i],
                    'value' => $request->spec_values[$i]
                ];
            }

            if ($request->hasFile('gambar')) {
                if ($product->gambar) {
                    Storage::disk('public')->delete($product->gambar);
                }

                $path = $request->file('gambar')->store('products', 'public');
                $gambarPath = $path;
            } else {
                $gambarPath = $product->gambar;
            }

            $product->update([
                'kategori_id' => $request->kategori_id,
                'harga' => $request->harga,
                'stok' => $request->stok,
                'nama_produk' => $request->nama_produk,
                'deskripsi' => $specifications,
                'gambar' => $gambarPath,
                'link_brosur' => $request->link_brosur,
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

    // Menampilkan tong sampah
    public function trash()
    {
        $categories = Category::all();
        // onlyTrashed() hanya mengambil data yang deleted_at-nya terisi
        $products = Product::onlyTrashed()->with('category')->latest()->get();
        return view('pages.admin.products-trash', compact('categories', 'products'));
    }

    // Mengembalikan produk
    public function restore($id)
    {
        $product = Product::withTrashed()->findOrFail($id);
        $product->restore();
        return redirect()->route('admin.produk.trash')->with('success', 'Produk berhasil dipulihkan!');
    }

    // Hapus Permanen
    public function forceDelete($id)
    {
        $product = Product::withTrashed()->findOrFail($id);
        if ($product->gambar) {
            Storage::disk('public')->delete($product->gambar);
        }
        $product->forceDelete();
        return redirect()->route('admin.produk.trash')->with('success', 'Produk dihapus permanen!');
    }
}
