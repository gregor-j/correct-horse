<?php

namespace Tests\GregorJ\CorrectHorse;

use GregorJ\CorrectHorse\Dictionaries\DictionaryFile;
use GregorJ\CorrectHorse\DictionaryInterface;
use PHPUnit\Framework\Exception;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use SebastianBergmann\RecursionContext\InvalidArgumentException;

/**
 * Class DictionaryFileTest
 */
class DictionaryFileTest extends TestCase
{
    /**
     * @return void
     * @throws Exception
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function testInheritance(): void
    {
        $dict = new DictionaryFile('dict-de-lc.txt');
        static::assertInstanceOf(DictionaryInterface::class, $dict);
    }

    /**
     * @return void
     */
    public function testInvalidFile(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('File not found: ');
        new DictionaryFile('invalid-file');
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws \Exception
     */
    public function testGetRandomWord(): void
    {
        $dict = new DictionaryFile('dict-de-lc.txt');
        static::assertNotEmpty($dict->getRandomWord());
    }
}
