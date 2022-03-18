<?php

namespace Tests\GregorJ\CorrectHorse;

use GregorJ\CorrectHorse\DictionaryInterface;
use GregorJ\CorrectHorse\Generators\RandomUpperCaseWord;
use PHPUnit\Framework\Exception;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\MockObject\RuntimeException;
use PHPUnit\Framework\TestCase;
use SebastianBergmann\RecursionContext\InvalidArgumentException;

/**
 * Class RandomUpperCaseWordTest
 */
class RandomUpperCaseWordTest extends TestCase
{
    /**
     * @return void
     * @throws ExpectationFailedException
     * @throws RuntimeException
     * @throws InvalidArgumentException
     * @throws Exception
     */
    public function testGetRandomWord(): void
    {
        $dict = $this->getMockBuilder(DictionaryInterface::class)->getMock();
        $dict->expects(self::once())
            ->method('getRandomWord')
            ->willReturn('randomword');
        $rand = new RandomUpperCaseWord($dict);
        $rand->add();
        $words = $rand->get();
        static::assertCount(1, $words);
        $word = array_pop($words);
        static::assertSame('RANDOMWORD', $word);
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws RuntimeException
     * @throws Exception
     */
    public function testDuplicateRandomWords(): void
    {
        $dict = $this->getMockBuilder(DictionaryInterface::class)->getMock();
        $dict->expects(self::exactly(3))
            ->method('getRandomWord')
            ->willReturnOnConsecutiveCalls('worda', 'worda', 'wordb');
        $rand = new RandomUpperCaseWord($dict);
        $rand->add();
        $rand->add();
        $words = $rand->get();
        static::assertCount(2, $words);
        $word1 = array_pop($words);
        $word2 = array_pop($words);
        static::assertStringStartsWith('WORD', $word1);
        static::assertStringStartsWith('WORD', $word2);
        if (substr($word1, 4, 1) === 'B') {
            $temp = $word1;
            $word1 = $word2;
            $word2 = $temp;
        }
        static::assertSame('WORDA', $word1);
        static::assertSame('WORDB', $word2);
    }
}
