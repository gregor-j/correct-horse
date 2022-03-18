<?php

namespace GregorJ\CorrectHorse\Generators;

use GregorJ\CorrectHorse\RandomGeneratorInterface;

/**
 * Class RandomUpperCaseWord
 */
final class RandomUpperCaseWord implements RandomGeneratorInterface
{
    use ManageRandomItemsTrait;
    use DictionaryWordTrait;

    /**
     * @return string
     */
    private function getRandomWord(): string
    {
        return strtoupper($this->dictionary->getRandomWord());
    }
}
