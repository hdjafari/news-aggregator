<?php

namespace App\Jobs;

use App\Models\Article;
use App\Models\Category;
use App\Services\NewsApiService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class FetchNewsAPIArticlesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $category;

    public function __construct($category)
    {
        $this->category = $category;
    }

    public function handle(NewsApiService $newsApiService)
    {
        Log::info('FetchArticlesJob: Job started.');
        try {
            $articles = $newsApiService->fetchArticles($this->category);
            foreach ($articles as $articleData) {
                $category = Category::firstOrCreate(['name' => $this->category]);

                $article = Article::updateOrCreate(
                    ['url' => $articleData['url']],
                    [
                        'title' => $articleData['title'],
                        'content' => $articleData['content'],
                        'author' => $articleData['author'],
                        'source' => $articleData['source']['name'],
                        'published_at' => Carbon::parse($articleData['publishedAt'])->toDateTimeString(),
                    ]
                );

                $article->categories()->syncWithoutDetaching([$category->id]);
            }
        } catch (\Exception $e) {
        }
    }
}