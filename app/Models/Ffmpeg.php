<?php

namespace App\Models;

use FFMpeg\FFMpeg as VideoConverter;
use FFMpeg\Coordinate\TimeCode;
use FFMpeg\Format\Video\X264;
use App\Helpers\File;
use Storage;

/**
 * Helper Model for video convert with FFmpeg
 * Class Ffmpeg
 * @package App\Models
 */

class Ffmpeg
{
    /** @var null|string  */
    protected $audioCodec;

    /** @var VideoConverter  */
    protected $ffmpeg;

    /** @var AppVideo  */
    protected $video;

    /**
     * Ffmpeg constructor.
     * @param AppVideo $video
     * @param null|string $audioCodec
     */
    public function __construct(AppVideo $video, $audioCodec = null)
    {
        $this->ffmpeg = VideoConverter::create([
            'ffmpeg.binaries'  => env('FFMPEG_PATH'),
            'ffprobe.binaries' => env('FFPROBE_PATH')
        ]);
        $this->video = $video;
        $this->audioCodec = ($audioCodec) ?? env('FFMPEG_AUDIO_CODEC');
    }

    public function cutVideo()
    {
        $file = $this->ffmpeg->open($this->getOriginvideoPath());
        $file->filters()->clip(
            TimeCode::fromSeconds($this->video->start_time),
            TimeCode::fromSeconds($this->video->duration)
        );
        $file->save(
            new X264($this->audioCodec), File::setNewName($this->getOriginVideoPath(), env('CUT_FILE_NAME'))
        );
    }

    /**
     * @return string
     */
    public function getOriginVideoPath()
    {
        return Storage::getDriver()->getAdapter()->getPathPrefix().$this->video->url;
    }
}