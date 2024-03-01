<?php

namespace App\Contracts;

interface TextractServiceInterface
{
    /**
     * @param  string  $documentKey
     *
     * @return string|null
     */
    public function startAnalyzeDocument(string $documentKey): ?string;

    /**
     * @param  string  $jobId
     *
     * @return array
     */
    public function getDocumentAnalysis(string $jobId): array;

    /**
     * @param  array  $blocks
     *
     * @return string
     */
    public function parseBlocks(array $blocks): string;
}
