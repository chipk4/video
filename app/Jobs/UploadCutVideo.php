<?php

namespace App\Jobs;

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
    protected $fileUrl;

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
        $this->setVideoStatus(AppVideo::PROCESS_PROCESSING);

        $this->video->cutVideo();

        $this->setVideoStatus(AppVideo::PROCESS_DONE);
    }

    public function failed()
    {
        $this->setVideoStatus(AppVideo::PROCESS_FAILED);
    }

    protected function setVideoStatus(int $status) {
        $this->video->status = $status;
        if(!$this->video->save()) {
            Log::error('Can not save video status', [
                'video' => $this->video->id,
                'user' => $this->video->user_id,
                'status' => $status
            ]);
        }
    }
}