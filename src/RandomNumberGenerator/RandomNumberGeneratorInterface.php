<?php

declare(strict_types=1);

namespace Prikkprikkprikk\TextWFC\RandomNumberGenerator;

/**
 * Interface RandomNumberGeneratorInterface
 *
 * Defines the methods that a random number generator must implement.
 * This interface is used to facilitate testing of otherwise random outcomes.
 */
interface RandomNumberGeneratorInterface {

    /**
     * Returns a random number between the given minimum and maximum values.
     *
     * @param integer $max The maximum value (inclusive).
     * @param integer $min The minimum value (inclusive).
     * @return integer A random number between the given minimum and maximum values.
     */
    public function getRandomNumber(int $min = 0, int $max = PHP_INT_MAX): int;
}