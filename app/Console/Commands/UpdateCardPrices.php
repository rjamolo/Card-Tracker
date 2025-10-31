<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Command\Command as SymfonyCommand;
use App\Models\CardPrice;
use App\Models\CardPriceHistory;
use App\Services\YuyuTeiScraper;

class UpdateCardPrices extends Command
{
    protected $signature = 'cards:update-prices';
    protected $description = 'Fetch latest card prices from Yuyu-Tei and store history';

    public function handle()
    {
        $scraper = new YuyuTeiScraper();
        $cards = CardPrice::all();

        foreach ($cards as $card) {
            $this->info("Updating: {$card->card_name}");

            $data = $scraper->getCardData($card->source_url);
            if (!$data['price']) {
                $this->warn("❌ Failed to fetch price for {$card->card_name}");
                continue;
            }

            if ($data['price'] != $card->price) {
                // Save history
                CardPriceHistory::create([
                    'card_price_id' => $card->id,
                    'old_price' => $card->price,
                    'new_price' => $data['price'],
                ]);

                // Update main table
                $card->update([
                    'price' => $data['price'],
                    'collected_at' => now(),
                ]);

                $this->info("✅ Updated {$card->card_name}: ¥{$data['price']}");
            } else {
                $this->line("No change for {$card->card_name}");
            }
        }

        $this->info('All cards updated!');
        return SymfonyCommand::SUCCESS;
    }
}
