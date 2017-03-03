<?php
namespace App\Api\Controllers;

use App\Jobs\UploadCutVideo;
use App\Models\AppVideo;
use Illuminate\Http\Request;
use Validator;
use Storage;

class VideoController extends BaseController
{

    public function upload(Request $request)
    {
        $requestFields = $request->only('duration', 'start_time', 'video');

        $validator = $this->validator($requestFields);

        if ($validator->fails()) {
            return $this->respondWithError($validator->messages());
        }

        $video = new AppVideo();
        $video->saveVideo($request);
        $fileUrl = storage_path('app').DIRECTORY_SEPARATOR.$video->url;

        if($video->id) {
            $job = new UploadCutVideo($video, $fileUrl);
            $this->dispatch($job);
            return $this->respondWithMessage('File was added to queue');
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
            'video' => 'required|file|mimes:mp4'
        ]);
    }

}