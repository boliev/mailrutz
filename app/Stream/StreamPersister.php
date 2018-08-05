<?php
namespace App\Stream;

use App\DTO\StreamDTO;
use App\Exceptions\StreamViewersNotFoundException;
use App\Game;
use App\Repository\StreamsRepository;
use App\Stream;
use App\StreamViewers;

class StreamPersister
{
    /**
     * @var StreamsRepository
     */
    protected $streamRepository;

    /**
     * TwitchPersister constructor.
     * @param StreamsRepository $streamsRepository
     */
    public function __construct(StreamsRepository $streamsRepository)
    {
        $this->streamRepository = $streamsRepository;
    }

    /**
     * @param StreamDTO $streamDto
     * @param Game $game
     * @throws \Exception
     */
    public function persist(StreamDTO $streamDto, Game $game)
    {
        $stream = $this->streamRepository->getById($streamDto->getId(), $streamDto->getServiceName());
        if(null === $stream) {
            // new stream
            $this->createStream($streamDto, $game);
        } else {
            // stream exists - update the viewers count
            $this->updateStream($streamDto, $stream);
        }
    }

    /**
     * @param StreamDTO $streamDto
     * @param Game $game
     */
    private function createStream(StreamDTO $streamDto, Game $game): void
    {
        $stream = new Stream();
        /** TODO: pass game entitiy */
        $stream->game_id = $game->id;
        $stream->title = $streamDto->getTitle();
        $stream->service_name = $streamDto->getServiceName();
        $stream->streamer_id = $streamDto->getStreamerId();
        $stream->stream_id = $streamDto->getId();
        $stream->language = $streamDto->getLanguage();
        $stream->thumbnail_url = $streamDto->getThumbnailUrl();
        $stream->save();
        $now = new \DateTime();
        $this->createStreamViewers($streamDto, $stream, $now, $now);

    }

    /**
     * @param StreamDTO $streamDto
     * @param Stream $stream
     * @throws StreamViewersNotFoundException
     * @throws \Exception
     */
    private function updateStream(StreamDTO $streamDto, Stream $stream): void
    {
        $lastCount = $stream->streamViewers()->get()->last();
        if(!$lastCount) {
            throw new StreamViewersNotFoundException(
                sprintf('Stream viewers not found for stream %d', $stream->id)
            );
        }
        $lastPeriod = (new \DateTime($lastCount->period_to))->add(new \DateInterval('PT1S'));
        $this->createStreamViewers($streamDto, $stream, $lastPeriod, new \DateTime());
    }

    /**
     * @param array $streamDto
     * @param Stream $stream
     * @param \DateTime $periodFrom
     * @param \DateTime $periodTo
     */
    private function createStreamViewers(
        StreamDTO $streamDto,
        Stream $stream,
        \DateTime $periodFrom,
        \DateTime $periodTo
    ) :void
    {
        $streamViewers = new StreamViewers();
        $streamViewers->stream_id = $stream->id;
        $streamViewers->count = $streamDto->getViewerCount();
        $streamViewers->period_from =$periodFrom;
        $streamViewers->period_to = $periodTo;
        $streamViewers->save();
    }
}