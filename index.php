<?php

declare(strict_types=1);

use Prikkprikkprikk\TextWFC\NGrams\NGrams;

require 'vendor/autoload.php';

$input = file_get_contents('input/norsk_tekst_uten_linjeskift.txt');

$ngrams = new NGrams(input: $input, nGramSize: 4);

echo $ngrams->generateWord(length: 11) . PHP_EOL;
