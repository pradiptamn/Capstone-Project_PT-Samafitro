@extends('layouts.admin')

@section('title', 'Tong Sampah Produk | Samafitro')

@section('content')
  <div class="bg-gradient-to-b from-gray-900 to-gray-800 text-white font-sans min-h-screen flex flex-col">
    <main class="flex-1 container mx-auto px-4 py-8">

      <div class="mb-8">
        <a href="{{ route('admin.produk.index') }}"
          class="text-gray-400 hover:text-white flex items-center gap-2 transition">
          <i class="fas fa-arrow-left"></i> Kembali ke Produk Aktif
        </a>
        <h2 class="text-3xl font-bold mt-4 flex items-center gap-3">
          <i class="fas fa-trash-restore text-red-500"></i> Produk Dihapus (Trash)
        </h2>
        <p class="text-gray-400 text-sm mt-1">Produk di bawah ini tidak muncul di katalog user tapi masih tersimpan di
          database.</p>
      </div>

      @if (session('success'))
        <div class="mb-6 p-4 bg-green-600/20 border border-green-600 text-green-400 rounded-lg flex items-center gap-3">
          <i class="fas fa-check-circle"></i>
          <span>{{ session('success') }}</span>
        </div>
      @endif

      <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @forelse ($products as $p)
          <div
            class="bg-gray-800 border border-red-900/30 rounded-xl overflow-hidden shadow-md opacity-80 hover:opacity-100 transition">
            <div class="h-40 w-full bg-white p-4 flex items-center justify-center grayscale">
              <img src="{{ asset('storage/' . $p->gambar) }}" class="max-h-full max-w-full object-contain">
            </div>
            <div class="p-4">
              <h4 class="text-lg font-bold text-white mb-1 line-clamp-1">{{ $p->nama_produk }}</h4>
              <span class="text-[10px] text-red-400 font-bold uppercase tracking-widest">Terhapus pada:
                {{ $p->deleted_at->format('d M Y') }}</span>

              <div class="mt-6 flex flex-col gap-2">
                {{-- Tombol Restore --}}
                <form action="{{ route('admin.produk.restore', $p->id) }}" method="POST">
                  @csrf @method('PUT')
                  <button type="submit"
                    class="w-full bg-green-600 hover:bg-green-500 text-white py-2 rounded-lg text-sm font-medium transition">
                    <i class="fas fa-undo mr-1"></i> Pulihkan Produk
                  </button>
                </form>

                {{-- Tombol Force Delete --}}
                <form action="{{ route('admin.produk.forceDelete', $p->id) }}" method="POST"
                  onsubmit="return confirm('PERINGATAN: Hapus permanen tidak bisa dibatalkan!')">
                  @csrf @method('DELETE')
                  <button type="submit"
                    class="w-full bg-transparent border border-red-600 text-red-500 hover:bg-red-600 hover:text-white py-2 rounded-lg text-sm transition">
                    Hapus Selamanya
                  </button>
                </form>
              </div>
            </div>
          </div>
        @empty
          <div class="col-span-full text-center py-20 text-gray-500">
            <i class="fas fa-trash text-5xl mb-4 opacity-20"></i>
            <p>Tong sampah kosong.</p>
          </div>
        @endforelse
      </div>
    </main>
  </div>
@endsection
