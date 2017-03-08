<?php

namespace App\Jobs;

use App\Models\Conversion\Contracts\Video\Video as ContractVideo;
use App\Models\Conversion\Ffmpeg;
use Exception;
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
     * @param ContractVideo $video
     */
    public function __construct(ContractVideo $video)
    {
        $this->video = $video;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(Ffmpeg $videoConverter)
    {
//        $this->video->setVideoStatus(AppVideo::PROCESS_PROCESSING);

        $videoConverter->cutVideo($this->video);

//        $this->video->setVideoStatus(AppVideo::PROCESS_DONE);
    }

    public function failed(Exception $exception)
    {
        Log::error('Job failed', [
            'videoId' => $this->video->id,
            'error' => $exception->getMessage()
        ]);
//        $this->video->setVideoStatus(AppVideo::PROCESS_FAILED);
    }
}