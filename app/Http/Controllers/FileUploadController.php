<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessDocumentAnalysisJob;
use App\Contracts\S3ServiceInterface;
use App\Contracts\TextractServiceInterface;
use App\Http\Requests\UploadPdfRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class FileUploadController extends Controller
{
    protected $textractService;
    protected $s3Service;

    public function __construct(TextractServiceInterface $textractService, S3ServiceInterface $s3Service)
    {
        $this->textractService = $textractService;
        $this->s3Service = $s3Service;
    }

    public function upload(UploadPdfRequest $request): JsonResponse
    {
        try {
            $uploadedDocument = $this->decodeAndUpload($request);
            if (!$uploadedDocument) {
                return response()->json(['message' => 'Failed to upload document to S3'], 500);
            }

            $jobId = $this->textractService->startAnalyzeDocument($uploadedDocument);

            ray($jobId)->blue();

            if (empty($jobId)) {
                return response()->json(['message' => 'Failed to start document analysis'], 500);
            }

            // Dispatch job for asynchronous processing
            ProcessDocumentAnalysisJob::dispatch($jobId);

            return response()->json(['message' => 'Document analysis pending.'], 202);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['message' => 'Failed to upload file.'], 500);
        }
    }

    protected function decodeAndUpload(UploadPdfRequest $request)
    {
        ray('decodeAndUpload');
        $pdfDecoded = base64_decode($request->input('pdf_base64'));
        ray($pdfDecoded)->blue();
        $uploadResponse = $this->s3Service->uploadBase64EncodedDocument($pdfDecoded);
        ray($uploadResponse)->orange();

        if (isset($uploadResponse['ObjectURL'])) {
            $urlComponents = parse_url($uploadResponse['ObjectURL']);
            $path = $urlComponents['path'];
            return basename($path);
        }

        return $uploadResponse;
    }

}
