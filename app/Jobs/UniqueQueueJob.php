<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UniqueQueueJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $data;
    public $uniqueKey;

    public function __construct($data, $uniqueKey)
    {
        $this->data = $data;
        $this->uniqueKey = $uniqueKey;
    }

    public function handle()
    {
        \Log::info('Processing data:', ['data' => $this->data]);

        // Simulate task completion
        sleep(5);

        // Remove the lock after job completion
        Cache::forget($this->uniqueKey);
        \Log::info('Job completed, lock released.');
    }
}
