<?php

namespace GregorJ\CorrectHorse\Generators;

use GregorJ\CorrectHorse\DictionaryInterface;

/**
 * Trait DictionaryWordTrait
 */
trait DictionaryWordTrait
{
    /**
     * @var DictionaryInterface
     */
    private $dictionary;

    /**
     * @param  DictionaryInterface  $dictionary
     */
    public function __construct(DictionaryInterface $dictionary)
    {
        $this->dictionary = $dictionary;
        $this->reset();
    }

    /**
     * Has a random number already been generated?
     * @param  string  $word
     * @return bool
     */
    private function hasWord(string $word): bool
    {
        return in_array($word, $this->items, true);
    }

    /**
     * Add a randomly generated word.
     * @return void
     */
    public function add(): void
    {
        do {
            $word = $this->getRandomWord();
        } while ($this->hasWord($word));
        $this->items[] = $word;
    }

    // PHP > 8.0
    //abstract private function getRandomWord(): string;
}
