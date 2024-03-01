<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Extraction;

class ShowResults extends Component
{
    public $jobId;
    public $analysisResult;

    public function mount()
    {
        $this->jobId = request('jobId');
        $this->getExtraction();
    }

    public function getExtraction()
    {
        $result = Extraction::whereJobId($this->jobId)->first();

        ray($result);

        if ($result) {
            $this->analysisResult = $result['text'] ?? 'Not Found.';
        } else {
            // TODO - Handle this case
        }
    }

    public function render()
    {
        return view('livewire.results-component');
    }
}
