<?php

namespace App\Services;

use Aws\S3\S3Client;
use Aws\Textract\TextractClient;
use App\Contracts\TextractServiceInterface;

class TextractService implements TextractServiceInterface
{
    protected $client;

    public function __construct()
    {
        $this->client = new TextractClient([
            'version' => 'latest',
            'region'      => config('services.aws.region'),
            'credentials' => [
                'key'    => config('services.aws.key'),
                'secret' => config('services.aws.secret')
            ],
        ]);
    }

    public function startAnalyzeDocument($documentKey): ?string
    {
        $bucket = config('services.aws.bucket');

        $result = $this->client->startDocumentAnalysis([
            'DocumentLocation' => [
                'S3Object' => [
                    'Bucket' => $bucket,
                    'Name' => $documentKey,
                ],
            ],
            'FeatureTypes' => ['TABLES', 'FORMS', 'SIGNATURES'],
        ]);

        return $result->get('JobId');
    }

    public function getDocumentAnalysis($jobId): array
    {
        $result = [];
        if (!empty($this->client)) {
            $result = $this->client->getDocumentAnalysis([
                'JobId' => $jobId,
            ]);
        }

        ray($result)->blue();
        return $result->toArray();
    }

    // TODO - this should be in the S3Service
    public function uploadBase64EncodedDocument(string $decodedContent, string $documentName): string
    {
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
        $bucket = config('services.aws.bucket');
        $s3->putObject([
            'Bucket' => $bucket,
            'Key'    => $documentName,
            'Body'   => $decodedContent,
            'ContentType' => 'application/pdf',
        ]);

        return $documentName; // Assuming the document name is the key in the bucket
    }

    // TODO - guessing structure for now. Will need to be updated.
    public function parseBlocks(array $blocks): string
    {
        $string = '';

        foreach ($blocks as $block) {
            if ($block['BlockType'] === 'LINE') {
                $string .= $block['Text'] . ' ';
            }
        }

        return rtrim($string);
    }
}
