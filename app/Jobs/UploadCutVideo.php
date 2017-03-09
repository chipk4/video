<?php

namespace App\Jobs;

use App\Models\AppVideo;
use App\Models\Conversion\Contracts\Video\Video as ContractVideo;
use App\Models\Conversion\Ffmpeg;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Log;

class UploadCutVideo implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var ContractVideo
     */
    protected $video;

    /**
     * @var int
     */
    private $videoId;

    /**
     * Create a new job instance.
     * @param ContractVideo $video
     */
    public function __construct(ContractVideo $video, int $videoId)
    {
        $this->video = $video;
        $this->videoId = $videoId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(Ffmpeg $videoConverter)
    {

        $videoConverter->cutVideo($this->video);

    }

    public function failed(Exception $exception)
    {
        Log::error('Job failed', [
            'videoId' => $this->getVideoId(),
            'error' => $exception->getMessage()
        ]);

        (AppVideo::find($this->getVideoId()))->setVideoStatus(AppVideo::PROCESS_FAILED);
    }

    /**
     * @return int
     */
    public function getVideoId(): int
    {
        return $this->videoId;
    }

}