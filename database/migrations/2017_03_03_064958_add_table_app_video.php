<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTableAppVideo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('app_video_list', function (Blueprint $table) {
            $table->increments('id');
            $table->string('url')->default('');
            $table->integer('status');
            $table->integer('start_time')->unsigned();
            $table->integer('duration')->unsigned();
            $table->integer('user_id')->unsigned();

            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
            $table->index('duration', 'time_duration');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('app_video_list', function (Blueprint $table) {
            $table->dropIndex('time_duration');
            $table->dropForeign(['user_id']);
        });

        Schema::dropIfExists('app_video_list');
    }
}
