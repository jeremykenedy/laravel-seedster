<?php

declare(strict_types=1);

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Jeremykenedy\LaravelSeedster\Tests\Fixtures\FirstSeeder;
use Jeremykenedy\LaravelSeedster\Tests\Fixtures\PostsTableSeeder;
use Jeremykenedy\LaravelSeedster\Tests\Fixtures\RootSeeder;
use Jeremykenedy\LaravelSeedster\Tests\Fixtures\SecondSeeder;
use Jeremykenedy\LaravelSeedster\Tests\Fixtures\SeederLog;
use Jeremykenedy\LaravelSeedster\Tests\Fixtures\ShortNameSeeder;

it('runs a seeder that a package registered with the handler', function (): void {
    app('seed.handler')->register(FirstSeeder::class);

    $this->artisan('db:seed', ['--class' => RootSeeder::class])->assertExitCode(0);

    expect(SeederLog::$ran)->toContain(FirstSeeder::class);
});

it('runs registered seeders before the root seeder', function (): void {
    app('seed.handler')->register([FirstSeeder::class, SecondSeeder::class]);

    $this->artisan('db:seed', ['--class' => RootSeeder::class])->assertExitCode(0);

    expect(SeederLog::$ran)->toBe([
        FirstSeeder::class,
        SecondSeeder::class,
        RootSeeder::class,
    ]);
});

it('still runs the root seeder when nothing is registered', function (): void {
    $this->artisan('db:seed', ['--class' => RootSeeder::class])->assertExitCode(0);

    expect(SeederLog::$ran)->toBe([RootSeeder::class]);
});

it('falls back to a global database seeder when no class is given', function (): void {
    expect(class_exists('Database\\Seeders\\DatabaseSeeder'))->toBeFalse();

    app('seed.handler')->register(FirstSeeder::class);

    $this->artisan('db:seed')->assertExitCode(0);

    expect(SeederLog::$ran)->toBe([FirstSeeder::class, 'DatabaseSeeder']);
});

it('takes the root seeder from the class argument', function (): void {
    app('seed.handler')->register(FirstSeeder::class);

    $this->artisan('db:seed', ['class' => RootSeeder::class])->assertExitCode(0);

    expect(SeederLog::$ran)->toBe([FirstSeeder::class, RootSeeder::class]);
});

it('resolves a seeder registered by short name out of the application seeder namespace', function (): void {
    class_alias(ShortNameSeeder::class, 'Database\\Seeders\\ShortNameSeeder');

    app('seed.handler')->register('ShortNameSeeder');

    $this->artisan('db:seed', ['--class' => RootSeeder::class])->assertExitCode(0);

    expect(SeederLog::$ran)->toBe([ShortNameSeeder::class, RootSeeder::class]);
});

it('resolves a registered short name to the global database seeder when there is no namespaced one', function (): void {
    app('seed.handler')->register('DatabaseSeeder');

    $this->artisan('db:seed', ['--class' => RootSeeder::class])->assertExitCode(0);

    expect(SeederLog::$ran)->toBe(['DatabaseSeeder', RootSeeder::class]);
});

it('reports the root seeder by name alongside the registered ones', function (): void {
    app('seed.handler')->register(FirstSeeder::class);

    $this->artisan('db:seed', ['--class' => RootSeeder::class])
        ->expectsOutputToContain(FirstSeeder::class)
        ->expectsOutputToContain(RootSeeder::class)
        ->assertExitCode(0);
});

it('writes rows through a registered seeder', function (): void {
    Schema::create('posts', function ($table): void {
        $table->increments('id');
        $table->string('title');
    });

    app('seed.handler')->register(PostsTableSeeder::class);

    $this->artisan('db:seed', ['--class' => RootSeeder::class])->assertExitCode(0);

    expect(DB::table('posts')->pluck('title')->all())->toBe(['Seeded from a package']);
});

it('announces how many package seeders it is about to run', function (): void {
    app('seed.handler')->register([FirstSeeder::class, SecondSeeder::class]);

    $this->artisan('db:seed', ['--class' => RootSeeder::class])
        ->expectsOutputToContain('Running 2 registered package seeders.')
        ->assertExitCode(0);
});

it('announces a single package seeder in the singular', function (): void {
    app('seed.handler')->register(FirstSeeder::class);

    $this->artisan('db:seed', ['--class' => RootSeeder::class])
        ->expectsOutputToContain('Running 1 registered package seeder.')
        ->assertExitCode(0);
});

it('says nothing about package seeders when none are registered', function (): void {
    $this->artisan('db:seed', ['--class' => RootSeeder::class])
        ->doesntExpectOutputToContain('registered package')
        ->assertExitCode(0);
});

it('leaves the default connection alone after seeding another connection', function (): void {
    app('seed.handler')->register(FirstSeeder::class);

    $this->artisan('db:seed', [
        '--class'    => RootSeeder::class,
        '--database' => 'secondary',
    ])->assertExitCode(0);

    expect(DB::getDefaultConnection())->toBe('testing');
});

it('lets a package register its seeders while the handler is being resolved', function (): void {
    app()->afterResolving('seed.handler', function ($handler): void {
        $handler->register(FirstSeeder::class);
    });

    $this->artisan('db:seed', ['--class' => RootSeeder::class])->assertExitCode(0);

    expect(SeederLog::$ran)->toBe([FirstSeeder::class, RootSeeder::class]);
});

it('runs in production when forced', function (): void {
    app()->detectEnvironment(fn (): string => 'production');

    app('seed.handler')->register(FirstSeeder::class);

    $this->artisan('db:seed', [
        '--class' => RootSeeder::class,
        '--force' => true,
    ])->assertExitCode(0);

    expect(SeederLog::$ran)->toBe([FirstSeeder::class, RootSeeder::class]);
});
