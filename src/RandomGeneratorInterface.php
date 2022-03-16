<?php

namespace GregorJ\CorrectHorse;

/**
 * Interface RandomGeneratorInterface
 * Manages randomly generated items.
 */
interface RandomGeneratorInterface
{
    /**
     * Clear all generated items.
     * @return void
     */
    public function reset(): void;

    /**
     * Add a randomly generated item.
     * @return void
     */
    public function add(): void;

    /**
     * Remove a random generated item.
     * @return void
     */
    public function remove(): void;

    /**
     * Are there any randomly generated items?
     * @return bool
     */
    public function has(): bool;

    /**
     * Get all randomly generated items in random order.
     * @return array
     */
    public function get(): array;
}
