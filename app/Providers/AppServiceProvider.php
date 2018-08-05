<?php

namespace App\Providers;

use App\Console\Commands\StreamsRetrieve;
use App\Repository\GamesRepository;
use App\Repository\StreamsRepository;
use App\Stream\StreamPersister;
use App\Stream\Retriever\TwitchRetriever;
use GuzzleHttp\Client;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;
use Psr\Log\LoggerInterface;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
    }

    /**
     * Register any application services.
     */
    public function register()
    {
        $this->app->bind(GamesRepository::class, function (Application $app) {
            return new GamesRepository();
        });

        $this->app->bind(StreamsRepository::class, function (Application $app) {
            return new StreamsRepository();
        });

        $this->app->bind(StreamPersister::class, function (Application $app) {
            return new StreamPersister(
               $app->get(StreamsRepository::class),
               $app->get(LoggerInterface::class)
           );
        });

        $this->app->bind(TwitchRetriever::class, function (Application $app) {
            return new TwitchRetriever(
                new Client(),
                Config::get('services.twitch'),
                $app->get(StreamPersister::class),
                $app->get(GamesRepository::class),
                $app->get(LoggerInterface::class)
            );
        });

        $this->app->bind(StreamsRetrieve::class, function (Application $app) {
            return new StreamsRetrieve($app->get(TwitchRetriever::class));
        });
    }
}
