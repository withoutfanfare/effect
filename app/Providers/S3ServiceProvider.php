<?php

namespace App\Providers;

use App\Contracts\S3ServiceInterface;
use App\Services\S3Service;
use Aws\S3\S3Client;
use Illuminate\Support\ServiceProvider;

class S3ServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(S3Client::class, function ($app) {
            return new S3Client([
                'version'     => 'latest',
                'region'      => config('services.aws.region'),
                'credentials' => [
                    'key'    => config('services.aws.key'),
                    'secret' => config('services.aws.secret')
                ],
            ]);
        });

        $this->app->bind(S3ServiceInterface::class, S3Service::class);
    }
}
