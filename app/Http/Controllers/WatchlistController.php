<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CardPrice;

class WatchlistController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->get('q');

        $cards = CardPrice::query()
            ->when($q, function ($query, $q) {
                $query->where('card_name', 'like', "%{$q}%")
                    ->orWhere('price', 'like', "%{$q}%");
            })
            ->orderByDesc('collected_at')
            ->get();

        return view('watchlist.index', compact('cards', 'q'));
    }
}
