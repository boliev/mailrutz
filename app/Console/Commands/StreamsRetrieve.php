<?php

namespace App\Console\Commands;

use App\Stream\Retriever\RetrieverInterface;
use Illuminate\Console\Command;

class StreamsRetrieve extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'streams:retrieve';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Retrieves all streams from all services';

    /**
     * @var array|RetrieverInterface[]
     */
    private $retrievers = [];

    /**
     * StreamsRetrieve constructor.
     * @param RetrieverInterface[] $retrievers
     */
    public function __construct(RetrieverInterface ...$retrievers)
    {
        parent::__construct();
        $this->retrievers = $retrievers;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        foreach($this->retrievers as $retriever) {
            $retriever->retrieve();
        }
    }
}
