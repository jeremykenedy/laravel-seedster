<?php

declare(strict_types=1);

namespace Jeremykenedy\LaravelSeedster\Handlers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Collection;

class SeedHandler
{
    protected Application $app;

    protected Collection $seeders;

    public function __construct(Application $app, Collection $seeders)
    {
        $this->app = $app;
        $this->seeders = $seeders;
    }

    /**
     * Register one or more seeder classes.
     */
    public function register(string|array $seeder): void
    {
        foreach ((array) $seeder as $seed) {
            $this->seeders->push($seed);
        }
    }

    /**
     * Retrieve all registered seeders.
     */
    public function seeders(): Collection
    {
        return $this->seeders;
    }
}
