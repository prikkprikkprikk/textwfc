<?php

declare(strict_types=1);

namespace Prikkprikkprikk\TextWFC\NGrams;

use Prikkprikkprikk\TextWFC\RandomNumberGenerator\RandomNumberGenerator;
use Prikkprikkprikk\TextWFC\RandomNumberGenerator\RandomNumberGeneratorInterface;

/**
 * Class MGramData
 *
 * Data class for storing information about an m-gram.
 *
 * The class name is a play on words, as it stores information about a string
 * that is one character shorter than an n-gram. (n-1=m)
 */
class MGramData {

    /** @var array<string, integer> */
    protected array $precedingCharacters = [];

    /** @var integer The total number of preceding characters for the m-gram. */
    protected int $precedingTotal = 0;

    /** @var array<string, integer> */
    protected array $followingCharacters = [];

    /** @var integer The total number of following characters for the m-gram. */
    protected int $followingTotal = 0;

    /**
     * MGramData constructor.
     *
     * @param string $mGram The m-gram.
     * @param RandomNumberGeneratorInterface $rng The random number generator to use. If not provided, a new instance of RandomNumberGenerator will be created.
     */
    public function __construct(
        protected string $mGram,
        protected RandomNumberGeneratorInterface $rng = new RandomNumberGenerator()
    ) {
    }

    /**
     * Adds a preceding character to the m-gram data.
     *
     * @param string $character
     * @param integer $frequency The frequency of the character in the m-gram.
     * @return void
     */
    public function addPrecedingCharacter(string $character, int $frequency = 1): void
    {
        if (!isset($this->precedingCharacters[$character])) {
            $this->precedingCharacters[$character] = $frequency;
        } else {
            $this->precedingCharacters[$character] += $frequency;
        }
        $this->precedingTotal += $frequency;
    }

    /**
     * Adds a following character to the m-gram data.
     *
     * @param string $character
     * @return void
     */
    public function addFollowingCharacter(string $character, int $frequency = 1): void
    {
        if (!isset($this->followingCharacters[$character])) {
            $this->followingCharacters[$character] = $frequency;
        } else {
            $this->followingCharacters[$character] += $frequency;
        }
        $this->followingTotal += $frequency;
    }

    /**
     * Returns the preceding characters for the m-gram.
     *
     * @return array<string, int>
     */
    public function precedingCharacters(): array
    {
        return $this->precedingCharacters;
    }

    /**
     * Returns the following characters for the m-gram.
     *
     * @return array<string, int>
     */
    public function followingCharacters(): array {
        return $this->followingCharacters;
    }

    /**
     * Returns a random preceding character for the m-gram.
     *
     * @return string
     */
    public function getRandomPrecedingCharacter(): string {
        return $this->getRandomFromWeights($this->precedingCharacters);
    }

    /**
     * Returns a random following character for the m-gram.
     *
     * @return string
     */
    public function getRandomFollowingCharacter(): string {
        return $this->getRandomFromWeights($this->followingCharacters);
    }

    /**
     * Returns a weighted random following character for the m-gram.
     *
     * @param array<string, int> $weights
     */
    public function getRandomFromWeights(array $weights): string
    {
        if (count($weights) === 0) {
            return '';
        }
        $total = array_sum($weights);
        $randomNumber = $this->rng->getRandomNumber(0, $total);
        $cumulative = 0;
        $randomCharacter = '';
        foreach ($weights as $character => $weight) {
            $cumulative += $weight;
            if ($randomNumber <= $cumulative) {
                $randomCharacter = $character;
                break;
            }
        }
        return $randomCharacter;
    }

    /**
     * Return the m-gram.
     *
     * @return string The m-gram.
     */
    public function mGram() {
        return $this->mGram;
    }

    /**
     * Return a string representation of the preceding characters with their frequencies.
     *
     * @return string A string representation of the preceding characters.
     */
    public function precedingCharactersString(): string {
        $string = '';
        foreach ($this->precedingCharacters as $character => $frequency) {
            $string .= $character . ': ' . $frequency . ', ';
        }
        return $string;
    }

    /**
     * Return a string representation of the following characters with their frequencies.
     *
     * @return string A string representation of the following characters.
     */
    public function followingCharactersString(): string {
        $string = '';
        foreach ($this->followingCharacters as $character => $frequency) {
            $string .= $character . ': ' . $frequency . ', ';
        }
        return $string;
    }
}