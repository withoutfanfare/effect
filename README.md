# Effect - AWS Textract

The Effect AWS Textract project is a test project that demonstrates how to use the AWS Textract service to extract text from PDF documents. The project is built using the Laravel PHP framework and uses the AWS SDK for PHP to interact with the AWS Textract service. Additionally it uses Pusher to provide real-time updates to the user interface.

This document provides instructions on how to use the `api/upload` endpoint in this test project.

You can use a live Livewire example of the app here:

[https://effect.stuntrocket.co/](https://effect.stuntrocket.co/)


## API Endpoint Usage

### Upload Endpoint

The `api/upload` endpoint is used to upload a PDF file for analysis before storing in the database. This endpoint accepts a POST request with the PDF file encoded in base64 format.

#### Request

The request should be a POST request with the following structure:

```json
{
    "pdf_base64": "<base64_encoded_pdf>"
}
```

Replace <base64_encoded_pdf> with your base64 encoded PDF file.

#### Response

The response will be a JSON object. If the upload and analysis request is successful, you will receive a 202 Accepted status code with a message indicating that the document analysis is pending.  

Example of a successful response:

```json
{
    "message": "Document analysis pending."
}
```

If there is an error during the upload or analysis request, you will receive a 500 Internal Server Error status code with a message indicating the error.  

Example of an error response:

```json
{
    "message": "Failed to extract text from PDF."
}
```

#### Usage

You can use curl to test this endpoint:

```bash
curl -X POST -H "Content-Type: application/json" -d '{"pdf_base64":"<base64_encoded_pdf>"}' http://localhost:8000/api/upload
```

Please note that you need to replace http://localhost:8000 with the actual URL of your application if it's hosted elsewhere.

