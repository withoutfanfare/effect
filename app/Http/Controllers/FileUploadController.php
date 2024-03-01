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
    protected TextractServiceInterface $textractService;
    protected S3ServiceInterface $s3Service;

    public function __construct(TextractServiceInterface $textractService, S3ServiceInterface $s3Service)
    {
        $this->textractService = $textractService;
        $this->s3Service = $s3Service;
    }

    /**
     * @param  UploadPdfRequest  $request
     *
     * @return JsonResponse
     */
    public function upload(UploadPdfRequest $request): JsonResponse
    {
        try {
            $uploadedDocument = $this->decodeAndUpload($request);

            if (!$uploadedDocument) {
                return response()->json(['message' => 'Failed to extract text from PDF.'], 500);
            }

            $jobId = $this->textractService->startAnalyzeDocument($uploadedDocument);

            if (empty($jobId)) {
                return response()->json(['message' => 'Failed to start document analysis'], 500);
            }

            ProcessDocumentAnalysisJob::dispatch($jobId);

            return response()->json(['message' => 'Document analysis pending.'], 202);
        } catch (\Exception $e) {
            Log::error("Error occurred in file: {$e->getFile()} on line {$e->getLine()} with message: {$e->getMessage()}");
            return response()->json(['message' => 'Failed to upload file.'], 500);
        }
    }

    /**
     * @param  UploadPdfRequest  $request
     *
     * @return string
     */
    protected function decodeAndUpload(UploadPdfRequest $request)
    {
        $pdfDecoded = base64_decode($request->input('pdf_base64'));
        $uploadResponse = $this->s3Service->uploadBase64EncodedDocument($pdfDecoded);

        if (isset($uploadResponse['ObjectURL'])) {
            $urlComponents = parse_url($uploadResponse['ObjectURL']);
            $path = $urlComponents['path'];
            return basename($path);
        }

        return $uploadResponse;
    }

}
