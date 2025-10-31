<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class YuyuTeiScraper
{
    public function getCardData(string $url): array
    {
        try {
            $response = Http::withOptions(['verify' => false])->get($url);

            if ($response->failed()) {
                throw new \Exception("Failed to fetch $url");
            }

            $html = $response->body();

            // Extract name (inside <div id="power"><h3> ... </h3>)
            preg_match('/<div[^>]*id="power"[^>]*>.*?<h3[^>]*>(.*?)<\/h3>/is', $html, $nameMatch);
            $name = isset($nameMatch[1]) ? trim(strip_tags($nameMatch[1])) : 'Unknown';

            // --- Optional: lightweight “translation” replacements ---
            $translations = [
                'パラレル' => 'Parallel',
                'プロモ' => 'Promo',
                'スペシャル' => 'Special',
                'シークレット' => 'Secret',
                'レア' => 'Rare',
            ];

            foreach ($translations as $jp => $en) {
                $name = str_replace($jp, $en, $name);
            }

            // Extract price
            preg_match('/<h4[^>]*class="[^"]*fw-bold[^"]*d-inline-block[^"]*"[^>]*>(.*?)<\/h4>/u', $html, $priceMatch);
            $priceText = isset($priceMatch[1]) ? trim(strip_tags($priceMatch[1])) : null;
            $price = $priceText ? (int) preg_replace('/[^\d]/', '', $priceText) : null;

            // Extract image URL (img.vimg)
            preg_match('/<img[^>]+class="[^"]*vimg[^"]*"[^>]+src="([^"]+)"/i', $html, $imgMatch);
            $imageUrl = $imgMatch[1] ?? null;

            return [
                'name' => $name,
                'price' => $price,
                'image_url' => $imageUrl,
            ];
        } catch (\Exception $e) {
            \Log::error("Failed to scrape $url: " . $e->getMessage());
            return [
                'name' => 'Unknown',
                'price' => null,
                'image_url' => null,
            ];
        }
    }
}
