<!DOCTYPE html>
<html>

<head>
  <title>Invoice #{{ $order->order_number }}</title>
  <style>
    /* Reset & Font */
    body {
      font-family: 'Helvetica', 'Arial', sans-serif;
      color: #333;
      font-size: 13px;
      line-height: 1.6;
      margin: 0;
      padding: 0;
    }

    /* --- HEADER (KOP SURAT) --- */
    .header-table {
      width: 100%;
      border-bottom: 3px solid #2d3748;
      padding-bottom: 10px;
      margin-bottom: 30px;
    }

    .header-logo img {
      height: 60px;
      width: auto;
    }

    .header-company {
      text-align: right;
      font-size: 11px;
      color: #555;
    }

    .company-name {
      font-size: 22px;
      font-weight: 800;
      color: #1a202c;
      margin-bottom: 5px;
      text-transform: uppercase;
    }

    /* --- INFO INVOICE --- */
    .info-table {
      width: 100%;
      margin-bottom: 30px;
    }

    .bill-to {
      width: 50%;
      vertical-align: top;
    }

    .invoice-details {
      width: 50%;
      text-align: right;
      vertical-align: top;
    }

    .title {
      font-size: 24px;
      font-weight: bold;
      color: #2d3748;
      letter-spacing: 1px;
      margin-bottom: 5px;
    }

    .invoice-number {
      font-size: 14px;
      color: #718096;
      margin-bottom: 15px;
    }

    .badge-box {
      padding: 5px 10px;
      display: inline-block;
      font-weight: bold;
      border-radius: 4px;
      border: 1px solid transparent;
    }

    /* --- TABEL BARANG --- */
    .items-table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 30px;
    }

    .items-table th {
      background-color: #2d3748;
      color: #fff;
      padding: 8px 12px;
      text-align: left;
      font-size: 11px;
      text-transform: uppercase;
    }

    .items-table td {
      padding: 10px 12px;
      border-bottom: 1px solid #edf2f7;
    }

    .items-table tr:nth-child(even) {
      background-color: #f7fafc;
    }

    /* Helper Classes */
    .text-right {
      text-align: right;
    }

    .text-center {
      text-align: center;
    }

    .font-bold {
      font-weight: bold;
    }

    .text-sm {
      font-size: 11px;
      color: #718096;
    }

    /* --- TOTAL & NOTES --- */
    .total-table {
      width: 40%;
      float: right;
      border-collapse: collapse;
    }

    .total-table td {
      padding: 5px 0;
    }

    .grand-total {
      border-top: 2px solid #2d3748;
      border-bottom: 2px solid #2d3748;
      padding: 10px 0 !important;
      font-size: 16px;
      font-weight: bold;
      color: #2d3748;
    }

    .notes {
      width: 55%;
      float: left;
      font-size: 11px;
      color: #718096;
      font-style: italic;
      background: #fffaf0;
      padding: 10px;
      border-radius: 5px;
      border: 1px solid #feeebc;
    }

    /* Style khusus untuk notes Cancelled */
    .notes-cancelled {
      background: #f7fafc;
      border-color: #cbd5e0;
      color: #718096;
    }

    /* --- STEMPEL (WATERMARK STYLE) --- */
    .stamp-container {
      position: absolute;
      top: 40%;
      left: 50%;
      transform: translate(-50%, -50%) rotate(-30deg);
      z-index: -1;
    }

    .stamp {
      font-size: 80px;
      font-weight: 900;
      text-transform: uppercase;
      border: 8px solid;
      padding: 10px 40px;
      border-radius: 20px;
      opacity: 0.15;
      letter-spacing: 10px;
      display: inline-block;
      white-space: nowrap;
    }

    .is-paid {
      color: #22c55e;
      border-color: #22c55e;
    }

    /* Hijau */
    .is-unpaid {
      color: #ef4444;
      border-color: #ef4444;
    }

    /* Merah */
    .is-cancelled {
      color: #4a5568;
      border-color: #4a5568;
    }

    /* Abu Gelap */

    /* Footer Halaman */
    .page-footer {
      position: fixed;
      bottom: 0;
      width: 100%;
      text-align: center;
      font-size: 10px;
      color: #cbd5e0;
      border-top: 1px solid #e2e8f0;
      padding-top: 10px;
    }
  </style>
</head>

