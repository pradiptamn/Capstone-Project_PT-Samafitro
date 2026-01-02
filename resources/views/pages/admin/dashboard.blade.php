@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')
  @php
    $role = auth()->user()->role;
    $prefix = $role;
  @endphp

  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

  <div class="container mx-auto px-4 py-8 text-white">

    <div class="flex flex-col xl:flex-row justify-between items-start xl:items-center mb-8 gap-4">
      <div>
        <h1 class="text-3xl font-bold">Dashboard Overview</h1>
        <p class="text-gray-400 text-sm">Analisa performa penjualan dan kelola konten.</p>
      </div>

      <div class="flex flex-col md:flex-row gap-3 w-full md:w-auto items-end">
        {{-- FILTER TOOLS --}}
        <div class="flex bg-gray-800 p-1.5 rounded-lg border border-gray-700 gap-2 items-center flex-wrap">
          <div class="relative">
            <select id="trend_type"
              class="bg-gray-900 text-white text-xs border border-gray-600 rounded px-2 py-2.5 focus:ring-blue-500 cursor-pointer outline-none">
              <option value="daily" {{ $trendType == 'daily' ? 'selected' : '' }}>Tren Harian</option>
              <option value="weekly" {{ $trendType == 'weekly' ? 'selected' : '' }}>Tren Mingguan</option>
              <option value="monthly" {{ $trendType == 'monthly' ? 'selected' : '' }}>Tren Bulanan</option>
            </select>
          </div>

          <div class="h-6 w-[1px] bg-gray-700 hidden md:block"></div>

          <div class="relative">
            <span class="absolute left-2 top-1.5 text-[10px] text-gray-500 uppercase font-bold">Dari</span>
            <input type="date" id="start_date" value="{{ $startDate }}"
              class="bg-gray-900 text-white text-sm border border-gray-600 rounded px-2 pt-4 pb-1 w-32 focus:ring-blue-500 outline-none cursor-pointer">
          </div>
          <div class="text-gray-500">-</div>
          <div class="relative">
            <span class="absolute left-2 top-1.5 text-[10px] text-gray-500 uppercase font-bold">S/d</span>
            <input type="date" id="end_date" value="{{ $endDate }}"
              class="bg-gray-900 text-white text-sm border border-gray-600 rounded px-2 pt-4 pb-1 w-32 focus:ring-blue-500 outline-none cursor-pointer">
          </div>
          <button onclick="resetFilter()" class="bg-gray-700 hover:bg-gray-600 text-gray-300 p-2.5 rounded transition"
            title="Reset Filter">
            <i class="fas fa-undo-alt"></i>
          </button>
        </div>

        <div class="flex gap-2">
          <a id="btn-export-pdf" href="{{ route($prefix . '.export.pdf', request()->all()) }}"
            class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm flex items-center justify-center gap-2 transition shadow-lg shadow-red-900/20">
            <i class="fas fa-file-pdf"></i> PDF
          </a>
          <a id="btn-export-excel" href="{{ route($prefix . '.export.excel', request()->all()) }}"
            class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm flex items-center justify-center gap-2 transition shadow-lg shadow-green-900/20">
            <i class="fas fa-file-excel"></i> Excel
          </a>
        </div>
      </div>
    </div>

    {{-- STAT CARDS (3 Utama Tetap Sama) --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
      <div class="bg-gray-800 p-6 rounded-xl border border-gray-700 shadow-lg relative overflow-hidden">
        <div class="flex justify-between items-start">
          <div>
            <p class="text-gray-400 text-xs font-bold uppercase mb-1">Pendapatan (Periode Ini)</p>
            <p class="text-3xl font-bold text-green-400" id="stat-filtered-revenue">Rp
              {{ number_format($filteredRevenue, 0, ',', '.') }}</p>
            <div class="mt-4 pt-4 border-t border-gray-700">
              <p class="text-gray-500 text-[10px] uppercase">Total Keseluruhan (All Time)</p>
              <p class="text-sm font-semibold text-gray-300" id="stat-global-revenue">Rp
                {{ number_format($globalRevenue, 0, ',', '.') }}</p>
            </div>
          </div>
          <div class="bg-green-500/20 p-3 rounded-lg text-green-500"><i class="fas fa-wallet text-2xl"></i></div>
        </div>
      </div>
      <div class="bg-gray-800 p-6 rounded-xl border border-gray-700 shadow-lg flex items-center justify-between">
        <div>
          <p class="text-gray-400 text-xs font-bold uppercase">Total Order</p>
          <p class="text-3xl font-bold text-white mt-1" id="stat-total-orders">{{ $totalOrders }}</p>
        </div>
        <div class="bg-blue-500/20 p-3 rounded-lg text-blue-500"><i class="fas fa-shopping-cart text-2xl"></i></div>
      </div>
      <div class="bg-gray-800 p-6 rounded-xl border border-gray-700 shadow-lg flex items-center justify-between">
        <div>
          <p class="text-gray-400 text-xs font-bold uppercase">Perlu Proses</p>
          <p class="text-3xl font-bold text-yellow-400 mt-1" id="stat-pending-orders">{{ $pendingOrders }}</p>
        </div>
        <div class="bg-yellow-500/20 p-3 rounded-lg text-yellow-500"><i class="fas fa-bell text-2xl"></i></div>
      </div>
    </div>

    {{-- MAIN CHART --}}
    <div class="bg-gray-800 p-6 rounded-xl border border-gray-700 shadow-lg mb-8">
      <h3 class="font-bold mb-6 text-green-400 flex items-center" id="revenue-chart-title">
        <i class="fas fa-chart-line mr-2"></i> Tren Pendapatan
        {{ $trendType == 'daily' ? 'Harian' : ($trendType == 'weekly' ? 'Mingguan' : 'Bulanan') }}
      </h3>
      <div class="h-80"><canvas id="revenueTrendChart"></canvas></div>
    </div>

    {{-- TOP SELLING & PIE CHART --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-10">
      <div class="lg:col-span-2 bg-gray-800 p-6 rounded-xl border border-gray-700 shadow-lg">
        <h3 class="font-bold mb-4 text-blue-400"><i class="fas fa-fire mr-2"></i>Produk Terlaris</h3>
        <div class="h-64"><canvas id="barChart"></canvas></div>
      </div>
      <div class="bg-gray-800 p-6 rounded-xl border border-gray-700 shadow-lg">
        <h3 class="font-bold mb-4 text-purple-400"><i class="fas fa-chart-pie mr-2"></i>Distribusi Kategori</h3>
        <div class="h-48 flex justify-center"><canvas id="pieChart"></canvas></div>
      </div>
    </div>

    {{-- PERFORMA SALES CHART --}}
    <div class="bg-gray-800 p-6 rounded-xl border border-gray-700 shadow-lg mb-10">
      <div class="flex justify-between items-center mb-6">
        <h3 class="font-bold text-yellow-400 flex items-center">
          <i class="fas fa-user-tie mr-2"></i> Performa Penjualan Per Sales
        </h3>
        <span class="text-[10px] text-gray-500 italic uppercase">Berdasarkan Total Nilai Transaksi</span>
      </div>
      <div class="h-80">
        <canvas id="salesChart"></canvas>
      </div>
    </div>

    {{-- TABLES (RESTOCK & DEADSTOCK) --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-12">
      {{-- TABEL PRIORITAS RESTOCK --}}
      <div class="bg-gray-800 rounded-xl border border-gray-700 shadow-lg overflow-hidden">
        <div class="p-4 border-b border-gray-700 bg-gray-800">
          <h3 class="font-bold text-white">📋 Tabel Prioritas Restock</h3>
        </div>
        <div class="overflow-x-auto max-h-64">
          <table class="w-full text-left text-sm text-gray-400">
            <thead class="bg-gray-900 text-gray-200 sticky top-0">
              <tr>
                <th class="px-4 py-2">Produk</th>
                <th class="px-4 py-2 text-center">Terjual</th>
                <th class="px-4 py-2 text-center">Sisa Stok</th> {{-- Tambah Kolom --}}
                <th class="px-4 py-2">Prioritas</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-700" id="table-restock-body">
              @foreach ($restockPriority as $item)
                <tr class="hover:bg-gray-700">
                  <td class="px-4 py-2 text-white">{{ $item->nama_produk }}</td>
                  <td class="px-4 py-2 font-bold text-center">{{ $item->total_sold }}</td>
                  <td
                    class="px-4 py-2 text-center font-bold {{ $item->current_stock <= 3 ? 'text-red-500' : 'text-gray-300' }}">
                    {{ $item->current_stock }}
                  </td>
                  <td class="px-4 py-2">
                    <span
                      class="text-[10px] px-2 py-1 rounded {{ $item->priority == 'Sangat Tinggi' ? 'bg-red-900 text-red-300' : ($item->priority == 'Tinggi' ? 'bg-yellow-900 text-yellow-300' : 'bg-green-900 text-green-300') }}">
                      {{ $item->priority }}
                    </span>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>

      {{-- TABEL DEAD STOCK --}}
      <div class="bg-gray-800 rounded-xl border border-gray-700 shadow-lg overflow-hidden flex flex-col">
        <div class="p-4 border-b border-gray-700 bg-gray-800 flex justify-between items-center">
          <h3 class="font-bold text-red-400 flex items-center"><i class="fas fa-box-open mr-2"></i> Produk Tidak Laku
            (Dead Stock)</h3>
          <span class="text-[10px] text-gray-500 bg-gray-900 px-2 py-1 rounded border border-gray-700 italic">Penjualan:
            0
            Unit</span>
        </div>
        <div class="overflow-y-auto max-h-64 flex-1">
          <table class="w-full text-left text-sm text-gray-400">
            <thead class="bg-gray-900 text-gray-200 sticky top-0 z-10">
              <tr>
                <th class="px-4 py-2">Nama Produk</th>
                <th class="px-4 py-2 text-center">Sisa Stok</th> {{-- Tambah Kolom --}}
                <th class="px-4 py-2 text-right">Harga</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-700" id="table-deadstock-body">
              @forelse ($deadStock as $item)
                <tr class="hover:bg-gray-750 group transition">
                  <td class="px-4 py-3 text-white group-hover:text-red-300 transition">{{ $item->nama_produk }}</td>
                  <td class="px-4 py-3 text-center font-mono text-gray-300">{{ $item->stok }}</td>
                  <td class="px-4 py-3 text-right font-mono text-gray-500">
                    Rp {{ number_format($item->harga, 0, ',', '.') }}
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="3" class="px-4 py-8 text-center text-gray-500">
                    <i class="fas fa-check-circle text-green-500 text-2xl mb-2 block"></i>Semua produk laku terjual.
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>

    {{-- KELOLA KONTEN DASHBOARD (Tetap Sama) --}}
    @if (Auth::user()->role === 'admin')
      <div class="border-t border-gray-700 my-10 pt-10">
        <h2 class="text-2xl font-bold mb-6 text-white flex items-center gap-2">
          <i class="fas fa-edit text-yellow-500"></i> Kelola Konten Dashboard
        </h2>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
          <div class="lg:col-span-1">
            <div class="bg-gray-800 p-6 rounded-xl border border-gray-700 shadow-lg sticky top-6">
              <h3 class="text-lg font-semibold text-white mb-4">Tambah Konten Baru</h3>
              <form action="{{ route('admin.dashboard.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="space-y-4">
                  <div>
                    <label class="block text-sm text-gray-400 mb-1">Judul</label>
                    <input type="text" name="judul"
                      class="w-full bg-gray-900 border border-gray-600 rounded-lg p-2.5 text-white focus:border-blue-500 outline-none"
                      required>
                  </div>
                  <div>
                    <label class="block text-sm text-gray-400 mb-1">Deskripsi</label>
                    <textarea name="deskripsi" rows="3"
                      class="w-full bg-gray-900 border border-gray-600 rounded-lg p-2.5 text-white focus:border-blue-500 outline-none"
                      required></textarea>
                  </div>
                  <div>
                    <label class="block text-sm text-gray-400 mb-1">Gambar</label>
                    <input type="file" name="gambar"
                      class="block w-full text-sm text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:bg-gray-700 file:text-blue-400 hover:file:bg-gray-600 cursor-pointer">
                  </div>
                  <button type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-500 text-white font-bold py-2.5 rounded-lg shadow-lg transition">Simpan
                    Konten</button>
                </div>
              </form>
            </div>
          </div>

          <div class="lg:col-span-2">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
              @forelse ($dashboardItems as $item)
                <div
                  class="bg-gray-800 rounded-xl overflow-hidden border border-gray-700 shadow-md group hover:border-gray-500 transition relative">
                  @if ($item->gambar)
                    <div class="h-40 overflow-hidden">
                      <img src="{{ asset('storage/' . $item->gambar) }}"
                        class="w-full h-full object-cover transform group-hover:scale-105 transition duration-500">
                    </div>
                  @endif
                  <div class="p-5">
                    <h3 class="font-bold text-white text-lg mb-2">{{ $item->judul }}</h3>
                    <p class="text-gray-400 text-sm line-clamp-3">{{ $item->deskripsi }}</p>
                    <p class="text-[10px] text-gray-500 mt-4">{{ $item->created_at->diffForHumans() }}</p>
                  </div>
                  <form action="{{ route('admin.dashboard.destroy', $item->id) }}" method="POST"
                    class="absolute top-3 right-3 opacity-0 group-hover:opacity-100 transition duration-300">
                    @csrf @method('DELETE')
                    <button onclick="return confirm('Hapus konten ini?')"
                      class="bg-red-600 text-white p-2 rounded-full shadow-lg hover:bg-red-700 transition">
                      <i class="fas fa-trash-alt"></i>
                    </button>
                  </form>
                </div>
              @empty
                <div class="col-span-full text-center py-10 bg-gray-800 rounded-xl border border-gray-700 border-dashed">
                  <p class="text-gray-500 italic">Belum ada konten tambahan.</p>
                </div>
              @endforelse
            </div>
          </div>
        </div>
      </div>
    @endif

  </div>

  {{-- SCRIPT JAVASCRIPT --}}
  <script>
    let revenueChart, barChart, pieChart, salesChart;

    document.addEventListener('DOMContentLoaded', function() {
      initCharts();
      document.getElementById('start_date').addEventListener('change', updateDashboard);
      document.getElementById('end_date').addEventListener('change', updateDashboard);
      document.getElementById('trend_type').addEventListener('change', updateDashboard);
    });

    function initCharts() {
      // 1. Revenue Chart
      const revCtx = document.getElementById('revenueTrendChart');
      revenueChart = new Chart(revCtx, {
        type: 'line',
        data: {
          labels: {!! json_encode($revenueTrend->pluck('label')) !!},
          datasets: [{
            label: 'Pendapatan (Rp)',
            data: {!! json_encode($revenueTrend->pluck('revenue')) !!},
            borderColor: '#10b981',
            backgroundColor: 'rgba(16, 185, 129, 0.1)',
            borderWidth: 3,
            fill: true,
            tension: 0.4,
            pointRadius: 4,
            pointHoverRadius: 6
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          scales: {
            y: {
              beginAtZero: true,
              grid: {
                color: '#374151'
              },
              ticks: {
                color: '#9ca3af'
              }
            },
            x: {
              grid: {
                display: false
              },
              ticks: {
                color: '#9ca3af'
              }
            }
          },
          plugins: {
            legend: {
              display: false
            },
            tooltip: {
              callbacks: {
                label: function(context) {
                  return 'Pendapatan: Rp ' + new Intl.NumberFormat('id-ID').format(context.raw);
                }
              }
            }
          }
        }
      });

      // 2. Bar Chart
      const barCtx = document.getElementById('barChart');
      barChart = new Chart(barCtx, {
        type: 'bar',
        data: {
          labels: {!! json_encode($topSelling->pluck('product_name')) !!},
          datasets: [{
            label: 'Unit Terjual',
            data: {!! json_encode($topSelling->pluck('total_sold')) !!},
            backgroundColor: '#3b82f6',
            borderRadius: 6
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          scales: {
            y: {
              grid: {
                color: '#374151'
              },
              ticks: {
                color: '#9ca3af'
              }
            },
            x: {
              grid: {
                display: false
              },
              ticks: {
                color: '#9ca3af'
              }
            }
          },
          plugins: {
            legend: {
              display: false
            }
          }
        }
      });

      // 3. Pie Chart
      const pieCtx = document.getElementById('pieChart');
      pieChart = new Chart(pieCtx, {
        type: 'doughnut',
        data: {
          labels: {!! json_encode($categoryDistribution->pluck('name')) !!},
          datasets: [{
            data: {!! json_encode($categoryDistribution->pluck('total')) !!},
            backgroundColor: ['#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#3b82f6', '#06b6d4'],
            borderWidth: 0
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: {
              position: 'right',
              labels: {
                color: '#fff',
                font: {
                  size: 10
                }
              }
            }
          }
        }
      });

      // 4. Sales Performance Chart (Horizontal Bar)
      const salesCtx = document.getElementById('salesChart');
      salesChart = new Chart(salesCtx, {
        type: 'bar',
        data: {
          labels: {!! json_encode($salesPerformance->pluck('name')) !!},
          datasets: [{
            label: 'Total Penjualan (Rp)',
            data: {!! json_encode($salesPerformance->pluck('total_revenue')) !!},
            backgroundColor: '#f59e0b',
            borderRadius: 4,
            // indexAxis: 'y' <--- HAPUS DARI SINI
          }]
        },
        options: {
          indexAxis: 'y', // <--- PINDAHKAN KE SINI (Level Options)
          responsive: true,
          maintainAspectRatio: false,
          scales: {
            x: {
              beginAtZero: true,
              grid: {
                color: '#374151'
              },
              ticks: {
                color: '#9ca3af'
              }
            },
            y: {
              grid: {
                display: false
              },
              ticks: {
                color: '#fff',
                font: {
                  weight: 'bold'
                }
              }
            }
          },
          plugins: {
            legend: {
              display: false
            },
            tooltip: {
              callbacks: {
                label: function(context) {
                  return ' Omzet: Rp ' + new Intl.NumberFormat('id-ID').format(context.raw);
                }
              }
            }
          }
        }
      });
    }

    function updateDashboard() {
      const startDate = document.getElementById('start_date').value;
      const endDate = document.getElementById('end_date').value;
      const trendType = document.getElementById('trend_type').value;

      const titleType = trendType === 'daily' ? 'Harian' : (trendType === 'weekly' ? 'Mingguan' : 'Bulanan');
      document.getElementById('revenue-chart-title').innerHTML =
        `<i class="fas fa-chart-line mr-2"></i> Tren Pendapatan ${titleType}`;

      const exportParams = `?start_date=${startDate}&end_date=${endDate}&trend_type=${trendType}`;
      document.getElementById('btn-export-pdf').href = `{{ route($prefix . '.export.pdf') }}${exportParams}`;
      document.getElementById('btn-export-excel').href = `{{ route($prefix . '.export.excel') }}${exportParams}`;

      fetch(`{{ route($prefix . '.dashboard') }}${exportParams}`, {
          headers: {
            'X-Requested-With': 'XMLHttpRequest'
          }
        })
        .then(response => response.json())
        .then(data => {
          document.getElementById('stat-filtered-revenue').innerText = 'Rp ' + data.stats.filteredRevenue;
          document.getElementById('stat-global-revenue').innerText = 'Rp ' + data.stats.globalRevenue;
          document.getElementById('stat-total-orders').innerText = data.stats.totalOrders;
          document.getElementById('stat-pending-orders').innerText = data.stats.pendingOrders;

          updateChartData(revenueChart, data.charts.revenue.labels, data.charts.revenue.data);
          updateChartData(barChart, data.charts.topSelling.labels, data.charts.topSelling.data);
          updateChartData(pieChart, data.charts.pie.labels, data.charts.pie.data);
          updateChartData(salesChart, data.charts.salesPerformance.labels, data.charts.salesPerformance.data);

          updateTableRestock(data.table);
          updateTableDeadStock(data.charts.deadStock_detail);
        })
        .catch(error => console.error('Error:', error));
    }

    function updateChartData(chart, labels, data) {
      chart.data.labels = labels;
      chart.data.datasets[0].data = data;
      chart.update();
    }

    function updateTableDeadStock(items) {
      const tbody = document.getElementById('table-deadstock-body');
      tbody.innerHTML = '';
      if (!items || items.length === 0) {
        tbody.innerHTML =
          '<tr><td colspan="3" class="px-4 py-8 text-center text-gray-500"><i class="fas fa-check-circle text-green-500 text-2xl mb-2 block"></i>Semua produk laku terjual.</td></tr>';
        return;
      }
      items.forEach(item => {
        tbody.innerHTML += `
            <tr class="hover:bg-gray-750 group transition">
                <td class="px-4 py-3 text-white group-hover:text-red-300 transition">${item.nama_produk}</td>
                <td class="px-4 py-3 text-center font-mono text-gray-300">${item.stok}</td> {{-- Data Stok --}}
                <td class="px-4 py-3 text-right font-mono text-gray-500">Rp ${new Intl.NumberFormat('id-ID').format(item.harga)}</td>
            </tr>`;
      });
    }

    function updateTableRestock(items) {
      const tbody = document.getElementById('table-restock-body');
      tbody.innerHTML = '';
      if (!items || items.length === 0) {
        tbody.innerHTML =
          '<tr><td colspan="4" class="px-4 py-8 text-center text-gray-500 italic">Semua stok masih aman.</td></tr>';
        return;
      }
      items.forEach(item => {
        const priorityClass = item.priority === 'Sangat Tinggi' ? 'bg-red-900 text-red-300' : (item.priority ===
          'Tinggi' ? 'bg-yellow-900 text-yellow-300' : 'bg-green-900 text-green-300');
        const stockClass = item.current_stock <= 3 ? 'text-red-500' : 'text-gray-300';

        tbody.innerHTML += `
        <tr class="hover:bg-gray-750 transition">
            <td class="px-4 py-3 text-white">${item.nama_produk || item.product_name}</td>
            <td class="px-4 py-3 text-center font-bold text-blue-400">${item.total_sold || 0}</td>
            <td class="px-4 py-3 text-center font-bold ${stockClass}">${item.current_stock}</td>
            <td class="px-4 py-3"><span class="text-[10px] px-2 py-1 rounded ${priorityClass}">${item.priority}</span></td>
        </tr>`;
      });
    }

    function resetFilter() {
      const now = new Date();
      const startOfMonth = new Date(now.getFullYear(), now.getMonth(), 1);
      const endOfMonth = new Date(now.getFullYear(), now.getMonth() + 1, 0);
      const formatDate = (d) => {
        const year = d.getFullYear();
        const month = String(d.getMonth() + 1).padStart(2, '0');
        const day = String(d.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}`;
      };
      document.getElementById('start_date').value = formatDate(startOfMonth);
      document.getElementById('end_date').value = formatDate(endOfMonth);
      document.getElementById('trend_type').value = 'daily';
      updateDashboard();
    }
  </script>
@endsection
