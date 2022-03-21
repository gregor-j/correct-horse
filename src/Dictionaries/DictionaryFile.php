<?php

namespace GregorJ\CorrectHorse\Dictionaries;

use Exception;
use GregorJ\CorrectHorse\DictionaryInterface;
use RuntimeException;

use function count;
use function dirname;
use function file;
use function file_exists;
use function random_int;
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
     * @throws Exception
     */
    public function getRandomWord(): string
    {
        $lines = file($this->filename);
        $max = count($lines) - 1;
        do {
            $line = trim($lines[random_int(0, $max)]);
        } while ($line === '');
        return $line;
    }
}
