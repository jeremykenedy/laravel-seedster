<?php

declare(strict_types=1);

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Database\Console\Seeds\SeedCommand as LaravelSeedCommand;
use Jeremykenedy\LaravelSeedster\Commands\SeedCommand;
use Jeremykenedy\LaravelSeedster\Handlers\SeedHandler;

it('binds the seed handler that packages register their seeders with', function (): void {
    expect(app('seed.handler'))->toBeInstanceOf(SeedHandler::class);
});

it('shares one seed handler across resolutions', function (): void {
    expect(app('seed.handler'))->toBe(app('seed.handler'));
});

it('hands artisan the package command for db:seed', function (): void {
    $command = app(Kernel::class)->all()['db:seed'];

    expect($command)->toBeInstanceOf(SeedCommand::class);
});

it('replaces the framework seed command rather than sitting beside it', function (): void {
    expect(app(LaravelSeedCommand::class))->toBeInstanceOf(SeedCommand::class);
});

it('stays a drop in for the framework command so type hints keep resolving', function (): void {
    expect(app(LaravelSeedCommand::class))->toBeInstanceOf(LaravelSeedCommand::class);
});

it('keeps the command.seed alias that shipped before laravel 9', function (): void {
    expect(app('command.seed'))->toBeInstanceOf(SeedCommand::class);
});

it('resolves command.seed and the framework command to the same instance', function (): void {
    expect(app('command.seed'))->toBe(app(LaravelSeedCommand::class));
});

it('keeps db:seed named and described as the framework names it', function (): void {
    $command = app(Kernel::class)->all()['db:seed'];

    expect($command->getName())->toBe('db:seed')
        ->and($command->getDescription())->toBe('Seed the database with records');
});

it('accepts the class argument and the class, database and force options', function (): void {
    $definition = app(Kernel::class)->all()['db:seed']->getDefinition();

    expect($definition->hasArgument('class'))->toBeTrue()
        ->and($definition->hasOption('class'))->toBeTrue()
        ->and($definition->hasOption('database'))->toBeTrue()
        ->and($definition->hasOption('force'))->toBeTrue();
});
