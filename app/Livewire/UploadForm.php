<?php

namespace App\Livewire;

use Exception;
use Illuminate\Support\Facades\Http;
use Livewire\Component;
use Livewire\WithFileUploads;


class UploadForm extends Component
{
    use WithFileUploads;

    public $file;
    public $message;
    public $uploadSuccess = false;
    public $uploadError = false;
    // protected $listeners = ['documentAnalysisCompleted'];
    protected $listeners = ['document-analysis-completed' => 'handleDocumentAnalysisCompleted'];

    public function submit()
    {
        $this->validate([
            'file' => 'required|mimes:pdf|max:4096',
        ]);

        try {
            // Fulfil the Base 64 part of the brief by manually encoding the file.
            if ($this->file) {
                $base64File = base64_encode(file_get_contents($this->file->getRealPath()));

                $response = $this->sendToInternalApi($base64File);

                ray($response->json());

                // Handle these separately in the UI so we can colourise the messages.
                if ($response->successful()) {
                    $this->uploadSuccess = $response->json()['message'];
                } else {
                    $this->uploadError = $response->json()['message'];
                }
            }
        } catch(Exception $e) {
            ray($e->getMessage());
            $this->uploadError = true;
            $this->message = 'Failed to upload file.';
        }
    }

    // Fulfil the REST API request for the brief with the base64 file.
    private function sendToInternalApi($base64File)
    {
        return Http::timeout(400)->post(env('APP_URL') . '/api/upload', [
            'pdf_base64' => $base64File,
        ]);
    }

    public function handleDocumentAnalysisCompleted($event)
    {
        ray('handleDocumentAnalysisCompleted');
        ray($event);

        // Access your event data here
        $jobId = $event['detail']['jobId'];
        $analysisResult = $event['detail']['analysisResult'];
        $status = $event['detail']['status'];

        if ($status === 'succeeded') {
            $this->uploadSuccess = true;
            $this->message = 'Document analysis completed successfully.';
            // Process $analysisResult as needed
        } else {
            $this->uploadError = true;
            $this->message = 'Document analysis failed.';
        }
    }

    public function render()
    {
        return view('livewire.upload-form');
    }
}
