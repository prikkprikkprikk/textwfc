<?php

use Prikkprikkprikk\TextWFC\RandomNumberGenerator\MockRandomNumberGenerator;


test('the mock random number generator returns the correct numbers', function () {

    // Arrange
    $numbers = [1, 2, 3, 4, 5];
    $generator = new MockRandomNumberGenerator(notSoRandomNumbers: $numbers);

    // Act
    $randomNumbers = [];
    for ($i = 0; $i < count($numbers) * 2; $i++) {
        $randomNumbers[] = $generator->getRandomNumber();
    }

    // Assert
    expect($randomNumbers)->toBe([1, 2, 3, 4, 5, 1, 2, 3, 4, 5]);
});


test('the normal random number generator returns numbers within the given range', function () {

    // Arrange
    $generator = new \Prikkprikkprikk\TextWFC\RandomNumberGenerator\RandomNumberGenerator();

    // Act
    $randomNumbers = [];
    for ($i = 0; $i < 10; $i++) {
        $randomNumbers[] = $generator->getRandomNumber(min: 1, max: 10);
    }

    // Assert
    expect($randomNumbers)->toBeArray()
        ->toHaveCount(10)
        ->toContainValuesBetween(1, 10);
});
