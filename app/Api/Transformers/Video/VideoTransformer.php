<?php
/**
 * Created by PhpStorm.
 * User: chipkA
 * Date: 3/2/17
 * Time: 8:42 PM
 */

namespace App\Api\Transformers\Video;

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
        $videoUrls = [
            'origin' => env('VIDEO_URL').'/'.$item->url,
            'cut' => ''
        ];
        if($item->status == AppVideo::PROCESS_DONE) {
            $videoUrls['cut'] = env('VIDEO_URL').'/'.File::setNewName($item->url, env('CUT_FILE_NAME'));
        }
        return $videoUrls;
    }
}