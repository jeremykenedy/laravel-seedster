<?php

declare(strict_types=1);

namespace Jeremykenedy\LaravelSeedster\Tests\Fixtures;

use Illuminate\Database\Seeder;

class SecondSeeder extends Seeder
{
    public function run(): void
    {
        SeederLog::record(static::class);
    }
}
