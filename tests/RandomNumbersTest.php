<?php

namespace Tests\GregorJ\CorrectHorse;

use GregorJ\CorrectHorse\Generators\RandomNumbers;
use GregorJ\CorrectHorse\RandomGeneratorInterface;
use PHPUnit\Framework\Exception;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use SebastianBergmann\RecursionContext\InvalidArgumentException;

/**
 * Class RandomNumbersTest
 */
class RandomNumbersTest extends TestCase
{
    /**
     * @return void
     * @throws Exception
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function testInheritance(): void
    {
        $rand = new RandomNumbers();
        static::assertInstanceOf(RandomGeneratorInterface::class, $rand);
    }

    /**
     * @return void
     * @throws Exception
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws \Exception
     */
    public function testSetMinAndMax(): void
    {
        $rand = new RandomNumbers();
        $rand->setMinAndMax(101, 102);
        $rand->add();
        $numbers = $rand->get();
        static::assertCount(1, $numbers);
        $number = array_pop($numbers);
        static::assertGreaterThanOrEqual(101, $number);
        static::assertLessThanOrEqual(102, $number);
    }

    /**
     * @return void
     * @throws Exception
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws \Exception
     */
    public function testSetFlippedMinAndMax(): void
    {
        $rand = new RandomNumbers();
        $rand->setMinAndMax(102, 101);
        $rand->add();
        $numbers = $rand->get();
        static::assertCount(1, $numbers);
        $number = array_pop($numbers);
        static::assertGreaterThanOrEqual(101, $number);
        static::assertLessThanOrEqual(102, $number);
    }

    /**
     * @return void
     * @throws Exception
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws \Exception
     */
    public function testSetMinSameAsMax(): void
    {
        $rand = new RandomNumbers();
        $rand->setMinAndMax(RandomNumbers::DEFAULT_MAX + 1, RandomNumbers::DEFAULT_MAX + 1);
        $rand->add();
        $numbers = $rand->get();
        static::assertCount(1, $numbers);
        $number = array_pop($numbers);
        static::assertGreaterThanOrEqual(RandomNumbers::DEFAULT_MIN, $number);
        static::assertLessThanOrEqual(RandomNumbers::DEFAULT_MAX, $number);
    }

    /**
     * @return void
     * @throws Exception
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws \Exception
     */
    public function testSetMinBelowZero(): void
    {
        $rand = new RandomNumbers();
        $rand->setMinAndMax(-100, -99);
        $rand->add();
        $numbers = $rand->get();
        static::assertCount(1, $numbers);
        $number = array_pop($numbers);
        static::assertGreaterThanOrEqual(RandomNumbers::DEFAULT_MIN, $number);
        static::assertLessThanOrEqual(RandomNumbers::DEFAULT_MAX, $number);
    }

    /**
     * @return void
     * @throws Exception
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws \Exception
     */
    public function testReachingMax(): void
    {
        $rand = new RandomNumbers();
        $rand->setMinAndMax(101, 103);
        $rand->add();
        $rand->add();
        $rand->add();
        $numbers = $rand->get();
        static::assertCount(2, $numbers);
    }
}
