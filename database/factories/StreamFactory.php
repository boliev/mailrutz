<?php

use Faker\Generator as Faker;

$factory->define(App\Stream::class, function (Faker $faker) {
    return [
        'game_id' => 1,
        'title' => $faker->title,
        'streamer_id' => $faker->numberBetween(1000, 100000),
        'stream_id' => $faker->numberBetween(1000, 100000),
        'service_name' => App\Stream::SERVICE_NAME_TWITCH,
        'language' => 'en',
        'viewers_count' => $faker->numberBetween(1, 100),
        'period_from' => \Carbon\Carbon::now()->subMinutes(3),
        'period_to' => \Carbon\Carbon::now(),
    ];
});
