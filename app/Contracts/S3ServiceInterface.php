<?php

namespace App\Contracts;

interface S3ServiceInterface
{
    /**
     * @param  string  $base64EncodedDocument
     *
     * @return string
     */
    public function uploadBase64EncodedDocument(string $base64EncodedDocument): string;
}
