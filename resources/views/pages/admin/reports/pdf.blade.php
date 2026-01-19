<!DOCTYPE html>
<html>

<head>
  <title>Laporan Penjualan</title>
  <style>
    /* Base Styles */
    body {
      font-family: sans-serif;
      font-size: 12px;
      color: #333;
    }

    /* Header */
    .header {
      width: 100%;
      border-bottom: 2px solid #0056b3;
      padding-bottom: 10px;
      margin-bottom: 20px;
    }

    .header-logo img {
      height: 50px;
    }

    .header-content {
      text-align: right;
    }

    .company-name {
      font-size: 20px;
      font-weight: bold;
      color: #0056b3;
      margin: 0;
    }

    .company-address {
      font-size: 10px;
      color: #555;
      margin: 2px 0;
    }

    /* Title */
    .report-title {
      text-align: center;
      margin-bottom: 20px;
    }

    .report-title h2 {
      margin: 0;
      text-decoration: underline;
    }

    .report-period {
      font-size: 11px;
      color: #666;
      margin-top: 5px;
    }

    /* Summary Box (Kotak Rincian Keuangan) */
    .summary-box {
      width: 100%;
      margin-bottom: 20px;
      border: 1px solid #ddd;
      background-color: #f8f9fa;
      padding: 15px;
    }

    .summary-table {
      width: 100%;
      border-collapse: collapse;
    }

    .summary-label {
      font-weight: bold;
      color: #555;
      width: 60%;
      text-align: right;
      padding-right: 20px;
    }

    .summary-value {
      font-weight: bold;
      color: #333;
      text-align: right;
      width: 40%;
    }

    .total-row td {
      border-top: 1px solid #aaa;
      padding-top: 5px;
      color: #0056b3;
      font-size: 14px;
    }

    /* Data Table */
    .data-table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 30px;
    }

    .data-table th,
    .data-table td {
      border: 1px solid #ddd;
      padding: 6px 8px;
      text-align: left;
    }

    .data-table th {
      background-color: #0056b3;
      color: white;
      font-weight: bold;
      text-transform: uppercase;
      font-size: 10px;
    }

    .data-table tr:nth-child(even) {
      background-color: #f2f2f2;
    }

    .text-right {
      text-align: right;
    }

    .text-center {
      text-align: center;
    }

    /* Footer */
    .footer {
      position: fixed;
      bottom: 0;
      width: 100%;
      font-size: 9px;
      color: #999;
      text-align: center;
      border-top: 1px solid #eee;
      padding-top: 10px;
    }
  </style>
</head>

<body>

  <table class="header">
    <tr>
      <td class="header-logo">
        <img src="{{ public_path('images/samafitro-bandung.png') }}" alt="Logo">
      </td>
      <td class="header-content">
        <h1 class="company-name">PT. SAMAFITRO</h1>
        <p class="company-address">Jl. Karang Tinggal No.27, Bandung, Jawa Barat<br>Telp: (022) 2033666</p>
      </td>
    </tr>
  </table>

  <div class="report-title">
    <h2>LAPORAN PENJUALAN</h2>
    <div class="report-period">
      Periode: {{ \Carbon\Carbon::parse($startDate)->translatedFormat('d F Y') }}
      s/d {{ \Carbon\Carbon::parse($endDate)->translatedFormat('d F Y') }}
    </div>
  </div>

  <div class="summary-box">
    <table class="summary-table">
      <tr>
        <td class="summary-label">Total Transaksi (Item Terjual):</td>
        <td class="summary-value">{{ $totalItems }} Unit</td>
      </tr>
      <tr>
        <td class="summary-label">Total Penjualan Barang (Subtotal):</td>
        <td class="summary-value">Rp {{ number_format($productRevenue, 0, ',', '.') }}</td>
      </tr>
      <tr>
        <td class="summary-label">Total Asuransi:</td>
        <td class="summary-value">Rp {{ number_format($insuranceRevenue, 0, ',', '.') }}</td>
      </tr>
      <tr class="total-row">
        <td class="summary-label">TOTAL PENDAPATAN BERSIH:</td>
        <td class="summary-value">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</td>
      </tr>
    </table>
  </div>

  <table class="data-table">
    <thead>
      <tr>
        <th width="5%">No</th>
        <th width="15%">Tanggal</th>
        <th width="15%">No Invoice</th>
        <th width="35%">Nama Produk</th>
        <th width="10%" class="text-center">Qty</th>
        <th width="20%" class="text-right">Subtotal Produk</th>
      </tr>
    </thead>
    <tbody>
      @php $no = 1; @endphp
      @foreach ($data as $item)
        <tr>
          <td class="text-center">{{ $no++ }}</td>
          <td>{{ $item->created_at->format('d/m/Y') }}</td>
          <td>{{ $item->order->order_number ?? '-' }}</td>
          <td>{{ $item->product_name }}</td>
          <td class="text-center">{{ $item->quantity }}</td>
          <td class="text-right">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
        </tr>
      @endforeach

      <tr style="font-weight: bold; background-color: #e9ecef;">
        <td colspan="5" class="text-right">Total Penjualan Barang</td>
        <td class="text-right">Rp {{ number_format($productRevenue, 0, ',', '.') }}</td>
      </tr>
    </tbody>
  </table>

  <div style="margin-top: 10px; font-size: 10px; color: #666; font-style: italic;">
    * Nilai di tabel di atas adalah harga barang sebelum asuransi. Total Pendapatan (termasuk asuransi) dapat dilihat
    pada kotak rincian di atas.
  </div>

  <div class="footer">
    Dicetak otomatis oleh Sistem Admin Samafitro pada {{ now()->format('d F Y H:i') }}
  </div>

</body>

</html>
