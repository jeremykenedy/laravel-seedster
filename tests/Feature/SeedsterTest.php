<?php

use Jeremykenedy\Seedster\SeedHandler;

it('can instantiate the SeedHandler class', function () {
    expect(class_exists(SeedHandler::class))->toBeTrue();

    $handler = new SeedHandler();

    expect($handler)->toBeInstanceOf(SeedHandler::class);
});
