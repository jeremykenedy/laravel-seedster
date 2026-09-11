<?php

declare(strict_types=1);

use Illuminate\Database\Seeder;
use Jeremykenedy\LaravelSeedster\Tests\Fixtures\SeederLog;

/**
 * Applications that predate the Database\Seeders namespace keep their root
 * seeder in the global namespace. Both db:seed and this package fall back to
 * it, so the suite needs one to fall back to.
 */
class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        SeederLog::record('DatabaseSeeder');
    }
}
