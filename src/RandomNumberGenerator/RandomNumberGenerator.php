<?php

declare(strict_types=1);

namespace Prikkprikkprikk\TextWFC\RandomNumberGenerator;

class RandomNumberGenerator implements RandomNumberGeneratorInterface {

    /**
     * Returns a random number between the given minimum and maximum values
     * using the mt_rand() function.
     *
     * @inheritDoc
     */
    public function getRandomNumber(int $min = 0, int $max = PHP_INT_MAX): int {
        return mt_rand($min, $max);
    }
}