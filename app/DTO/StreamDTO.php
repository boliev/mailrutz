<?php

namespace App\DTO;

class StreamDTO
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $streamerId;

    /**
     * @var string
     */
    private $gameId;

    /**
     * @var string
     */
    private $title;

    /**
     * @var int
     */
    private $viewerCount;

    /**
     * @var string
     */
    private $language;

    /**
     * @var string
     */
    private $thumbnailUrl;

    /**
     * @var string
     */
    private $serviceName;

    public function __construct(
        string $id,
        string $streamerId,
        string $gameId,
        string $title,
        int $viewerCount,
        string $language,
        string $thumbnailUrl,
        string $serviceName

    )
    {
        $this->id = $id;
        $this->streamerId = $streamerId;
        $this->gameId = $gameId;
        $this->title = $title;
        $this->viewerCount = $viewerCount;
        $this->language = $language;
        $this->thumbnailUrl = $thumbnailUrl;
        $this->serviceName = $serviceName;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getStreamerId(): string
    {
        return $this->streamerId;
    }

    /**
     * @return string
     */
    public function getGameId(): string
    {
        return $this->gameId;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return int
     */
    public function getViewerCount(): int
    {
        return $this->viewerCount;
    }

    /**
     * @return string
     */
    public function getLanguage(): string
    {
        return $this->language;
    }

    /**
     * @return string
     */
    public function getThumbnailUrl(): string
    {
        return $this->thumbnailUrl;
    }

    /**
     * @return string
     */
    public function getServiceName(): string
    {
        return $this->serviceName;
    }
}