<?php

namespace Tests\GregorJ\CorrectHorse;

use GregorJ\CorrectHorse\DictionaryInterface;
use GregorJ\CorrectHorse\Generators\RandomWord;
use GregorJ\CorrectHorse\Generators\RandomWords;
use PHPUnit\Framework\Exception;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\MockObject\RuntimeException;
use PHPUnit\Framework\TestCase;
use SebastianBergmann\RecursionContext\InvalidArgumentException;

/**
 * Class RandomWordsTest
 */
class RandomWordsTest extends TestCase
{
    /**
     * @return void
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws RuntimeException
     */
    public function testEmptyWords(): void
    {
        $lowerCaseDict = $this->getMockBuilder(DictionaryInterface::class)->getMock();
        $upperCaseDict = $this->getMockBuilder(DictionaryInterface::class)->getMock();
        $rand = new RandomWords(new RandomWord($lowerCaseDict), new RandomWord($upperCaseDict));
        static::assertFalse($rand->has());
        static::assertSame([], $rand->get());
    }

    /**
     * @return void
     * @throws Exception
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws RuntimeException
     */
    public function testReset(): void
    {
        $lowerCaseDict = $this->getMockBuilder(DictionaryInterface::class)->getMock();
        $lowerCaseDict->expects(self::once())
            ->method('getRandomWord')
            ->willReturn('worda');
        $upperCaseDict = $this->getMockBuilder(DictionaryInterface::class)->getMock();

        $lowerCase = new RandomWord($lowerCaseDict);
        $lowerCase->add();
        $upperCase = new RandomWord($upperCaseDict);

        $rand = new RandomWords($lowerCase, $upperCase);
        static::assertTrue($rand->has());
        static::assertCount(1, $rand->get());
        $rand->reset();
        static::assertFalse($rand->has());
        static::assertSame([], $rand->get());
    }

    /**
     * @return void
     * @throws Exception
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws RuntimeException
     */
    public function testRemoveLowerCase(): void
    {
        $lowerCaseDict = $this->getMockBuilder(DictionaryInterface::class)->getMock();
        $lowerCaseDict->expects(self::once())
            ->method('getRandomWord')
            ->willReturn('worda');
        $upperCaseDict = $this->getMockBuilder(DictionaryInterface::class)->getMock();

        $lowerCase = new RandomWord($lowerCaseDict);
        $lowerCase->add();
        $upperCase = new RandomWord($upperCaseDict);

        $rand = new RandomWords($lowerCase, $upperCase);
        static::assertTrue($rand->has());
        static::assertCount(1, $rand->get());
        $rand->remove();
        static::assertFalse($rand->has());
        static::assertSame([], $rand->get());
    }

    /**
     * @return void
     * @throws Exception
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws RuntimeException
     */
    public function testRemoveUpperCase(): void
    {
        $lowerCaseDict = $this->getMockBuilder(DictionaryInterface::class)->getMock();
        $upperCaseDict = $this->getMockBuilder(DictionaryInterface::class)->getMock();
        $upperCaseDict->expects(self::once())
            ->method('getRandomWord')
            ->willReturn('WORDA');

        $lowerCase = new RandomWord($lowerCaseDict);
        $upperCase = new RandomWord($upperCaseDict);
        $upperCase->add();

        $rand = new RandomWords($lowerCase, $upperCase);
        static::assertTrue($rand->has());
        static::assertCount(1, $rand->get());
        $rand->remove();
        static::assertFalse($rand->has());
        static::assertSame([], $rand->get());
    }

    /**
     * @return void
     * @throws Exception
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws RuntimeException
     */
    public function testRemove(): void
    {
        // in 10 rounds rand(0,1) should produce at least one different result
        for ($i = 0; $i < 10; $i++) {
            $lowerCaseDict = $this->getMockBuilder(DictionaryInterface::class)->getMock();
            $lowerCaseDict->expects(self::once())
                ->method('getRandomWord')
                ->willReturn('worda');
            $upperCaseDict = $this->getMockBuilder(DictionaryInterface::class)->getMock();
            $upperCaseDict->expects(self::once())
                ->method('getRandomWord')
                ->willReturn('WORDA');

            $lowerCase = new RandomWord($lowerCaseDict);
            $lowerCase->add();
            $upperCase = new RandomWord($upperCaseDict);
            $upperCase->add();

            $rand = new RandomWords($lowerCase, $upperCase);
            static::assertTrue($rand->has());
            static::assertCount(2, $rand->get());
            $rand->remove();
            static::assertTrue($rand->has());
            static::assertCount(1, $rand->get());
            $rand->remove();
            static::assertFalse($rand->has());
            static::assertSame([], $rand->get());
        }
    }

    /**
     * @return void
     * @throws Exception
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws RuntimeException
     */
    public function testGetWithoutLowerCase(): void
    {
        $lowerCaseDict = $this->getMockBuilder(DictionaryInterface::class)->getMock();
        $lowerCaseDict->expects(self::once())
            ->method('getRandomWord')
            ->willReturn('wordc');
        $upperCaseDict = $this->getMockBuilder(DictionaryInterface::class)->getMock();
        $upperCaseDict->expects(self::exactly(2))
            ->method('getRandomWord')
            ->willReturnOnConsecutiveCalls('WordA', 'WordB');

        $lowerCase = new RandomWord($lowerCaseDict);
        $upperCase = new RandomWord($upperCaseDict);
        $upperCase->add();
        $upperCase->add();

        $rand = new RandomWords($lowerCase, $upperCase);
        $words = $rand->get();
        static::assertContains('wordc', $words);
    }

    /**
     * @return void
     * @throws Exception
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws RuntimeException
     */
    public function testGetWithoutUpperCase(): void
    {
        $lowerCaseDict = $this->getMockBuilder(DictionaryInterface::class)->getMock();
        $lowerCaseDict->expects(self::exactly(2))
            ->method('getRandomWord')
            ->willReturnOnConsecutiveCalls('worda', 'wordb');
        $upperCaseDict = $this->getMockBuilder(DictionaryInterface::class)->getMock();
        $upperCaseDict->expects(self::once())
            ->method('getRandomWord')
            ->willReturn('WordC');

        $lowerCase = new RandomWord($lowerCaseDict);
        $lowerCase->add();
        $lowerCase->add();
        $upperCase = new RandomWord($upperCaseDict);

        $rand = new RandomWords($lowerCase, $upperCase);
        $words = $rand->get();
        static::assertContains('WordC', $words);
    }

    /**
     * @return void
     * @throws Exception
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws RuntimeException
     */
    public function testAdd(): void
    {
        // in 10 rounds rand(0,1) should produce at least one different result
        for ($i = 0; $i < 10; $i++) {
            $lowerCaseDict = $this->getMockBuilder(DictionaryInterface::class)->getMock();
            $lowerCaseDict->expects(self::atMost(1))
                ->method('getRandomWord')
                ->willReturn('worda');
            $upperCaseDict = $this->getMockBuilder(DictionaryInterface::class)->getMock();
            $upperCaseDict->expects(self::atMost(1))
                ->method('getRandomWord')
                ->willReturn('WordB');

            $rand = new RandomWords(new RandomWord($lowerCaseDict), new RandomWord($upperCaseDict));
            static::assertFalse($rand->has());
            $rand->add();
            static::assertTrue($rand->has());
            static::assertCount(1, $rand->get());
        }
    }
}
