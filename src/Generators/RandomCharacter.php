<?php

namespace GregorJ\CorrectHorse\Generators;

use Exception;
use GregorJ\CorrectHorse\RandomGeneratorInterface;

use function array_diff;
use function array_values;
use function count;
use function in_array;
use function random_int;
use function substr;

/**
 * Class RandomCharacter
 */
final class RandomCharacter implements RandomGeneratorInterface
{
    use ManageRandomItemsTrait;

    /**
     * Define a list of default characters in case none are provided.
     */
    public const DEFAULT_CHARS = ['-', '#', '.', ',', '+', ';', '*'];

    /**
     * @var string[]
     */
    private $chars = [];

    /**
     * Initialize the class with an array of special characters.
     * @param  array  $chars
     */
    public function __construct(array $chars = [])
    {
        if ($chars !== []) {
            foreach ($chars as $char) {
                $this->addChar($char);
            }
        }
        if ($this->chars === []) {
            $this->chars = self::DEFAULT_CHARS;
        }
    }

    /**
     * Add a special character to the list of special characters.
     * @param  string  $char
     * @return void
     */
    public function addChar(string $char): void
    {
        $char = substr($char, 0, 1);
        if (!$this->hasChar($char) && $char !== '') {
            $this->chars[] = $char;
        }
    }

    /**
     * @param  string  $char
     * @return bool
     */
    public function hasChar(string $char): bool
    {
        return in_array($char, $this->chars);
    }

    /**
     * Add a randomly generated item.
     * @return void
     * @throws Exception
     */
    public function add(): void
    {
        $chars = array_values(array_diff($this->chars, $this->items));
        if ($chars === []) {
            return;
        }
        $max = count($chars) - 1;
        $this->items[] = $chars[random_int(0, $max)];
    }
}
