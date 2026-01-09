<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $keyType = 'string';
    public $incrementing = false;
    protected $fillable = ['id', 'kategori_id', 'harga', 'stok', 'nama_produk', 'deskripsi', 'gambar', 'link_brosur'];

    protected $casts = [
        'deskripsi' => 'array'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'kategori_id');
    }

    /**
     * Get user's cart items
     */
    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
