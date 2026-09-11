<?php

declare(strict_types=1);

namespace Jeremykenedy\LaravelSeedster\Seeders;

use Illuminate\Database\Seeder;

/**
 * Runs the seeders registered by packages, then the application root seeder.
 */
class RegisteredSeeders extends Seeder
{
    /**
     * @param array<int, string> $registered
     */
    public function __construct(protected array $registered, protected Seeder $root)
    {
    }

    public function run(): void
    {
        foreach ($this->registered as $seeder) {
            $this->call($this->qualify($seeder));
        }

        $this->root->__invoke();
    }

    /**
     * A seeder registered by its short name resolves out of the application
     * seeder namespace, matching how db:seed treats a bare class value.
     */
    protected function qualify(string $seeder): string
    {
        if (str_contains($seeder, '\\')) {
            return $seeder;
        }

        return 'Database\\Seeders\\'.$seeder;
    }
}
