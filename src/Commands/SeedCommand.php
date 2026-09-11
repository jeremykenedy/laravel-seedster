<?php

declare(strict_types=1);

namespace Jeremykenedy\LaravelSeedster\Commands;

use Illuminate\Database\Console\Seeds\SeedCommand as BaseSeedCommand;
use Illuminate\Database\Seeder;
use Jeremykenedy\LaravelSeedster\Seeders\RegisteredSeeders;

/**
 * Extends the framework db:seed command so seeders registered through the
 * seed handler run ahead of the application root seeder.
 */
class SeedCommand extends BaseSeedCommand
{
    protected function getSeeder(): Seeder
    {
        $root = parent::getSeeder();

        $registered = $this->registeredSeeders();

        if ($registered === []) {
            return $root;
        }

        $this->components->info(
            trans_choice('seedster::seedster.registered', count($registered))
        );

        return (new RegisteredSeeders($registered, $root))
            ->setContainer($this->laravel)
            ->setCommand($this);
    }

    /**
     * @return array<int, string>
     */
    protected function registeredSeeders(): array
    {
        return $this->laravel['seed.handler']->seeders()->all();
    }
}
