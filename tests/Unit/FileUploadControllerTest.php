<?php

namespace Tests\Unit;

use App\Http\Controllers\FileUploadController;
use App\Contracts\S3ServiceInterface;
use App\Contracts\TextractServiceInterface;
use App\Http\Requests\UploadPdfRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Mockery;
use Tests\TestCase;

class FileUploadControllerTest extends TestCase
{
    protected $textractService;
    protected $s3Service;
    protected $controller;

    public function setUp(): void
    {
        parent::setUp();

        $this->textractService = Mockery::mock(TextractServiceInterface::class);
        $this->s3Service = Mockery::mock(S3ServiceInterface::class);
        $this->controller = new FileUploadController($this->textractService, $this->s3Service);
    }

    public function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function testUpload()
    {
        $request = Mockery::mock(UploadPdfRequest::class);
        $request->shouldReceive('input')->andReturn('pdf_base64_encoded_string');

        $this->s3Service->shouldReceive('uploadBase64EncodedDocument')->andReturn('document.pdf');
        $this->textractService->shouldReceive('startAnalyzeDocument')->andReturn('jobId');

        $response = $this->controller->upload($request);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(202, $response->getStatusCode());
        $this->assertEquals(['message' => 'Document analysis pending.'], $response->getData(true));
    }
}

