<?php

namespace App\Contracts;

interface TextractServiceInterface
{
    public function startAnalyzeDocument(string $documentKey): ?string;
    public function getDocumentAnalysis(string $jobId): array;
    public function parseBlocks(array $blocks): string;
}
