<?php

namespace GregorJ\CorrectHorse\Generators;

use function array_rand;
use function shuffle;

/**
 * Trait ManageRandomItemsTrait
 * Manage common tasks with random items.
 */
trait ManageRandomItemsTrait
{
    /**
     * @var array
     */
    private $items = [];

    /**
     * Clear all generated items.
     * @return void
     */
    public function reset(): void
    {
        $this->items = [];
    }

    /**
     * Remove a random generated item.
     * @return void
     */
    public function remove(): void
    {
        if ($this->has()) {
            unset($this->items[array_rand($this->items)]);
        }
    }

    /**
     * Are there any randomly generated items?
     * @return bool
     */
    public function has(): bool
    {
        return $this->items !== [];
    }

    /**
     * Get all randomly generated items in random order.
     * @return array
     */
    public function get(): array
    {
        // in case there are no random items, return an empty array
        if (!$this->has()) {
            return [];
        }
        // in case there is just one item, there is no need to shuffle
        if (count($this->items) === 1) {
            return $this->items;
        }
        // get the generated items in random order before returning them
        $items = $this->items;
        shuffle($items);
        return $items;
    }
}
