<?php

namespace Tests\GregorJ\CorrectHorse;

use GregorJ\CorrectHorse\Generators\RandomNumbers;
use PHPUnit\Framework\Exception;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use SebastianBergmann\RecursionContext\InvalidArgumentException;

/**
 * Class ManageRandomItemsTraitTest
 */
class ManageRandomItemsTraitTest extends TestCase
{
    /**
     * @return void
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function testHas(): void
    {
        $rand = new RandomNumbers();
        static::assertFalse($rand->has());
        $rand->add();
        static::assertTrue($rand->has());
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function testReset(): void
    {
        $rand = new RandomNumbers();
        $rand->add();
        static::assertTrue($rand->has());
        $rand->reset();
        static::assertFalse($rand->has());
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws Exception
     */
    public function testRemove(): void
    {
        $rand = new RandomNumbers();
        $rand->add();
        $rand->add();
        static::assertCount(2, $rand->get());
        $rand->remove();
        static::assertCount(1, $rand->get());
        $rand->remove();
        static::assertFalse($rand->has());
        static::assertSame([], $rand->get());
        //remove again, event though there's nothing left
        $rand->remove();
        static::assertFalse($rand->has());
        static::assertSame([], $rand->get());
    }
}
