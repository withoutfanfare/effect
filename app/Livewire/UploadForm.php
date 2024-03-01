<?php

namespace App\Livewire;

use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithFileUploads;

class UploadForm extends Component
{
    use WithFileUploads;

    public $file;
    public $message;
    public $uploadSuccess = false;
    public $uploadError = false;
    protected $listeners = ['document-analysis-completed' => 'handleDocumentAnalysisCompleted'];

    /**
     * @return void
     */
    public function submit()
    {
        $this->validate([
            'file' => 'required|mimes:pdf|max:8192',
        ]);

        try {
            // Fulfil the Base 64 input part of the brief by manually encoding the file from the UI.
            if ($this->file) {
                $base64File = base64_encode(file_get_contents($this->file->getRealPath()));

                $response = $this->sendToInternalApi($base64File);

                // Handle these separately in the UI so we can colourise the messages.
                if ($response->successful()) {
                    $this->uploadSuccess = $response->json()['message'];
                } else {
                    $this->uploadError = $response->json()['message'];
                }
            }
        } catch(Exception $e) {
            Log::info($e->getMessage());
            $this->uploadError = true;
            $this->message = 'Failed to upload file.';
        }
    }

    // Fulfil the REST API request for the brief with the base64 file.

    /**
     * @param $base64File
     *
     * @return \GuzzleHttp\Promise\PromiseInterface|\Illuminate\Http\Client\Response
     */
    private function sendToInternalApi($base64File)
    {
        return Http::timeout(400)->post(env('APP_URL') . '/api/upload', [
            'pdf_base64' => $base64File,
        ]);
    }

    /**
     * @param $event
     *
     * @return void
     */
    public function handleDocumentAnalysisCompleted($event)
    {
        $status = $event['detail']['status'];
        if ($status === 'succeeded') {
            $this->uploadSuccess = true;
            $this->message = 'Document analysis completed successfully.';
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
