<?php

declare(strict_types=1);

namespace Jeremykenedy\LaravelSeedster\Providers;

use Illuminate\Database\Console\Seeds\SeedCommand as BaseSeedCommand;
use Illuminate\Support\ServiceProvider;
use Jeremykenedy\LaravelSeedster\Commands\SeedCommand;
use Jeremykenedy\LaravelSeedster\Handlers\SeedHandler;

class SeedsterServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadTranslationsFrom(__DIR__.'/../../resources/lang', 'seedster');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../../resources/lang' => $this->app->langPath('vendor/seedster'),
            ], 'seedster-lang');
        }
    }

    public function register(): void
    {
        $this->app->register(ArtisanServiceProvider::class);

        $this->replaceSeedCommand();

        $this->app->singleton('seed.handler', function ($app) {
            return new SeedHandler($app, collect());
        });
    }

    /**
     * The framework registers its own seed command from a deferred provider
     * that loads after this one, so binding alone would be handed straight
     * back. Extending survives that later binding.
     */
    private function replaceSeedCommand(): void
    {
        $this->app->extend(BaseSeedCommand::class, function ($command, $app) {
            return $command instanceof SeedCommand ? $command : new SeedCommand($app['db']);
        });
    }
}
