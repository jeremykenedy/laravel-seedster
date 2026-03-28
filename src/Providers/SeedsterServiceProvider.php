<?php

declare(strict_types=1);

namespace Jeremykenedy\LaravelSeedster\Providers;

use Illuminate\Support\ServiceProvider;
use Jeremykenedy\LaravelSeedster\Commands\SeedCommand;
use Jeremykenedy\LaravelSeedster\Handlers\SeedHandler;

class SeedsterServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        //
    }

    public function register(): void
    {
        $this->app->register(ArtisanServiceProvider::class);

        $this->app->singleton('seed.handler', function ($app) {
            return new SeedHandler($app, collect());
        });

        $this->app->singleton('command.seed', function ($app) {
            return new SeedCommand($app['db']);
        });
    }
}
