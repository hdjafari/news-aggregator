<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Console\Scheduling\Schedule;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })
    ->withSchedule(function (Schedule $schedule) {
        $newsCategories = explode(',', env('NEWSCATEGORIES', 'business,sports,technology'));

        foreach ($newsCategories as $category) {
            $schedule->job(new \App\Jobs\FetchNewsAPIArticlesJob($category))->everyMinute();
            $schedule->job(new \App\Jobs\FetchNyTimesArticlesJob($category))->everyMinute();
            $schedule->job(new \App\Jobs\FetchGuardianArticlesJob($category))->everyMinute();
        }
    })
    ->create();
