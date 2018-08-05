<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStreamViewersTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('stream_viewers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('stream_id', false, true);
            $table->integer('count');
            $table->dateTime('period_from');
            $table->dateTime('period_to');
            $table->foreign('stream_id')->references('id')->on('streams')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('stream_viewers');
    }
}
