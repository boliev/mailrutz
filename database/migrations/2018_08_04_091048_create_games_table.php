<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGamesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('games', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('twitch_id')->nullable();
            $table->string('youtube_id')->nullable();
        });

        \Illuminate\Support\Facades\DB::table('games')->insert([
            ['id' => '1', 'title' => 'Warface', 'twitch_id' => '29918'],
            ['id' => '2', 'title' => 'Conqueror\'s Blade', 'twitch_id' => '498523'],
            ['id' => '3', 'title' => 'PLAYERUNKNOWN\'S BATTLEGROUNDS', 'twitch_id' => '493057'],
            ['id' => '4', 'title' => 'ArcheAge', 'twitch_id' => '30924'],
            ['id' => '5', 'title' => 'Cross Fire', 'twitch_id' => '21167'],
            ['id' => '6', 'title' => 'Skyforge', 'twitch_id' => '461326'],
            ['id' => '7', 'title' => 'Perfect World', 'twitch_id' => '20050'],
            ['id' => '8', 'title' => 'Battle for the Galaxy', 'twitch_id' => '497980'],
            ['id' => '9', 'title' => 'Revelation', 'twitch_id' => '10539'],
            ['id' => '10', 'title' => 'Grand Theft Auto V', 'twitch_id' => '32982'],
            ['id' => '11', 'title' => 'FIFA 18', 'twitch_id' => '495589'],
            ['id' => '12', 'title' => 'Sid Meier\'s Civilization VI', 'twitch_id' => '492553']
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('games');
    }
}
