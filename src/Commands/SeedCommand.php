<?php

declare(strict_types=1);

namespace Jeremykenedy\LaravelSeedster\Commands;

use Illuminate\Console\Command;
use Illuminate\Console\ConfirmableTrait;
use Illuminate\Database\ConnectionResolverInterface as Resolver;
use Illuminate\Database\Eloquent\Model;
use Symfony\Component\Console\Input\InputOption;

class SeedCommand extends Command
{
    use ConfirmableTrait;

    protected $name = 'db:seed';

    protected $description = 'Seed the database with records';

    protected Resolver $resolver;

    public function __construct(Resolver $resolver)
    {
        parent::__construct();

        $this->resolver = $resolver;
    }

    public function handle(): int
    {
        if (! $this->confirmToProceed()) {
            return 1;
        }

        $this->resolver->setDefaultConnection($this->getDatabase());

        $seeders = $this->seeders();
        $seeders->push($this->input->getOption('class'));

        $seeders->each(function ($seeder) {
            Model::unguarded(function () use ($seeder) {
                $this->getSeeder($seeder)->__invoke();
            });
        });

        $this->info('Database seeding completed successfully.');

        return 0;
    }

    protected function getSeeder(string $class): \Illuminate\Database\Seeder
    {
        if (strpos($class, '\\') === false) {
            $class = 'Database\\Seeders\\' . $class;
        }

        if ($class === 'Database\\Seeders\\DatabaseSeeder' && ! class_exists($class)) {
            $class = 'DatabaseSeeder';
        }

        $instance = $this->laravel->make($class);

        return $instance->setContainer($this->laravel)->setCommand($this);
    }

    protected function getDatabase(): string
    {
        $database = $this->input->getOption('database');

        return $database ?: $this->laravel['config']['database.default'];
    }

    protected function getOptions(): array
    {
        return [
            ['class', null, InputOption::VALUE_OPTIONAL, 'The class name of the root seeder', 'Database\\Seeders\\DatabaseSeeder'],
            ['database', null, InputOption::VALUE_OPTIONAL, 'The database connection to seed'],
            ['force', null, InputOption::VALUE_NONE, 'Force the operation to run when in production'],
        ];
    }

    protected function seeders(): \Illuminate\Support\Collection
    {
        return app('seed.handler')->seeders();
    }
}
