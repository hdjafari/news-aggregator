<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class NyTimesService
{
    public function fetchArticles($category = 'general')
    {
        try {
            $response = Http::get('https://api.nytimes.com/svc/news/v3/content/all/'.$category.'.json', [
                'api-key' => config('services.nytimes.key'),
            ]);
            
            $data = $response->json();
            // Log::info('NyTimesService: API response', ['response' => $data]);

            if (isset($data['results'])) {
                return $data['results'];
            }

            // Log::warning('NyTimesService: No results found in API response.');
            return [];
        } catch (\Exception $e) {
            // Log::error('NyTimesService: Failed to fetch articles.', ['error' => $e->getMessage()]);
            return [];
        }
    }
}