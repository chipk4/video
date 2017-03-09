<?php

namespace App\Providers;

use App\Models\AppVideo;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Queue;
use Illuminate\Queue\Events\JobProcessed;
use Illuminate\Queue\Events\JobProcessing;
use Illuminate\Queue\Events\JobFailed;
use Log;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Queue::before(function (JobProcessing $event) {
            if($event->job->getQueue() == env('QUEUE_VIDEO_NAME')) {
                $command = (unserialize($event->job->payload()['data']['command']));
                $video = AppVideo::find($command->getVideoId());
                $video->setVideoStatus(AppVideo::PROCESS_PROCESSING);
                $video->save();
            }
        });
        Queue::after(function (JobProcessed $event) {
            if($event->job->getQueue() == env('QUEUE_VIDEO_NAME')) {
                $command = (unserialize($event->job->payload()['data']['command']));
                $video = AppVideo::find($command->getVideoId());
                $video->setVideoStatus(AppVideo::PROCESS_DONE);
                $video->save();
            }
        });
        Queue::failing(function (JobFailed $event) {
            if($event->job->getQueue() == env('QUEUE_VIDEO_NAME')) {
                $command = (unserialize($event->job->payload()['data']['command']));
                $video = AppVideo::find($command->getVideoId());
                $video->setVideoStatus(AppVideo::PROCESS_DONE);
                $video->save();
            }
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

}
