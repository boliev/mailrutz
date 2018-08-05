<?php
namespace App\Repository;

use App\Stream;

class StreamsRepository
{
    /**
     * @param string $id
     * @param string $serviceName
     * @return Stream|null
     */
    public function getById(string $id, string $serviceName): ?Stream
    {
        return Stream::where('stream_id', $id)->where('service_name', $serviceName)->first();
    }
}