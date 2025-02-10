<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class NewsApiService
{
    public function fetchArticles($category = 'general')
    {
        try {
            $response = Http::get('https://newsapi.org/v2/top-headlines', [
                'apiKey' => config('services.newsapi.key'),
                'country' => 'us',
                'category' => $category,
            ]);

            $data = $response->json();

            if (isset($data['articles'])) {
                // Log::info('Articles fetched successfully.');
                return $data['articles'];
            }

            // Log::warning('No articles found in the response.');
            return [];
        } catch (\Exception $e) {
            // Log::error('Error fetching articles: ' . $e->getMessage());
            return [];
        }
    }
}