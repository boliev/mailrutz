<?php

namespace App\Http\Controllers;

use App\Exceptions\BadTimeFormatException;
use App\Http\Resources\Streams;
use App\Repository\StreamsRepository;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\Paginator;

class StreamController extends Controller
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
        $time = $this->extractTime(request()->get('time'));
        $games = request()->get('games', []);

        $streams = $streamsRepository->getActive($games, $time);

        return $this->makePaginatedStreamCollection($streams);
    }

    /**
     * @param StreamsRepository $streamsRepository
     *
     * @return mixed
     *
     * @throws \Exception
     */
    public function byGames(StreamsRepository $streamsRepository)
    {
        $time = $this->extractTime(request()->get('time'));
        $games = request()->get('games', []);

        $streams = $streamsRepository->getActiveGroupByGames($games, $time);

        return $this->makePaginatedStreamCollection($streams);
    }

    /**
     * @param null|string $time
     *
     * @return Carbon
     *
     * @throws BadTimeFormatException
     */
    private function extractTime(?string $time = null): Carbon
    {
        if ($time) {
            try {
                return Carbon::createFromFormat('Y-m-d H:i:s', $time);
            } catch (\Exception $e) {
                throw new BadTimeFormatException();
            }
        } else {
            return Carbon::now()->subMinutes(self::DELAY);
        }
    }

    /**
     * @param Paginator $streams
     *
     * @return Streams
     */
    private function makePaginatedStreamCollection(Paginator $streams): Streams
    {
        $collection = new Streams($streams);
        $collection->appends(request()->except(['page']));

        return $collection;
    }
}
