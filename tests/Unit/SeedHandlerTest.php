<?php

declare(strict_types=1);

use Illuminate\Support\Collection;
use Jeremykenedy\LaravelSeedster\Handlers\SeedHandler;
use Jeremykenedy\LaravelSeedster\Tests\Fixtures\FirstSeeder;
use Jeremykenedy\LaravelSeedster\Tests\Fixtures\SecondSeeder;

function handler(): SeedHandler
{
    return new SeedHandler(app(), collect());
}

it('registers a single seeder class name', function (): void {
    $handler = handler();

    $handler->register(FirstSeeder::class);

    expect($handler->seeders()->all())->toBe([FirstSeeder::class]);
});

it('registers an array of seeder class names', function (): void {
    $handler = handler();

    $handler->register([FirstSeeder::class, SecondSeeder::class]);

    expect($handler->seeders()->all())->toBe([FirstSeeder::class, SecondSeeder::class]);
});

it('keeps seeders in the order they were registered', function (): void {
    $handler = handler();

    $handler->register(SecondSeeder::class);
    $handler->register(FirstSeeder::class);

    expect($handler->seeders()->all())->toBe([SecondSeeder::class, FirstSeeder::class]);
});

it('accumulates seeders across separate register calls', function (): void {
    $handler = handler();

    $handler->register(FirstSeeder::class);
    $handler->register([SecondSeeder::class]);

    expect($handler->seeders())->toHaveCount(2);
});

it('returns a collection so callers can filter and map', function (): void {
    expect(handler()->seeders())->toBeInstanceOf(Collection::class);
});

it('reports an empty collection before anything registers', function (): void {
    expect(handler()->seeders()->isEmpty())->toBeTrue();
});

it('resolves from the container as a shared instance so providers can register into it', function (): void {
    app('seed.handler')->register(FirstSeeder::class);

    expect(app('seed.handler')->seeders()->all())->toBe([FirstSeeder::class]);
});
