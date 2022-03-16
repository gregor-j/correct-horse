<?php

namespace GregorJ\CorrectHorse;

use function array_merge;
use function array_pop;
use function implode;

/**
 * Class PassphraseGenerator
 */
final class PassphraseGenerator
{
    private $counter = 0;

    /**
     * @var RandomGeneratorInterface[]
     */
    private $generators = [];

    /**
     * @var int[]
     */
    private $times = [];

    /**
     * @var RandomGeneratorInterface|null
     */
    private $separator;

    /**
     * Initialize the passphrase generator with a separator.
     * @param  RandomGeneratorInterface|null  $separator
     */
    public function __construct(RandomGeneratorInterface $separator = null)
    {
        if ($separator !== null) {
            $this->setSeparator($separator);
        }
    }

    /**
     * @param  RandomGeneratorInterface  $separator
     * @return void
     */
    public function setSeparator(RandomGeneratorInterface $separator): void
    {
        $this->separator = $separator;
    }

    /**
     * Add a random generator.
     * @param  RandomGeneratorInterface  $generator
     * @param  int  $times
     * @return void
     */
    public function add(RandomGeneratorInterface $generator, int $times): void
    {
        $this->generators[$this->counter] = $generator;
        $this->times[$this->counter] = $times;
        $this->counter++;
    }

    /**
     * Generate a random passphrase with the given number of items.
     * @return string
     */
    public function generate(): string
    {
        // in case there are no generators, return an empty string
        if ($this->counter === 0) {
            return '';
        }
        // run each generator
        $parts = [];
        foreach ($this->generators as $key => $generator) {
            // reset the generator
            $generator->reset();
            // generate as many items as defined
            for ($i = 0; $i < $this->times[$key]; $i++) {
                $generator->add();
            }
            // add the generated items to the parts of the passphrase
            $parts = array_merge($parts, $generator->get());
        }
        return implode($this->getSeparator(), $parts);
    }

    /**
     * Generate one random separator item and return it as string.
     * @return string
     */
    private function getSeparator(): string
    {
        // in case there is no separator just return an empty string
        if ($this->separator === null) {
            return '';
        }
        // reset the generator
        $this->separator->reset();
        // generate a single separator
        $this->separator->add();
        // return an empty string in case no separator has been generated
        if (!$this->separator->has()) {
            return '';
        }
        // get the generated separator and cast it to a string
        $separator = $this->separator->get();
        return (string)array_pop($separator);
    }
}
