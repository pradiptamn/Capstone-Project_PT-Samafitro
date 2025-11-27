<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Promo;
use Illuminate\Http\Request;

class PromoController extends Controller
{
    public function index()
    {
        // Ambil semua data promo dari database, urut terbaru
        $promos = Promo::latest()->get();

        return view('pages.user.promo.index', compact('promos'));
    }

    public function show(Promo $promo)
    {
        // $promo adalah model yang di-binding otomatis (id)
        return view('pages.user.promo.show', compact('promo'));
    }
}
