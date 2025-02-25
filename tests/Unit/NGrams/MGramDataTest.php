<?php

declare(strict_types=1);

use Prikkprikkprikk\TextWFC\NGrams\MGramData;
use Prikkprikkprikk\TextWFC\RandomNumberGenerator\MockRandomNumberGenerator;


it('correctly generates an MGramData object', function () {

    // Arrange
    $mgram = 'bc';
    $data = new MGramData(mGram: $mgram);

    // Act
    $data->addPrecedingCharacter('a');
    $data->addFollowingCharacter('d');

    // Assert
    expect($data->precedingCharacters())->toBe(['a' => 1]);
    expect($data->followingCharacters())->toBe(['d' => 1]);

    expect($data->getRandomPrecedingCharacter())->toBe('a');
    expect($data->getRandomFollowingCharacter())->toBe('d');

    // Act more
    $data->addPrecedingCharacter('a');
    $data->addPrecedingCharacter('e');
    $data->addFollowingCharacter('f');

    // Assert
    expect($data->precedingCharacters())->toBe(['a' => 2, 'e' => 1]);
    expect($data->followingCharacters())->toBe(['d' => 1, 'f' => 1]);
});


it('returns the correct random preceding character', function () {
    // Arrange
    $mgram = 'bc';
    $rng = new MockRandomNumberGenerator(notSoRandomNumbers: [1, 3, 4, 6, 7, 9]);

    $data = new MGramData(mGram: $mgram, rng: $rng);

    // Act
    $data->addPrecedingCharacter(character: 'a', frequency: 3);
    $data->addPrecedingCharacter(character: 'e', frequency: 3);
    $data->addPrecedingCharacter(character: 'f', frequency: 3);

    // Assert
    expect($data->getRandomPrecedingCharacter())->toBe('a')
        ->and($data->getRandomPrecedingCharacter())->toBe('a')
        ->and($data->getRandomPrecedingCharacter())->toBe('e')
        ->and($data->getRandomPrecedingCharacter())->toBe('e')
        ->and($data->getRandomPrecedingCharacter())->toBe('f')
        ->and($data->getRandomPrecedingCharacter())->toBe('f')
    ;
});


it('returns the correct random following character', function () {
    // Arrange
    $mgram = 'bc';
    $rng = new MockRandomNumberGenerator(notSoRandomNumbers: [1, 3, 4, 6, 7, 9]);

    $data = new MGramData(mGram: $mgram, rng: $rng);

    // Act
    $data->addPrecedingCharacter(character: 'q', frequency: 3);
    $data->addPrecedingCharacter(character: 'r', frequency: 3);
    $data->addPrecedingCharacter(character: 's', frequency: 3);

    // Assert
    expect($data->getRandomPrecedingCharacter())->toBe('q')
        ->and($data->getRandomPrecedingCharacter())->toBe('q')
        ->and($data->getRandomPrecedingCharacter())->toBe('r')
        ->and($data->getRandomPrecedingCharacter())->toBe('r')
        ->and($data->getRandomPrecedingCharacter())->toBe('s')
        ->and($data->getRandomPrecedingCharacter())->toBe('s')
    ;
});

