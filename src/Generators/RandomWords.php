<?php

namespace GregorJ\CorrectHorse\Generators;

use GregorJ\CorrectHorse\RandomGeneratorInterface;

use function array_merge;
use function rand;
use function shuffle;

/**
 * Class RandomWords
 * Generate random upper and lower case words.
 */
final class RandomWords implements RandomGeneratorInterface
{
    /**
     * @var RandomGeneratorInterface
     */
    private $lowerCase;

    /**
     * @var RandomGeneratorInterface
     */
    private $upperCase;

    /**
     * Initialize the generator with an upper case word dictionary and a lower case word dictionary.
     * @param  RandomWord  $lowerCase
     * @param  RandomWord  $upperCase
     */
    public function __construct(RandomGeneratorInterface $lowerCase, RandomGeneratorInterface $upperCase)
    {
        $this->upperCase = $upperCase;
        $this->lowerCase = $lowerCase;
    }

    /**
     * Add a randomly generated item.
     * @return void
     */
    public function add(): void
    {
        if (rand(0, 1) === 0) {
            $this->lowerCase->add();
        } else {
            $this->upperCase->add();
        }
    }

    /**
     * Clear all generated items.
     * @return void
     */
    public function reset(): void
    {
        $this->upperCase->reset();
        $this->lowerCase->reset();
    }

    /**
     * Remove a random generated item.
     * @return void
     */
    public function remove(): void
    {
        if (!$this->upperCase->has() && $this->lowerCase->has()) {
            $this->lowerCase->remove();
        } elseif (!$this->lowerCase->has() && $this->upperCase->has()) {
            $this->upperCase->remove();
        } elseif ($this->upperCase->has() && $this->lowerCase->has()) {
            if (rand(0, 1) === 0) {
                $this->lowerCase->remove();
            } else {
                $this->upperCase->remove();
            }
        }
    }

    /**
     * Are there any randomly generated items?
     * @return bool
     */
    public function has(): bool
    {
        return $this->lowerCase->has() || $this->upperCase->has();
    }

    /**
     * Get all randomly generated items in random order.
     * @return array
     */
    public function get(): array
    {
        /**
         * Quit early in case there are no words.
         */
        if (!$this->upperCase->has() && !$this->lowerCase->has()) {
            return [];
        }
        /**
         * Ensure that at least one upper case and one lower case word is present.
         */
        if (!$this->upperCase->has() && count($this->lowerCase->get()) > 1) {
            $this->lowerCase->remove();
            $this->upperCase->add();
        }
        if (!$this->lowerCase->has() && count($this->upperCase->get()) > 1) {
            $this->upperCase->remove();
            $this->lowerCase->add();
        }
        $words = array_merge(
            $this->upperCase->get(),
            $this->lowerCase->get()
        );
        shuffle($words);
        return $words;
    }
}
