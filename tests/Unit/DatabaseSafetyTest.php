<?php

declare(strict_types=1);

use Illuminate\Support\Facades\DB;

it('runs against sqlite only', function (): void {
    expect(config('database.default'))->toBe('testing')
        ->and(config('database.connections.testing.driver'))->toBe('sqlite');
});

it('runs against an in memory database so no real data can be seeded', function (): void {
    expect(config('database.connections.testing.database'))->toBe(':memory:')
        ->and(DB::connection()->getDatabaseName())->toBe(':memory:');
});
