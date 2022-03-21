<?php

namespace Tests\GregorJ\CorrectHorse;

use Exception;
use GregorJ\CorrectHorse\Generators\RandomCharacter;
use PHPUnit\Framework\TestCase;

/**
 * Class RandomCharacterTest
 */
class RandomCharacterTest extends TestCase
{
    /**
     * @return void
     */
    public function testHasAndAddCharacter(): void
    {
        $rand = new RandomCharacter();
        static::assertFalse($rand->hasChar('A'));
        $rand->addChar('A');
        static::assertTrue($rand->hasChar('A'));
    }

    /**
     * @return void
     */
    public function testAddMultipleCharacters(): void
    {
        $rand = new RandomCharacter();
        $rand->addChar('BC');
        static::assertTrue($rand->hasChar('B'));
        static::assertFalse($rand->hasChar('C'));
    }

    /**
     * @return void
     */
    public function testSpaceCharacter(): void
    {
        $rand = new RandomCharacter([' ']);
        static::assertTrue($rand->hasChar(' '));
        static::assertFalse($rand->hasChar(RandomCharacter::DEFAULT_CHARS[0]));
        static::assertFalse($rand->hasChar(RandomCharacter::DEFAULT_CHARS[1]));
        static::assertFalse($rand->hasChar(RandomCharacter::DEFAULT_CHARS[2]));
        static::assertFalse($rand->hasChar(RandomCharacter::DEFAULT_CHARS[3]));
    }

    /**
     * @return void
     */
    public function testEmptyString(): void
    {
        $rand = new RandomCharacter(['']);
        static::assertFalse($rand->hasChar(''));
        static::assertTrue($rand->hasChar(RandomCharacter::DEFAULT_CHARS[0]));
        static::assertTrue($rand->hasChar(RandomCharacter::DEFAULT_CHARS[1]));
        static::assertTrue($rand->hasChar(RandomCharacter::DEFAULT_CHARS[2]));
        static::assertTrue($rand->hasChar(RandomCharacter::DEFAULT_CHARS[3]));
    }

    /**
     * @return void
     * @throws Exception
     */
    public function testGenerate(): void
    {
        $rand = new RandomCharacter(['A', 'B']);
        $rand->add();
        $rand->add();
        $array = $rand->get();
        static::assertContains('A', $array);
        static::assertContains('B', $array);
    }

    /**
     * @return void
     * @throws Exception
     */
    public function testGenerateFromSingleCharacter(): void
    {
        $rand = new RandomCharacter(['A']);
        $rand->add();
        static::assertSame(['A'], $rand->get());
        $rand->add();
        static::assertSame(['A'], $rand->get());
    }
}
