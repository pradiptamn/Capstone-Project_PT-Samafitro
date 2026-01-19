<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Promo extends Model
{
    protected $fillable = [
        'name',
        'vendor',
        'label',
        'discount',
        'periode',
        'image',
        'terms'
    ];
}