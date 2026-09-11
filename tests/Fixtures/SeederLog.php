<?php

declare(strict_types=1);

namespace Jeremykenedy\LaravelSeedster\Tests\Fixtures;

/**
 * Records which seeders ran, and in what order.
 */
class SeederLog
{
    /** @var array<int, string> */
    public static array $ran = [];

    public static function record(string $seeder): void
    {
        static::$ran[] = $seeder;
    }

    public static function reset(): void
    {
        static::$ran = [];
        CountingRootSeeder::$constructed = 0;
    }
}
