<?php

namespace App\Providers;

use App\Contracts\TextractServiceInterface;
use Aws\Textract\TextractClient;
use App\Services\TextractService;
use Illuminate\Support\ServiceProvider;

class TextractServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(TextractService::class, function () {
            $textractClient = new TextractClient([
                'version' => 'latest',
                'region' => config('services.aws.region'),
                'credentials' => [
                    'key'    => config('services.aws.key'),
                    'secret' => config('services.aws.secret')
                ],
            ]);

            return new TextractService($textractClient);
        });

        $this->app->bind(TextractServiceInterface::class, TextractService::class);
    }

}
