<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\DashboardItem;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $items = DashboardItem::latest()->get();
        return view('pages.user.dashboard', compact('items'));
    }
}
