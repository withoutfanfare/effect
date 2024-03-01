<?php

namespace App\Contracts;

interface S3ServiceInterface
{
    public function uploadBase64EncodedDocument(string $base64EncodedDocument): string;

}
