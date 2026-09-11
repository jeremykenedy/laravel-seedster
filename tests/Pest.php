<?php

declare(strict_types=1);

use Jeremykenedy\LaravelSeedster\Tests\Fixtures\SeederLog;
use Jeremykenedy\LaravelSeedster\Tests\TestCase;

uses(TestCase::class)
    ->beforeEach(fn () => SeederLog::reset())
    ->in('Feature', 'Unit');
