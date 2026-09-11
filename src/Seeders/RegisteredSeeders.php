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
     * @param class-string<Seeder> $root
     */
    public function __construct(protected array $registered, protected string $root)
    {
    }

    public function run(): void
    {
        foreach ($this->registered as $seeder) {
            $this->call($this->qualify($seeder));
        }

        $this->call($this->root);
    }

    /**
     * A seeder registered by its short name resolves out of the application
     * seeder namespace, falling back to a global DatabaseSeeder when that is
     * where the application keeps it. This is what db:seed does with a bare
     * class value, and what this package has always done with a short name.
     */
    protected function qualify(string $seeder): string
    {
        if (!str_contains($seeder, '\\')) {
            $seeder = 'Database\\Seeders\\'.$seeder;
        }

        if ($seeder === 'Database\\Seeders\\DatabaseSeeder' && !class_exists($seeder)) {
            return 'DatabaseSeeder';
        }

        return $seeder;
    }
}
