<?php

namespace App\Jobs;

use App\Models\Article;
use App\Models\Category;
use App\Services\GuardianService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class FetchGuardianArticlesJob implements ShouldQueue
{
    protected $category;

    public function __construct($category)
    {
        $this->category = $category;
    }

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(GuardianService $guardianService)
    {
        // Log::info('FetchGuardianArticlesJob: Job started.');
        try {
            $articles = $guardianService->fetchArticles($this->category);
            // Log::info('FetchGuardianArticlesJob: Articles fetched.', ['articles' => $articles]);

            foreach ($articles as $articleData) {
            $category = Category::firstOrCreate(['name' => $this->category]);

            $article = Article::updateOrCreate(
                ['url' => $articleData['webUrl']],
                [
                'title' => $articleData['webTitle'],
                'content' => $articleData['webTitle'], // Guardian API does not provide content in the sample
                'author' => 'Unknown', // Guardian API does not provide author in the sample
                'source' => 'The Guardian',
                'published_at' => Carbon::parse($articleData['webPublicationDate'])->toDateTimeString(),
                ]
            );

            $article->categories()->syncWithoutDetaching([$category->id]);
            }
            // Log::info('FetchGuardianArticlesJob: Articles fetched and stored successfully.');
        } catch (\Exception $e) {
            // Log::error('FetchGuardianArticlesJob: Failed to fetch articles.', ['error' => $e->getMessage()]);
        }
        // Log::info('FetchGuardianArticlesJob: Job ended.');
    }
}
