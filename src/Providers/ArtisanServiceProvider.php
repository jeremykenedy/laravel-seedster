<?php

declare(strict_types=1);

namespace Jeremykenedy\LaravelSeedster\Providers;

use Illuminate\Foundation\Providers\ArtisanServiceProvider as ServiceProvider;
use Jeremykenedy\LaravelSeedster\Commands\SeedCommand;

class ArtisanServiceProvider extends ServiceProvider
{
    protected function registerSeedCommand(): void
    {
        $this->app->singleton('command.seed', function ($app) {
            return new SeedCommand($app['db']);
        });
    }
}
