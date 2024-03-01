<?php

namespace App\Contracts;

interface DocumentAnalysisServiceInterface
{
    /**
     * Start the analysis of a document.
     *
     * @param string $documentKey The key or path of the document in the storage service.
     * @return string|null The job ID of the analysis process, or null if initiation fails.
     */
    public function startAnalyzeDocument(string $documentKey): ?string;

    /**
     * Get the analysis result of a document.
     *
     * @param string $jobId The job ID of the analysis process.
     * @return array The result of the document analysis, including the status and any data produced by the analysis.
     */
    public function getDocumentAnalysis(string $jobId): array;
}
