<?php

namespace GregorJ\CorrectHorse\Generators;

use GregorJ\CorrectHorse\DictionaryInterface;
use GregorJ\CorrectHorse\RandomGeneratorInterface;

use function in_array;

/**
 * Class RandomWord
 */
final class RandomWord implements RandomGeneratorInterface
{
    use ManageRandomItemsTrait;

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
            $word = $this->dictionary->getRandomWord();
        } while ($this->hasWord($word));
        $this->items[] = $word;
    }
}
