<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create
        (
            'tags',
            function(Blueprint $table)
            {
                $table->increments('id');
                $table->string('name',150)->unique()->comment('Tag');
                $table->timestamps();
                $table->softDeletes();

                $table->index(['deleted_at']);
            }
        );
        db_comment_table('tags', 'Tags');

        Schema::create
        (
            'tag_video',
            function(Blueprint $table)
            {
                $table->increments('id');
                $table->unsignedInteger('video_id')->index();
                $table->unsignedInteger('tag_id')->index();
                $table->timestamps();

                $table->unique(['video_id','tag_id']);

                $table->foreign('video_id')->references('id')->on('videos');
                $table->foreign('tag_id')->references('id')->on('tags');
            }
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tags');
        Schema::dropIfExists('tag_video');
    }
}