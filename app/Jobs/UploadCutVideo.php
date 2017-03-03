<?php

namespace App\Jobs;

use App\Helpers\File;
use App\Models\AppVideo;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use FFMpeg\FFMpeg;
use Storage;
use FFMpeg\Format\Video\X264;
use FFMpeg\Coordinate\TimeCode;

class UploadCutVideo implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $video;
    protected $fileUrl;

    /**
     * Create a new job instance.
     * @param AppVideo $video
     * @param $fileUrl
     */
    public function __construct(AppVideo $video, $fileUrl)
    {
        $this->video = $video;
        $this->fileUrl = $fileUrl;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->setVideoStatus(AppVideo::PROCESS_PROCESSING);

        $ffmpeg = FFMpeg::create([
            'ffmpeg.binaries'  => env('FFMPEG_PATH'),
            'ffprobe.binaries' => env('FFPROBE_PATH')
        ]);
        $video = $ffmpeg->open($this->fileUrl);
        $video->filters()->clip(TimeCode::fromSeconds($this->video->start_time), TimeCode::fromSeconds($this->video->duration));
        $video->save(new X264(env('FFMPEG_AUDIO_CODEC')), File::setNewName($this->fileUrl, env('CUT_FILE_NAME')));

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