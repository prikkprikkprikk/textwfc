<?php

declare(strict_types=1);

namespace Prikkprikkprikk\TextWFC\RandomNumberGenerator;

use Prikkprikkprikk\TextWFC\RandomNumberGenerator\RandomNumberGeneratorInterface;

/**
 * Class MockRandomNumberGenerator
 *
 * Mock random number generator for testing purposes.
 * Takes an array of numbers and returns them in order,
 * and starts over when the end of the array is reached.
 */
class MockRandomNumberGenerator implements RandomNumberGeneratorInterface {

    /** @var integer The current index in the notSoRandomNumbers array. */
    protected int $currentNumberIndex = 0;

    /**
     * @inheritDoc
     *
     * @param array<int> $notSoRandomNumbers
     */
    public function __construct(
        protected array $notSoRandomNumbers
    ) {
    }

    public function getRandomNumber(int $min = 0, int $max = PHP_INT_MAX): int {
        if ($this->currentNumberIndex >= count($this->notSoRandomNumbers)) {
            $this->currentNumberIndex = 0;
        }
        return $this->notSoRandomNumbers[$this->currentNumberIndex++];
    }
}