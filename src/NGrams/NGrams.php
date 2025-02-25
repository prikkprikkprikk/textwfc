<?php

declare(strict_types=1);

namespace Prikkprikkprikk\TextWFC\NGrams;

use Prikkprikkprikk\TextWFC\NGrams\MGramData;

class NGrams {

    /** @var array<string> */
    protected array $nGrams = [];

    /** @var array<string, MGramData> */
    protected array $mGrams = [];

    /**
     * NGrams constructor.
     *
     * @param string $input
     * @param integer $nGramSize
     */
    public function __construct(
        protected string $input,
        protected int $nGramSize
    ) {
        $this->analyze();
    }

    /**
     * Analyzes the input text and creates an array of n-grams.
     *
     * @return void
     */
    public function analyze() {
        $this->nGrams = [];

        $input = mb_strtolower($this->input);

        // Split the input text into words.
        /* @var $words array<string> */
        $words = preg_split('/\s+/', $input);

        if (!is_array($words)) {
            throw new \RuntimeException('Failed to split input text into words');
        }

        echo "Processing " . count($words) . " words" . PHP_EOL;

        foreach ($words as $word) {
            for ($i = 0; $i <= mb_strlen($word) - $this->nGramSize; $i++) {
                $ngram = mb_substr($word, $i, $this->nGramSize);
                $this->nGrams[] = $ngram;
                $this->addFollowingMGram($ngram);
                $this->addPrecedingMGram($ngram);
            }
        }
    }

    /**
     * Registers the preceding character in the m-gram data for the given n-gram.
     *
     * @param string $ngram The n-gram.
     * @return void
     */
    protected function addPrecedingMGram(string $ngram): void {
        // The m-gram is all but the first character of the n-gram.
        // We don't need to specify the length, because mb_substr() then returns the whole rest of the string.
        $mgram = mb_substr($ngram, 1);
        if (!isset($this->mGrams[$mgram])) {
            $this->mGrams[$mgram] = new MGramData($mgram);
        }
        $this->mGrams[$mgram]->addPrecedingCharacter($ngram[0]);
    }


    /**
     * Registers the following character in the m-gram data for the given n-gram.
     *
     * @param string $ngram The n-gram.
     * @return void
     */
    protected function addFollowingMGram(string $ngram): void {
        // The m-gram is all but the last character of the n-gram.
        $mgram = mb_substr($ngram, 0, $this->nGramSize - 1);
        if (!isset($this->mGrams[$mgram])) {
            $this->mGrams[$mgram] = new MGramData($mgram);
        }
        $this->mGrams[$mgram]->addFollowingCharacter($ngram[$this->nGramSize - 1]);
    }


    /**
     * Generate a word of a given length.
     *
     * @param integer $length The length of the word to generate.
     * @return string The generated word.
     */
    public function generateWord(int $length): string {
        if ($length < $this->nGramSize) {
            throw new \InvalidArgumentException('The word length must be at least the nGramSize');
        }
        // First, get a random n-gram.
        $word = $this->nGrams[array_rand($this->nGrams)];
        echo "Random n-gram: $word" . PHP_EOL;

        $attempts = 0;

        while (mb_strlen($word) < $length) {
            $attempts++;
            if ($attempts > 1000) {
                throw new \RuntimeException('Failed to generate a word after 100 attempts');
            }
            // Check which direction has the least entropy.
            // We do this by checking how many possible characters are in the m-grams
            // for the start and end of the word.
            echo "Word: $word" . PHP_EOL;
            $startMGram = mb_substr($word, 0, $this->nGramSize-1);
            echo "Start m-gram: $startMGram" . PHP_EOL;
            echo "Preceding characters: " . $this->mGrams[$startMGram]->precedingCharactersString() . PHP_EOL;
            $endMGram = mb_substr($word, -($this->nGramSize-1));
            echo "End m-gram: $endMGram" . PHP_EOL;
            echo "Following characters: " . $this->mGrams[$endMGram]->followingCharactersString() . PHP_EOL;
            $precedingEntropy = 0;
            $followingEntropy = 0;
            if (isset($this->mGrams[$startMGram]) && isset($this->mGrams[$endMGram])) {
                echo "Start m-gram: $startMGram" . PHP_EOL;
                $precedingEntropy = array_sum($this->mGrams[$startMGram]->precedingCharacters());
                echo "End m-gram: $endMGram" . PHP_EOL;
                $followingEntropy = array_sum($this->mGrams[$endMGram]->followingCharacters());
            } elseif (isset($this->mGrams[$startMGram])) {
                $precedingEntropy = array_sum($this->mGrams[$startMGram]->precedingCharacters());
                $followingEntropy = 0;
            } elseif (isset($this->mGrams[$endMGram])) {
                $precedingEntropy = 0;
                $followingEntropy = array_sum($this->mGrams[$endMGram]->followingCharacters());
            } else {
                echo "Didn't find entropy for both preceding and following m-grams" . PHP_EOL;
                break;
            }
            if ($precedingEntropy === 0 || $followingEntropy === 0) {
                echo "Preceding entropy or following entropy is 0" . PHP_EOL;
                break;
            }
            echo "Preceding entropy: $precedingEntropy" . PHP_EOL;
            echo "Following entropy: $followingEntropy" . PHP_EOL;
            // We prioritize the m-gram with the higher entropy.
            if ($precedingEntropy > $followingEntropy) {
                $word = $this->mGrams[$startMGram]->getRandomPrecedingCharacter() . $word;
            } else {
                $word .= $this->mGrams[$endMGram]->getRandomFollowingCharacter();
            }
        }

        return $word;
    }

    /**
     * Returns the n-grams in the input text.
     *
     * @return array<string>
     */
    public function get(): array {
        return $this->nGrams;
    }

    /**
     * Returns the number of n-grams in the input text.
     *
     * @return integer
     */
    public function count(): int {
        return count($this->nGrams);
    }

    /**
     * Returns the m-grams in the input text.
     *
     * @return array<string, MGramData>
     */
    public function mGrams(): array {
        return $this->mGrams;
    }
}