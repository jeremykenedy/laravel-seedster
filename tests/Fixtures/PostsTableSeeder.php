<?php

declare(strict_types=1);

namespace Jeremykenedy\LaravelSeedster\Tests\Fixtures;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PostsTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('posts')->insert(['title' => 'Seeded from a package']);
    }
}
