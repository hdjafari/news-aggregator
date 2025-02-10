<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GuardianService
{
    

    public function fetchArticles($category = 'general')
    {
        try {
            $response = Http::get('https://content.guardianapis.com/'.$category, [
                'api-key' => config('services.guardian.key'),
            ]);

            $data = $response->json();
            // Log::info('GuardianService: API response', ['response' => $data]);

            if (isset($data['response']['results'])) {
                return $data['response']['results'];
            }

            // Log::warning('GuardianService: No results found in API response.');
            return [];
        } catch (\Exception $e) {
            // Log::error('GuardianService: Failed to fetch articles.', ['error' => $e->getMessage()]);
            return [];
        }
    }
}
