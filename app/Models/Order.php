<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
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
