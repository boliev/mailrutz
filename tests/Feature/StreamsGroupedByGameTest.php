<?php

namespace Tests\Feature;

use App\Stream;
use App\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use Tests\TestCase;

class StreamsGroupedByGameTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test example.
     */
    public function testGamesList()
    {
        Passport::actingAs(
            factory(User::class)->create(),
            ['create-servers']
        );
        factory(Stream::class)->create([
            'game_id' => 1,
            'viewers_count' => 3,
        ]);

        factory(Stream::class)->create([
            'game_id' => 1,
            'viewers_count' => 5,
        ]);

        factory(Stream::class)->create([
            'game_id' => 2,
        ]);

        factory(Stream::class)->create([
            'game_id' => 3,
        ]);

        // Exact game
        $response = $this->get('/streams/games?games[]=1');

        $response->assertStatus(200);
        $response->assertJsonCount(1, 'data');
        $data = json_decode($response->getContent(), true);
        $this->assertEquals(8, $data['data'][0]['viewers_count']);

        // List of games
        $response = $this->get('/streams/games?games[]=1&games[]=2');

        $response->assertStatus(200);
        $response->assertJsonCount(2, 'data');

        // All games
        $response = $this->get('/streams/games');

        $response->assertStatus(200);
        $response->assertJsonCount(3, 'data');
    }

    /**
     * A basic test example.
     */
    public function testTime()
    {
        Passport::actingAs(
            factory(User::class)->create(),
            ['create-servers']
        );
        factory(Stream::class)->create([
            'game_id' => 1,
            'period_from' => Carbon::createFromFormat('Y-m-d H:i:s', '2018-04-04 12:00:00'),
            'period_to' => Carbon::createFromFormat('Y-m-d H:i:s', '2018-04-04 12:02:00'),
            'viewers_count' => 3,
        ]);

        factory(Stream::class)->create([
            'game_id' => 1,
            'period_from' => Carbon::createFromFormat('Y-m-d H:i:s', '2018-04-04 12:00:00'),
            'period_to' => Carbon::createFromFormat('Y-m-d H:i:s', '2018-04-04 12:02:00'),
            'viewers_count' => 5,
        ]);

        factory(Stream::class)->create([
            'game_id' => 1,
            'period_from' => Carbon::now()->subMinutes(3),
            'period_to' => Carbon::now(),
            'viewers_count' => 2,
        ]);

        factory(Stream::class)->create([
            'game_id' => 1,
            'period_from' => Carbon::now()->subMinutes(3),
            'period_to' => Carbon::now(),
            'viewers_count' => 4,
        ]);

        // For exact time
        $response = $this->get('/streams/games/?time=2018-04-04 12:01:00');

        $response->assertStatus(200);
        $data = json_decode($response->getContent(), true);
        $this->assertEquals(8, $data['data'][0]['viewers_count']);

        // For now
        $response = $this->get('/streams/games');

        $response->assertStatus(200);
        $data = json_decode($response->getContent(), true);
        $this->assertEquals(6, $data['data'][0]['viewers_count']);
    }

    public function testAuthenticationNeeded()
    {
        $response = $this->get('/streams/games');

        $response->assertStatus(302);
    }

    public function testBadTimeFormat()
    {
        Passport::actingAs(
            factory(User::class)->create(),
            ['create-servers']
        );

        $response = $this->get('/streams/games?time=2018.08.05');

        $response->assertStatus(400);
    }
}
