<?php

namespace App\Api\v01\Transformers\Video;

use App\Helpers\File;
use App\Models\AppVideo;
use League\Fractal\TransformerAbstract;

class VideoTransformer extends TransformerAbstract
{
    public function transform(AppVideo $item)
    {
        return [
            'video' => $this->returnVideoUrls($item),
            'duration' => $item->duration,
            'status' => __('video.status.'.$item->status)
        ];
    }

    protected function returnVideoUrls(AppVideo $item)
    {
        $baseUrl = env('VIDEO_URL').'/';
        $videoUrls = [
            'origin' => $baseUrl.$item->url,
            'cut' => ''
        ];
        if($item->status == AppVideo::PROCESS_DONE) {
            $videoUrls['cut'] = $baseUrl.File::setNewName($item->url, env('CUT_FILE_NAME'));
        }
        return $videoUrls;
    }
}