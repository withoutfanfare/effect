<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class DocumentAnalysisCompleted implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $jobId;
    // public $analysisResult;
    // public $status;

    public function __construct($jobId)
    {
        ray('DocumentAnalysisCompleted', $jobId)->green();
        $this->jobId = $jobId;
    }

    public function broadcastOn()
    {
        return new Channel('document-analysis');
    }

    public function broadcastAs()
    {
        return 'document.analysis.completed';
    }
}
