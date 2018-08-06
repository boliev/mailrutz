<?php

namespace App\Stream;

use App\DTO\StreamDTO;
use App\Game;
use App\Repository\StreamsRepository;
use App\Stream;
use Carbon\Carbon;
use Psr\Log\LoggerInterface;

class StreamPersister
{
    /**
     * @var StreamsRepository
     */
    protected $streamRepository;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * TwitchPersister constructor.
     *
     * @param StreamsRepository $streamsRepository
     */
    public function __construct(StreamsRepository $streamsRepository, LoggerInterface $logger)
    {
        $this->streamRepository = $streamsRepository;
        $this->logger = $logger;
    }

    /**
     * @param StreamDTO $streamDto
     * @param Game      $game
     *
     * @throws \Exception
     */
    public function persist(StreamDTO $streamDto, Game $game)
    {
        $now = Carbon::now();
        $lastPeriod = $this->getLastPeriod($streamDto);

        $stream = new Stream();
        $stream->game_id = $game->id;
        $stream->title = $streamDto->getTitle();
        $stream->service_name = $streamDto->getServiceName();
        $stream->streamer_id = $streamDto->getStreamerId();
        $stream->stream_id = $streamDto->getId();
        $stream->language = $streamDto->getLanguage();
        $stream->viewers_count = $streamDto->getViewerCount();
        $stream->period_from = $lastPeriod;
        $stream->period_to = $now;
        $stream->save();
        $this->logger->info(
            sprintf('New stream %s was created: %s',
                $streamDto->getServiceName(),
                $streamDto->getId())
        );
    }

    /**
     * @param Stream $stream
     *
     * @return \DateTime
     *
     * @throws \Exception
     */
    private function getLastPeriod(StreamDTO $stream): \DateTime
    {
        $lastCount = $this->streamRepository->getById($stream->getId(), $stream->getServiceName());
        if ($lastCount) {
            return Carbon::createFromFormat('Y-m-d H:i:s', $lastCount->period_to)->addSecond();
        } else {
            return Carbon::now();
        }
    }
}
