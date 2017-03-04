<?php
namespace App\Models;

use App\Helpers\Helper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Auth;

class AppVideo extends Model {

    protected $table = 'app_video_list';

    const PROCESS_SCHEDULED = 1;
    const PROCESS_PROCESSING = 2;
    const PROCESS_DONE = 3;
    const PROCESS_FAILED = 4;
    const DEFAULT_PER_PAGE = 5;

    public function saveVideo(Request $request)
    {
        $this->status = self::PROCESS_SCHEDULED;
        $this->start_time = $request->get('start_time');
        $this->duration = $request->get('duration');
        $this->user_id = $id = Auth::id();

        if($this->save()) {
            $filePath = $this->storeVideoFile($request);
            $this->url = $filePath;
            $this->save();
        }

        return $this;
    }

    protected function storeVideoFile(Request $request)
    {
        $filePath = 'video'.DIRECTORY_SEPARATOR.$this->user_id.DIRECTORY_SEPARATOR.$this->id;

        $path = $request->file('video')->store($filePath);

        return $path;
    }
}