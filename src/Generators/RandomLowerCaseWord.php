<?php

namespace GregorJ\CorrectHorse\Generators;

use GregorJ\CorrectHorse\RandomGeneratorInterface;

/**
 * Class RandomLowerCaseWord
 */
final class RandomLowerCaseWord implements RandomGeneratorInterface
{
    use ManageRandomItemsTrait;
    use DictionaryWordTrait;

    /**
     * @return string
     */
    private function getRandomWord(): string
    {
        return strtolower($this->dictionary->getRandomWord());
    }
}
