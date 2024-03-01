<?php

namespace App\Services;

use Aws\S3\S3Client;
use Illuminate\Support\Str;
use App\Contracts\S3ServiceInterface;

class S3Service implements S3ServiceInterface
{
    public function uploadBase64EncodedDocument(string $base64EncodedDocument): string
    {
        // TODO - handle exceptions

        $documentName = (string) Str::uuid() . '.pdf';
        $bucket = config('services.aws.bucket');

        // Initialize the S3 client
        $s3 = new S3Client([
            'version'     => 'latest',
            'region'      => config('services.aws.region'),
            'credentials' => [
                'key'    => config('services.aws.key'),
                'secret' => config('services.aws.secret'),
            ],
        ]);

        // Upload the decoded file to S3
        $s3->putObject([
            'Bucket' => $bucket,
            'Key'    => $documentName,
            'Body'   => $base64EncodedDocument,
            'ContentType' => 'application/pdf',
        ])->toArray();

        ray($documentName)->orange();

        return $documentName;
    }
}
