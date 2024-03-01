<?php
namespace App\Services;

use Aws\S3\S3Client;
use Illuminate\Support\Str;
use App\Contracts\S3ServiceInterface;

class S3Service implements S3ServiceInterface
{
    private const AWS_CONFIG = [
        'version'     => 'latest',
        'region'      => 'services.aws.region',
        'credentials' => [
            'key'    => 'services.aws.key',
            'secret' => 'services.aws.secret',
        ],
    ];

    private const S3_EXCEPTION_MESSAGE = "There was an error uploading the file.";
    private const GENERAL_EXCEPTION_MESSAGE = "There was a general error.";

    private S3Client $s3Client;

    public function __construct(S3Client $s3Client)
    {
        $this->s3Client = $s3Client;
    }

    /**
     * @param  string  $base64EncodedDocument
     *
     * @return string
     */
    public function uploadBase64EncodedDocument(string $base64EncodedDocument): string
    {
        try {
            $documentName = (string) Str::uuid() . '.pdf';

            // Upload the decoded file to S3
            $this->s3Client->putObject([
                'Bucket' => config('services.aws.bucket'),
                'Key'    => $documentName,
                'Body'   => $base64EncodedDocument,
                'ContentType' => 'application/pdf', // Update this according to file type
            ])->toArray();

            return $documentName;
        } catch (\Aws\S3\Exception\S3Exception $e) {
            // Handle the S3Exception
            logger()->error(self::S3_EXCEPTION_MESSAGE, ['exception' => $e]);
        } catch (\Exception $e) {
            // Handle general exceptions
            logger()->error(self::GENERAL_EXCEPTION_MESSAGE, ['exception' => $e]);
        }

        return '';  // Default return if exceptions are encountered
    }
}
