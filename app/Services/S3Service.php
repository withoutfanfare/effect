<?php
namespace App\Services;

use Aws\S3\S3Client;
use Illuminate\Support\Str;
use App\Contracts\S3ServiceInterface;

class S3Service implements S3ServiceInterface
{
    private $s3Client;

    public function __construct()
    {
        // Initialize the S3 client
        $this->s3Client = new S3Client([
            'version'     => 'latest',
            'region'      => config('services.aws.region'),
            'credentials' => [
                'key'    => config('services.aws.key'),
                'secret' => config('services.aws.secret'),
            ],
        ]);
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
            $bucket = config('services.aws.bucket');

            // Upload the decoded file to S3
            $this->s3Client->putObject([
                'Bucket' => $bucket,
                'Key'    => $documentName,
                'Body'   => $base64EncodedDocument,
                'ContentType' => 'application/pdf', // Update this according to file type
            ])->toArray();

            return $documentName;
        } catch (\Aws\S3\Exception\S3Exception $e) {
            // Handle the S3Exception
            echo "There was an error uploading the file.";
        } catch (\Exception $e) {
            // Handle general exceptions
            echo "There was a general error.";
        }
    }
}