<body>

  <div class="stamp-container">
    @if ($order->status == 'cancelled')
      <div class="stamp is-cancelled">DIBATALKAN</div>
    @elseif($order->payment_status == 'paid')
      <div class="stamp is-paid">LUNAS</div>
    @else
      <div class="stamp is-unpaid">UNPAID</div>
    @endif
  </div>

  <table class="header-table">
    <tr>
      <td width="30%" valign="middle" class="header-logo">
        <img src="{{ public_path('images/samafitro-bandung.png') }}" alt="Samafitro Logo">
      </td>
      <td width="70%" valign="middle" class="header-company">
        <div class="company-name">PT. SAMAFITRO</div>
        <div>Jl. Karang Tinggal No.27, Cipedes, Kec. Sukajadi</div>
        <div>Kota Bandung, Jawa Barat 40162</div>
        <div>Telepon: (022) 2033666 | Email: info@samafitro.co.id</div>
      </td>
    </tr>
  </table>

  <table class="info-table">
    <tr>
      <td class="bill-to">
        <div class="text-sm font-bold uppercase mb-1">Ditagihkan Kepada:</div>
        <div style="font-size: 14px; font-weight: bold;">{{ $order->user->name }}</div>
        <div>{{ $order->shipping_address }}</div>
        <div>Telp: {{ $order->shipping_phone }}</div>
        <div style="margin-top: 5px; color: #4a5568;">{{ $order->user->email }}</div>
      </td>
      <td class="invoice-details">
        <div class="title">INVOICE</div>
        <div class="invoice-number">#{{ $order->order_number }}</div>

        <table width="100%" style="margin-top: 10px;">
          <tr>
            <td class="text-right text-sm">Tanggal Order:</td>
            <td class="text-right font-bold">{{ $order->created_at->format('d M Y') }}</td>
          </tr>
          <tr>
            <td class="text-right text-sm">Jatuh Tempo:</td>
            <td class="text-right font-bold">{{ $order->created_at->addDays(1)->format('d M Y') }}</td>
          </tr>
          <tr>
            <td class="text-right text-sm">Status:</td>
            <td class="text-right">
              {{-- LOGIKA BADGE STATUS --}}
              @if ($order->status == 'cancelled')
                <span class="badge-box" style="color: #4a5568; background: #e2e8f0; border-color: #cbd5e0;">
                  DIBATALKAN
                </span>
              @elseif($order->payment_status == 'paid')
                <span class="badge-box" style="color: #22543d; background: #c6f6d5;">
                  LUNAS
                </span>
              @else
                <span class="badge-box" style="color: #742a2a; background: #fed7d7;">
                  UNPAID
                </span>
              @endif
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>

  <table class="items-table">
    <thead>
      <tr>
        <th width="5%">No</th>
        <th width="45%">Deskripsi Produk</th>
        <th width="10%" class="text-center">Qty</th>
        <th width="20%" class="text-right">Harga Satuan</th>
        <th width="20%" class="text-right">Total</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($order->items as $index => $item)
        <tr>
          <td class="text-center">{{ $index + 1 }}</td>
          <td>
            <div style="font-weight: bold;">{{ $item->product_name }}</div>
            <div class="text-sm">Kode: {{ $item->product_id }}</div>
          </td>
          <td class="text-center">{{ $item->quantity }}</td>
          <td class="text-right">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
          <td class="text-right font-bold">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
        </tr>
      @endforeach
    </tbody>
  </table>

  <div style="width: 100%; display: inline-block;">

    {{-- LOGIKA CATATAN (NOTES) --}}
    @if ($order->status == 'cancelled')
      <div class="notes notes-cancelled">
        <strong>Status Pesanan: DIBATALKAN</strong><br>
        Invoice ini tidak berlaku sebagai tagihan pembayaran. Dokumen ini hanya sebagai arsip bahwa transaksi telah
        dibatalkan oleh pengguna atau sistem.
      </div>
    @elseif ($order->payment_status == 'unpaid')
      <div class="notes">
        <strong>Catatan Pembayaran:</strong><br>
        Silakan lakukan pembayaran pada aplikasi sebelum tanggal jatuh tempo.
        Pesanan akan diproses otomatis setelah pembayaran dikonfirmasi.
      </div>
    @else
      <div class="notes" style="background: #f0fff4; border-color: #c6f6d5; color: #2f855a;">
        <strong>Terima Kasih!</strong><br>
        Pembayaran telah diterima dengan baik. Terima kasih atas kepercayaan Anda berbelanja di PT. Samafitro. Simpan
        dokumen ini sebagai bukti pembayaran yang sah.
      </div>
    @endif

    <table class="total-table">
      <tr>
        <td class="text-right text-sm">Subtotal:</td>
        <td class="text-right">Rp {{ number_format($productRevenue ?? $order->subtotal, 0, ',', '.') }}</td>
      </tr>
      @if ($order->insurance_fee > 0)
        <tr>
          <td class="text-right text-sm">Biaya Asuransi Pengiriman:</td>
          <td class="text-right">Rp {{ number_format($insuranceRevenue ?? $order->insurance_fee, 0, ',', '.') }}</td>
        </tr>
      @endif
      <tr>
        <td class="grand-total text-right">TOTAL:</td>
        <td class="grand-total text-right">Rp {{ number_format($totalRevenue ?? $order->total_price, 0, ',', '.') }}
        </td>
      </tr>
    </table>
  </div>

  <div class="page-footer">
    Dokumen ini diterbitkan secara komputerisasi dan sah tanpa tanda tangan basah. | Dicetak pada:
    {{ now()->format('d/m/Y H:i') }}
  </div>

</body>

</html>
