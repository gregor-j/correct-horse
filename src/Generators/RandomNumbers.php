<?php

namespace GregorJ\CorrectHorse\Generators;

use Exception;
use GregorJ\CorrectHorse\RandomGeneratorInterface;

use function count;
use function in_array;
use function random_int;

/**
 * Class RandomNumbers
 */
final class RandomNumbers implements RandomGeneratorInterface
{
    use ManageRandomItemsTrait;

    /**
     * Define defaults.
     */
    public const DEFAULT_MIN = 0;
    public const DEFAULT_MAX = 9;

    /**
     * @var int
     */
    private $min = self::DEFAULT_MIN;

    /**
     * @var int
     */
    private $max = self::DEFAULT_MAX;

    /**
     * Initialize random number generator.
     */
    public function __construct()
    {
        $this->reset();
    }

    /**
     * Set numbers minimum and maximum values.
     * @param  int  $min
     * @param  int  $max
     * @return void
     */
    public function setMinAndMax(int $min, int $max): void
    {
        if ($min > $max) {
            $tmp = $min;
            $min = $max;
            $max = $tmp;
        }
        if ($min === $max || $min < 0) {
            return;
        }
        $this->min = $min;
        $this->max = $max;
    }

    /**
     * Determine whether max. number of random numbers has been generated.
     * @return bool
     */
    private function isMaxReached(): bool
    {
        return count($this->items) >= ($this->max - $this->min);
    }

    /**
     * Has a random number already been generated?
     * @param  int  $number
     * @return bool
     */
    private function hasNumber(int $number): bool
    {
        return in_array($number, $this->items, true);
    }

    /**
     * Add a randomly generated item.
     * @return void
     * @throws Exception
     */
    public function add(): void
    {
        if ($this->isMaxReached()) {
            return;
        }
        do {
            $number = random_int($this->min, $this->max);
        } while ($this->hasNumber($number));
        $this->items[] = $number;
    }
}
