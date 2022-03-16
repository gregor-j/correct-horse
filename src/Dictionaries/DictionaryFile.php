<?php

namespace GregorJ\CorrectHorse\Dictionaries;

use GregorJ\CorrectHorse\DictionaryInterface;
use RuntimeException;

use function array_rand;
use function dirname;
use function file;
use function file_exists;
use function trim;

/**
 * Class DictionaryFile
 */
final class DictionaryFile implements DictionaryInterface
{
    /**
     * Constants defining the files in the dictionary directory.
     */
    protected const DICT_DIR = 'dict';

    /**
     * @var string
     */
    private $filename;

    /**
     * Initialize the dictionary with a filename from the dict/ subdirectory.
     * @param  string  $filename
     * @throws RuntimeException
     */
    public function __construct(string $filename)
    {
        $this->filename = dirname(__DIR__, 2)
            . DIRECTORY_SEPARATOR
            . self::DICT_DIR
            . DIRECTORY_SEPARATOR
            . $filename;
        if (!file_exists($this->filename)) {
            throw new RuntimeException('File not found: ' . $this->filename);
        }
    }

    /**
     * Get a random word from the dictionary.
     * @return string
     */
    public function getRandomWord(): string
    {
        $lines = file($this->filename);
        $line = '';
        while ($line === '') {
            $line = trim($lines[array_rand($lines)]);
        }
        return $line;
    }
}
