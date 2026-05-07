<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class Order extends Model
{
    // Matikan auto-increment karena ID kita string
    public $incrementing = false;

    // Beritahu tipe data primary key adalah string
    protected $keyType = 'string';

    protected $guarded = [];

    // Fungsi 'boot' untuk otomatis isi ID dengan UUID saat create
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = (string) Str::uuid(); // Generate UUID (contoh: a0eebc99-9c0b...)
            }
        });
    }

    public static function generateOrderNumber()
    {
        $now = Carbon::now();
        $yearMonth = $now->format('ym'); // Menghasilkan '2508' (Tahun 2 digit, Bulan 2 digit)

        // Hitung jumlah order yang sudah dibuat pada bulan dan tahun ini
        $count = self::whereYear('created_at', $now->year)
            ->whereMonth('created_at', $now->month)
            ->count();

        // Tambah 1 untuk urutan berikutnya
        $sequence = $count + 1;

        // Format menjadi 5 digit dengan awalan nol (00179)
        $formattedSequence = str_pad($sequence, 5, '0', STR_PAD_LEFT);

        return "AR-{$yearMonth}{$formattedSequence}-SBDG";
    }

    // --- RELASI ---
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function courier()
    {
        return $this->belongsTo(User::class, 'courier_id');
    }

    // Sales
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
