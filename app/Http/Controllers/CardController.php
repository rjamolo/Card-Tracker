<?php

namespace App\Http\Controllers;

use App\Models\CardPrice;
use App\Services\YuyuTeiScraper;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\CardPriceHistory;
use Illuminate\Support\Facades\Log;


class CardController extends Controller
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

    public function show(CardPrice $card)
    {
        $card->load([
            'history' => function ($q) {
                $q->orderBy('created_at', 'desc');
            }
        ]);

        return view('cards.show', compact('card'));
    }

    public function create()
    {
        return view('cards.create');
    }
    public function store(Request $request, YuyuTeiScraper $scraper)
    {
        $request->validate([
            'source_url' => 'required|url',
        ]);

        $url = $request->input('source_url');

        // Check if card already exists
        $existing = CardPrice::where('source_url', $url)->first();
        if ($existing) {
            return redirect()->route('cards.show', $existing)
                ->with('info', 'This card is already being tracked.');
        }

        $data = $scraper->getCardData($url);

        $card = CardPrice::create([
            'card_name' => $data['name'] ?? 'Unknown Card',
            'source_url' => $url,
            'price' => $data['price'],
            'image_url' => $data['image_url'],
            'collected_at' => now(),
        ]);

        return redirect()->route('cards.show', $card)
            ->with('success', 'Card successfully added to tracking!');
    }
    public function refresh(YuyuTeiScraper $scraper)
    {
        $cards = CardPrice::all();
        $updatedCount = 0;

        foreach ($cards as $card) {
            try {
                $data = $scraper->getCardData($card->source_url);

                if (!empty($data['price']) && $data['price'] != $card->price) {
                    // Record history
                    CardPriceHistory::create([
                        'card_price_id' => $card->id,
                        'old_price' => $card->price,
                        'new_price' => $data['price'],
                    ]);

                    // Update current record
                    $card->update([
                        'price' => $data['price'],
                        'collected_at' => now(),
                    ]);

                    $updatedCount++;
                }
            } catch (\Exception $e) {
                Log::error("Refresh failed for {$card->id}: " . $e->getMessage());
            }
        }

        return redirect()->back()->with('status', "Refreshed $updatedCount card(s) successfully.");
    }
}
