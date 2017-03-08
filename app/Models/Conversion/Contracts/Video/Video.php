<?php

namespace App\Models\Conversion\Contracts\Video;

interface Video
{
    /**
     * Get real path to video file
     *
     * @return string
     */
    public function videoPath();

    /**
     * Get the time for result video duration
     *
     * @return int
     */
    public function duration();

    /**
     * The time from which the cutting begins
     *
     * @return int
     */
    public function startTime();
}