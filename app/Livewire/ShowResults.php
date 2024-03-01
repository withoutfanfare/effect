<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Extraction;

class ShowResults extends Component
{
    public $jobId;
    public $analysisResult;
    public $status;

    public function mount()
    {
        $this->jobId = request('jobId');
        $this->getExtraction();
    }

    public function getExtraction()
    {
        $result = Extraction::where('job_id', $this->jobId)->first();
        $this->analysisResult = $result['extracted_text'] ?? 'Not Found.';

        if ($this->analysisResult) {
            $this->status = $result->status;
        } else {
            // No extraction with the given jobId was found
            // TODO - Handle this case
        }
    }

    public function render()
    {
        return view('livewire.results-component');
    }
}
