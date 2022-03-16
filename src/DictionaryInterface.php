<?php

namespace GregorJ\CorrectHorse;

/**
 * Interface DictionaryInterface
 *
 * The dictionary interface
 */
interface DictionaryInterface
{
    /**
     * Get a random word from the dictionary.
     * @return string
     */
    public function getRandomWord(): string;
}
