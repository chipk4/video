<?php
namespace App\Models;

use App\Models\Conversion\Contracts\Video\Video;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Auth;
use Storage;

class AppVideo extends Model implements Video
{

    protected $table = 'app_video_list';

    const PROCESS_SCHEDULED = 1;
    const PROCESS_PROCESSING = 2;
    const PROCESS_DONE = 3;
    const PROCESS_FAILED = 4;
    const DEFAULT_PER_PAGE = 5;

    /**
     * @param Request $request
     * @return $this
     */
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

    public function setVideoStatus(int $status)
    {
        if(!$this->save()) {
            Log::error('Can not save video status', [
                'video' => $this->id,
                'user' => $this->user_id,
                'status' => $status
            ]);
        }
    }

    /**
     * Return video list by user
     * @param int $userId
     * @param int $page
     */
    public function getByUser(int $userId, int $page)
    {
        return $this->where('user_id', $userId)->paginate(AppVideo::DEFAULT_PER_PAGE, ['*'], 'page', $page);
    }

    /**
     * @param Request $request
     * @return false|string
     */
    protected function storeVideoFile(Request $request)
    {
        $filePath = 'video'.DIRECTORY_SEPARATOR.$this->user_id.DIRECTORY_SEPARATOR.$this->id;

        return $request->file('video')->store($filePath);
    }

    /**
     * Get real path to video file
     *
     * @return string
     */
    public function videoPath()
    {
        return Storage::getDriver()->getAdapter()->getPathPrefix().$this->url;
    }

    /**
     * Get the time for result video duration
     *
     * @return int
     */
    public function duration()
    {
        return $this->duration;
    }

    /**
     * The time from which the cutting begins
     *
     * @return int
     */
    public function startTime()
    {
        return $this->start_time;
    }
}