<?php

declare(strict_types=1);

namespace Jeremykenedy\LaravelSeedster\Providers;

use Illuminate\Database\Console\Seeds\SeedCommand as BaseSeedCommand;
use Illuminate\Foundation\Providers\ArtisanServiceProvider as ServiceProvider;
use Jeremykenedy\LaravelSeedster\Commands\SeedCommand;

class ArtisanServiceProvider extends ServiceProvider
{
    /**
     * Artisan resolves db:seed by the framework command class name, so the
     * replacement is bound against that name instead of a name of its own.
     * The command.seed alias is kept for callers that predate Laravel 9.
     */
    protected function registerSeedCommand(): void
    {
        $this->app->singleton(BaseSeedCommand::class, function ($app) {
            return new SeedCommand($app['db']);
        });

        $this->app->alias(BaseSeedCommand::class, 'command.seed');
        $this->app->alias(BaseSeedCommand::class, SeedCommand::class);
    }
}
