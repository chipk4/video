<?php
namespace App\Api\Controllers;

use App\Api\Transformers\VideoFile\Upload;
use App\Jobs\UploadCutVideo;
use App\Models\AppVideo;
use Illuminate\Http\Request;
use Validator;

class VideoController extends BaseController
{

    public function upload(Request $request)
    {
        $requestFields = $request->only('duration', 'start_time', 'video');

        $validator = $this->validator($requestFields);

        if ($validator->fails()) {
            return $this->respondWithError($validator->messages());
        }

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

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'duration' => 'required|int',
            'start_time' => 'required|int',
            'video' => 'required|file'
        ]);
    }

}