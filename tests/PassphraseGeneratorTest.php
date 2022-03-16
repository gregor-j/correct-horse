<?php

namespace Tests\GregorJ\CorrectHorse;

use GregorJ\CorrectHorse\Generators\RandomCharacter;
use GregorJ\CorrectHorse\PassphraseGenerator;
use GregorJ\CorrectHorse\RandomGeneratorInterface;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\MockObject\RuntimeException;
use PHPUnit\Framework\TestCase;
use SebastianBergmann\RecursionContext\InvalidArgumentException;

/**
 * Class PassphraseGeneratorTest
 */
class PassphraseGeneratorTest extends TestCase
{
    /**
     * @return void
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    public function testEmptyGenerate(): void
    {
        $pass = new PassphraseGenerator();
        static::assertSame('', $pass->generate());
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     * @throws RuntimeException
     * @throws InvalidArgumentException
     */
    public function testGenerate(): void
    {
        $rand1 = $this->getMockBuilder(RandomGeneratorInterface::class)->getMock();
        $rand1->expects(self::once())
            ->method('reset');
        $rand1->expects(self::exactly(2))
            ->method('add');
        $rand1->expects(self::once())
            ->method('get')
            ->willReturn(['worda', 'wordb']);

        $pass = new PassphraseGenerator(new RandomCharacter(['#']));
        $pass->add($rand1, 2);
        static::assertSame('worda#wordb', $pass->generate());
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     * @throws RuntimeException
     * @throws InvalidArgumentException
     */
    public function testEmptySeparator(): void
    {
        $rand1 = $this->getMockBuilder(RandomGeneratorInterface::class)->getMock();
        $rand1->expects(self::once())
            ->method('reset');
        $rand1->expects(self::exactly(2))
            ->method('add');
        $rand1->expects(self::once())
            ->method('get')
            ->willReturn(['worda', 'wordb']);

        $pass = new PassphraseGenerator();
        $pass->add($rand1, 2);
        static::assertSame('wordawordb', $pass->generate());
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     * @throws RuntimeException
     * @throws InvalidArgumentException
     */
    public function testWithSeparator(): void
    {
        $rand1 = $this->getMockBuilder(RandomGeneratorInterface::class)->getMock();
        $rand1->expects(self::once())
            ->method('reset');
        $rand1->expects(self::exactly(2))
            ->method('add');
        $rand1->expects(self::once())
            ->method('get')
            ->willReturn(['worda', 'wordb']);

        $separator = $this->getMockBuilder(RandomGeneratorInterface::class)->getMock();
        $separator->expects(self::once())
            ->method('reset');
        $separator->expects(self::once())
            ->method('add');
        $separator->expects(self::once())
            ->method('has')
            ->willReturn(true);
        $separator->expects(self::once())
            ->method('get')
            ->willReturn([',']);

        $pass = new PassphraseGenerator($separator);
        $pass->add($rand1, 2);
        static::assertSame('worda,wordb', $pass->generate());
    }

    /**
     * @return void
     * @throws ExpectationFailedException
     * @throws RuntimeException
     * @throws InvalidArgumentException
     */
    public function testNoSeparatorGenerated(): void
    {
        $rand1 = $this->getMockBuilder(RandomGeneratorInterface::class)->getMock();
        $rand1->expects(self::once())
            ->method('reset');
        $rand1->expects(self::exactly(2))
            ->method('add');
        $rand1->expects(self::once())
            ->method('get')
            ->willReturn(['worda', 'wordb']);

        $separator = $this->getMockBuilder(RandomGeneratorInterface::class)->getMock();
        $separator->expects(self::once())
            ->method('reset');
        $separator->expects(self::once())
            ->method('add');
        $separator->expects(self::once())
            ->method('has')
            ->willReturn(false);

        $pass = new PassphraseGenerator($separator);
        $pass->add($rand1, 2);
        static::assertSame('wordawordb', $pass->generate());
    }
}
