<?php
namespace App\Api\Controllers;

use App\Api\Transformers\VideoFile\Upload;
use App\Jobs\UploadCutVideo;
use App\Models\AppVideo;
use Illuminate\Http\Request;

class VideoController extends BaseController
{

    public function upload(Request $request)
    {
        $this->setTransformer(new Upload());

        $video = new AppVideo();
        $video->saveVideo($request);

        if($video->id) {
            $job = new UploadCutVideo($video);
            $this->dispatch($job);
            return $this->respondWithItem($video->id);
        }

        return $this->respondWithError('Can not save video');
    }

    public function getList(Request $request)
    {
        
    }

    public function restartFailed(Request $request)
    {

    }

}