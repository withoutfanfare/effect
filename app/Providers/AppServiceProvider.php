<?php

namespace App\Providers;

use App\Contracts\DocumentAnalysisServiceInterface;
use App\Contracts\S3ServiceInterface;
use App\Contracts\TextractServiceInterface;
use App\Services\S3Service;
use App\Services\TextractService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register()
    {
        $this->app->bind(S3ServiceInterface::class, S3Service::class);
        $this->app->bind(TextractServiceInterface::class, TextractService::class);
        $this->app->bind(DocumentAnalysisServiceInterface::class, TextractService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
