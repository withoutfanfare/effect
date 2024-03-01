<?php

namespace App\Livewire;

use Illuminate\Http\Request;
use Livewire\Component;
use App\Models\Extraction;

class ShowResults extends Component
{
    public $jobId;
    public $analysisResult;

    const JOB_ID_KEY = 'jobId';
    const NOT_FOUND_TEXT = 'Not Found.';

    /**
     * @param  Request  $request
     *
     * @return void
     */
    public function mount(Request $request): void
    {
        $this->jobId = request(self::JOB_ID_KEY);
        $this->analysisResult = $this->getExtraction();
    }

    /**
     * @return mixed|string
     */
    public function getExtraction(): mixed
    {
        $result = Extraction::whereJobId($this->jobId)->first();
        return $result && $result['text'] ? $result['text'] : self::NOT_FOUND_TEXT;
    }

    public function render()
    {
        return view('livewire.results-component');
    }
}
