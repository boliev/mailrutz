<?php

namespace App\Repository;

use App\Stream;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\Paginator;

class StreamsRepository
{
    /**
     * @param string $id
     * @param string $serviceName
     *
     * @return Stream|null
     */
    public function getById(string $id, string $serviceName): ?Stream
    {
        return Stream::where('stream_id', $id)->where('service_name', $serviceName)->orderBy('id', 'desc')->first();
    }

    /**
     * @param array  $games
     * @param Carbon $time
     *
     * @return Paginator
     */
    public function getActive(array $games, Carbon $time): Paginator
    {
        $results = Stream::where('period_from', '<', $time)->where('period_to', '>', $time);
        if (count($games)) {
            $results->whereIn('game_id', $games);
        }

        return $results->paginate();
    }

    /**
     * @param array  $games
     * @param Carbon $time
     *
     * @return Paginator
     */
    public function getActiveGroupByGames(array $games, Carbon $time): Paginator
    {
        $results = Stream::selectRaw('sum(viewers_count) AS viewers_count, game_id')->where('period_from', '<', $time)->where('period_to', '>', $time);
        if (count($games)) {
            $results->whereIn('game_id', $games);
        }
        $results->groupBy('game_id');

        return $results->paginate();
    }
}
