<?php

namespace App\Models\Conversion\Contracts\Video;

use App\Models\Conversion\Contracts\Video\Video as VideoForCut;

interface Converter {

    public function cutVideo(VideoForCut $video);

}