<?php

namespace App\Jobs;

use App\Models\Extraction;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Container\Container;
use App\Contracts\DocumentAnalysisServiceInterface;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use App\Events\DocumentAnalysisCompleted;

class ProcessDocumentAnalysisJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected string $jobId;

    /**
     * Create a new job instance.
     *
     * @param string $jobId
     * @return void
     */
    public function __construct(string $jobId)
    {
        $this->jobId = $jobId;
    }

    /**
     * Execute the job.
     *
     * @param  Container  $app
     * @return void
     * @throws BindingResolutionException
     */
    public function handle(Container $app): void
    {
        $textractService = $app->make(DocumentAnalysisServiceInterface::class);

        $maxAttempts = 100;
        $attempt = 0;
        $sleepDuration = 2; // seconds

        while ($attempt < $maxAttempts) {
            $result = $textractService->getDocumentAnalysis($this->jobId);

            if ($result['JobStatus'] === 'SUCCEEDED' || $result['JobStatus'] === 'FAILED') {
                $status = strtolower($result['JobStatus']);

                $parsedBlocks = $textractService->parseBlocks($result['Blocks']);

                // Save the blocks to the Extraction model.
                $saved = Extraction::saveBlocks($this->jobId, $parsedBlocks, $status);
                if ($saved) {
                    event(new DocumentAnalysisCompleted($this->jobId));
                    break;
                }

                break;
            }

            $attempt++;
            sleep($sleepDuration);
        }

        if ($attempt >= $maxAttempts) {
            Log::warning("Maximum attempts reached for job {$this->jobId} without completion.");
            // TODO - Need to add additional handling for this case.
        }
    }
}
