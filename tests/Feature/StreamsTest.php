<?php

namespace Tests\Feature;

use App\Stream;
use App\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use Tests\TestCase;

class StreamsTest extends TestCase
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
        ]);

        factory(Stream::class)->create([
            'game_id' => 2,
        ]);

        factory(Stream::class)->create([
            'game_id' => 3,
        ]);

        // Exact game
        $response = $this->get('/streams?games[]=1');

        $response->assertStatus(200);
        $response->assertJsonCount(1, 'data');

        // List of games
        $response = $this->get('/streams?games[]=1&games[]=2');

        $response->assertStatus(200);
        $response->assertJsonCount(2, 'data');

        // All games
        $response = $this->get('/streams');

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
        $stream1 = factory(Stream::class)->create([
            'period_from' => Carbon::createFromFormat('Y-m-d H:i:s', '2018-04-04 12:00:00'),
            'period_to' => Carbon::createFromFormat('Y-m-d H:i:s', '2018-04-04 12:02:00'),
        ]);

        $stream2 = factory(Stream::class)->create([
            'period_from' => Carbon::now()->subMinutes(3),
            'period_to' => Carbon::now(),
        ]);

        // For exact time
        $response = $this->get('/streams?time=2018-04-04 12:01:00');

        $response->assertStatus(200);
        $data = json_decode($response->getContent(), true);
        $this->assertEquals($stream1->id, $data['data'][0]['id']);

        // For now
        $response = $this->get('/streams');

        $response->assertStatus(200);
        $data = json_decode($response->getContent(), true);
        $this->assertEquals($stream2->id, $data['data'][0]['id']);
    }

    public function testAuthenticationNeeded()
    {
        $response = $this->get('/streams');

        $response->assertStatus(302);
    }

    public function testBadTimeFormat()
    {
        Passport::actingAs(
            factory(User::class)->create(),
            ['create-servers']
        );

        $response = $this->get('/streams?time=2018.08.05');

        $response->assertStatus(400);
    }
}
