<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Category extends Model
{
    protected $keyType = 'string';
    public $incrementing = false;
    protected $fillable = ['id', 'name', 'slug'];

    public function products()
    {
        return $this->hasMany(Product::class, 'kategori_id');
    }

    protected static function boot()
    {
        parent::boot();

        // Event saat data pertama kali dibuat
        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = (string) Str::uuid();
            }
        });

        // Event saat data dibuat ATAU diperbarui
        static::saving(function ($model) {
            $model->slug = Str::slug($model->name);
        });
    }
}
