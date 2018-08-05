<?php

namespace App\Stream\Retriever;

use App\DTO\StreamDTO;
use App\Game;
use App\Repository\GamesRepository;
use App\Stream;
use GuzzleHttp\Client;
use Psr\Log\LoggerInterface;
use App\Stream\StreamPersister;

class TwitchRetriever extends RetrieversAbstract
{
    /**
     * @var Client
     */
    protected $client;

    /**
     * @var string
     */
    protected $config;

    /**
     * @var StreamPersister
     */
    protected $persister;

    /**
     * @var GamesRepository
     */
    protected $gamesRepository;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * TwitchRetriever constructor.
     *
     * @param Client          $client
     * @param array           $config
     * @param StreamPersister $persister
     * @param GamesRepository $gamesRepository
     * @param LoggerInterface $logger
     */
    public function __construct(
        Client $client,
        array $config,
        StreamPersister $persister,
        GamesRepository $gamesRepository,
        LoggerInterface $logger
    ) {
        $this->client = $client;
        $this->config = $config;
        $this->persister = $persister;
        $this->gamesRepository = $gamesRepository;
        $this->logger = $logger;
    }

    /**
     * @return bool
     *
     * @throws \Exception
     */
    public function retrieve()
    {
        $games = $this->gamesRepository->all();
        foreach ($games as $game) {
            $this->logger->info(sprintf('Start retrieving %s', $game->title));
            $streams = $this->getStreams($game);
            if (!isset($streams['data'])) {
                continue;
            }

            while (count($streams['data'])) {
                foreach ($streams['data'] as $streamData) {
                    $stream = $this->makeStreamDTO($streamData);
                    $this->persister->persist($stream, $game);
                }
                $streams = $this->getNextPage($game, $streams['pagination']['cursor']);
            }
        }

        return true;
    }

    /**
     * @param Game        $game
     * @param null|string $cursor
     *
     * @return string
     */
    private function getStreamsListUrl(Game $game, ?string $cursor = null): string
    {
        return $this->config['url'].'streams?game_id='.$game->twitch_id.($cursor ? '&after='.$cursor : '');
    }

    /**
     * @return array
     */
    private function getHeaders(): array
    {
        return ['headers' => ['Client-ID' => $this->config['clientId']]];
    }

    /**
     * @param Game        $game
     * @param string|null $cursor
     *
     * @return array|null
     */
    private function getStreams(Game $game, ?string $cursor = null): ?array
    {
        try {
            $res = $this->client->get(
                $this->getStreamsListUrl($game, $cursor),
                $this->getHeaders()
            );
        } catch (\Exception $e) {
            $this->logger->error(
                sprintf('Error while retrieving streams for game %s: %s',
                    $game->title,
                    $e->getMessage())
            );

            return null;
        }

        return json_decode($res->getBody()->getContents(), true);
    }

    /**
     * @param Game   $game
     * @param string $cursor
     *
     * @return array|null
     */
    private function getNextPage(Game $game, string $cursor): ?array
    {
        return $this->getStreams($game, $cursor);
    }

    /**
     * @param array $streamData
     *
     * @return StreamDTO
     */
    private function makeStreamDTO(array $streamData): StreamDTO
    {
        return new StreamDTO(
            $streamData['id'],
            $streamData['user_id'],
            $streamData['game_id'],
            $streamData['title'],
            $streamData['viewer_count'],
            $streamData['language'],
            $streamData['thumbnail_url'],
            Stream::SERVICE_NAME_TWITCH
        );
    }
}
