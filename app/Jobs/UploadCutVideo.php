<?php

namespace App\Jobs;

use Exception;
use App\Models\AppVideo;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class UploadCutVideo implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $video;

    /**
     * Create a new job instance.
     * @param AppVideo $video
     */
    public function __construct(AppVideo $video)
    {
        $this->video = $video;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->video->setVideoStatus(AppVideo::PROCESS_PROCESSING);

        $this->video->cutVideo();

        $this->video->setVideoStatus(AppVideo::PROCESS_DONE);
    }

    public function failed(Exception $exception)
    {
        Log::error('Job failed', [
            'videoId' => $this->video->id,
            'error' => $exception->getMessage()
        ]);
        $this->video->setVideoStatus(AppVideo::PROCESS_FAILED);
    }
}