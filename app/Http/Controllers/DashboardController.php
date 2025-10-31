<?php

namespace App\Http\Controllers;

use App\Models\CardPrice;

class DashboardController extends Controller
{
    public function index()
    {
        $cards = CardPrice::latest('collected_at')->get();

        return view('dashboard', compact('cards'));
    }
}
