<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('genre_video', function (Blueprint $table) {
            $table->uuid('genre_id');
            $table->foreign('genre_id')
                ->references('id')
                ->on('genres');
            $table->uuid('video_id');
            $table->foreign('video_id')
                ->references('id')
                ->on('videos');

            $table->unique(['genre_id', 'video_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('gene_video');
    }
};
