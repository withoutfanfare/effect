<?php

namespace App\Services;

use Aws\Textract\TextractClient;
use App\Contracts\TextractServiceInterface;

class TextractService implements TextractServiceInterface
{
    protected $client;

    public function __construct(TextractClient $client)
    {
        $this->client = $client;
    }

    /**
     * @param $documentKey
     *
     * @return string|null
     */
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

    /**
     * @param $jobId
     *
     * @return array
     */
    public function getDocumentAnalysis($jobId): array
    {
        if (!empty($this->client)) {
            $result = $this->client->getDocumentAnalysis([
                'JobId' => $jobId,
            ]);
        }

        return !empty($result) ? $result->toArray() : [];
    }

    /**
     * @param  array  $blocks
     *
     * @return string
     */
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
