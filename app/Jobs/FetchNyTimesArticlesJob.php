<?php

namespace App\Jobs;

use App\Models\Article;
use App\Models\Category;
use App\Services\NyTimesService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class FetchNyTimesArticlesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $category;

    public function __construct($category)
    {
        $this->category = $category;
    }

    public function handle(NyTimesService $nyTimesService)
    {
        // Log::info('FetchNyTimesArticlesJob: Job started.');
        try {
            $articles = $nyTimesService->fetchArticles($this->category);
            // Log::info('FetchNyTimesArticlesJob: Articles fetched.', ['articles' => $articles]);

            foreach ($articles as $articleData) {
            $category = Category::firstOrCreate(['name' => $this->category]);

            $article = Article::updateOrCreate(
                ['url' => $articleData['url']],
                [
                'title' => $articleData['title'],
                'content' => $articleData['abstract'],
                'author' => $articleData['byline'],
                'source' => 'New York Times',
                'published_at' => Carbon::parse($articleData['published_date'])->toDateTimeString(),
                ]
            );

            $article->categories()->syncWithoutDetaching([$category->id]);
            }
            // Log::info('FetchNyTimesArticlesJob: Articles fetched and stored successfully.');
        } catch (\Exception $e) {
            // Log::error('FetchNyTimesArticlesJob: Failed to fetch articles.', ['error' => $e->getMessage()]);
        }
        // Log::info('FetchNyTimesArticlesJob: Job ended.');
    }
}
