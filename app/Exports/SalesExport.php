<?php

namespace App\Exports;

use App\Models\OrderItem;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SalesExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $startDate;
    protected $endDate;

    // Variabel untuk melacak ID Order sebelumnya
    private $lastOrderId = 0;

    public function __construct($startDate, $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function collection()
    {
        return OrderItem::with('order')
            ->whereHas('order', function ($q) {
                $q->where('payment_status', 'paid')
                    ->whereBetween('created_at', [$this->startDate, $this->endDate]);
            })
            // PENTING: Urutkan berdasarkan Order ID agar item dalam satu invoice berkumpul
            ->orderBy('order_id')
            ->get();
    }

    public function map($item): array
    {
        // LOGIKA PENTING:
        // Cek apakah Item ini memiliki Order ID yang sama dengan baris sebelumnya?
        $isFirstItemOfOrder = $this->lastOrderId !== $item->order_id;

        // Simpan ID sekarang untuk pengecekan baris berikutnya
        $this->lastOrderId = $item->order_id;

        return [
            $item->created_at->format('d/m/Y H:i'),
            $item->order->order_number ?? '-',
            $item->product_name,
            $item->quantity,
            $item->price,
            $item->subtotal,

            // HANYA TAMPILKAN JIKA INI ITEM PERTAMA DALAM ORDER TERSEBUT
            // Jika tidak, isi dengan 0 (atau string kosong '' jika mau sel kosong)
            $isFirstItemOfOrder ? ($item->order->insurance_fee ?? 0) : 0,
            $isFirstItemOfOrder ? ($item->order->total_price ?? 0) : 0,
        ];
    }

    public function headings(): array
    {
        return [
            'Tanggal Transaksi',
            'No Invoice',
            'Nama Produk',
            'Qty',
            'Harga Satuan (Rp)',
            'Subtotal Barang (Rp)',
            'Asuransi Pengiriman (Rp)',
            'Total Bayar (Rp)',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
