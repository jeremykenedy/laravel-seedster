<?php

declare(strict_types=1);

use Illuminate\Support\ServiceProvider;
use Jeremykenedy\LaravelSeedster\Providers\SeedsterServiceProvider;

it('loads the package translation namespace', function (): void {
    expect(trans('seedster::seedster.registered'))->not->toBe('seedster::seedster.registered');
});

it('pluralises the announcement line', function (): void {
    expect(trans_choice('seedster::seedster.registered', 1))->toBe('Running 1 registered package seeder.')
        ->and(trans_choice('seedster::seedster.registered', 3))->toBe('Running 3 registered package seeders.');
});

it('offers the language file for publishing', function (): void {
    $paths = ServiceProvider::pathsToPublish(SeedsterServiceProvider::class, 'seedster-lang');

    expect($paths)->not->toBeEmpty();

    $source = array_key_first($paths);

    expect(is_file($source.'/en/seedster.php'))->toBeTrue()
        ->and($paths[$source])->toEndWith('vendor/seedster');
});

it('lets an application override the announcement line', function (): void {
    app('translator')->addNamespace('seedster', __DIR__.'/../Fixtures/lang');

    expect(trans_choice('seedster::seedster.registered', 2))->toBe('Overridden for 2.');
});
