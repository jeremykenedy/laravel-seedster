<?php

declare(strict_types=1);

namespace Jeremykenedy\LaravelSeedster\Tests\Fixtures;

use Illuminate\Database\Seeder;

class CountingRootSeeder extends Seeder
{
    public static int $constructed = 0;

    public function __construct()
    {
        static::$constructed++;
    }

    public function run(): void
    {
        SeederLog::record(static::class);
    }
}
