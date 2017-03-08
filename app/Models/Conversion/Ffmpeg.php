<?php

namespace App\Models\Conversion;

use App\Models\Conversion\Contracts\Video\{Video, Converter};
use FFMpeg\FFMpeg as VideoConverter;
use FFMpeg\Coordinate\TimeCode;
use FFMpeg\Format\Video\X264;
use App\Helpers\File;
use Storage;

/**
 * Helper Model for video convert with FFmpeg
 * Class Ffmpeg
 * @package App\Models\Conversion
 */

class Ffmpeg implements Converter
{
    /** @var null|string  */
    protected $audioCodec;

    /** @var VideoConverter  */
    protected $ffmpeg;

    /**
     * Ffmpeg constructor.
     * @param null|string $audioCodec
     */
    public function __construct($audioCodec = null)
    {
        $this->ffmpeg = VideoConverter::create([
            'ffmpeg.binaries'  => env('FFMPEG_PATH'),
            'ffprobe.binaries' => env('FFPROBE_PATH')
        ]);
        $this->audioCodec = ($audioCodec) ?? env('FFMPEG_AUDIO_CODEC');
    }

    public function cutVideo(Video $video)
    {
        $file = $this->ffmpeg->open($video->videoPath());
        $file->filters()->clip(
            TimeCode::fromSeconds($video->startTime()),
            TimeCode::fromSeconds($video->duration())
        );
        $file->save(
            new X264($this->audioCodec), File::setNewName($video->videoPath(), env('CUT_FILE_NAME'))
        );
    }
}