<?php

namespace App\Http\Controllers;

use App\Http\Resources\Streams;
use App\Repository\StreamsRepository;
use Carbon\Carbon;

class StreamController extends BaseController
{
    const DELAY = 2;

    /**
     * @param StreamsRepository $streamsRepository
     *
     * @return mixed
     *
     * @throws \Exception
     */
    public function index(StreamsRepository $streamsRepository)
    {
        try {
            $time = $this->extractTime(request()->get('time'));
        } catch (\Exception $e) {
            return $this->throwError('The time field must be in \'Y-m-d H:i:s\' format', 400);
        }
        $games = request()->get('games', []);
        $streams = $streamsRepository->getActive($games, $time);

        $collection = new Streams($streams);
        $collection->appends(request()->except(['page']));

        return $collection;
    }

    /**
     * @param null|string $time
     *
     * @return Carbon
     */
    private function extractTime(?string $time = null): Carbon
    {
        if ($time) {
            return Carbon::createFromFormat('Y-m-d H:i:s', $time);
        } else {
            return Carbon::now()->subMinutes(self::DELAY);
        }
    }
}
