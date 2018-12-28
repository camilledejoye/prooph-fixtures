<?php

/**
 * This file is part of elythy/prooph-fixtures.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Prooph\Fixtures\Cleaner;

/**
 * Call multipile cleaners in the same order they were added.
 */
class ChainCleaner implements Cleaner
{
    /**
     * @var Cleaner[]
     */
    private $cleaners;

    /**
     * Creates a new chain of Cleaner.
     *
     * @param Cleaner[] $cleaners
     */
    public function __construct(iterable $cleaners = [])
    {
        $this->cleaners = [];

        if ($cleaners) {
            $this->addCleaners($cleaners);
        }
    }

    /**
     * Call each Cleaner in the same order they were added.
     *
     * @return void
     */
    public function __invoke(): void
    {
        foreach ($this->cleaners as $cleaner) {
            $cleaner();
        }
    }

    /**
     * Adds multiple cleaners in the order they are given.
     *
     * @param iterable $cleaners
     *
     * @return self
     *
     * @throws \TypeError If at least one of the cleaners does not implement Cleaner interface.
     */
    public function addCleaners(iterable $cleaners): self
    {
        foreach ($cleaners as $cleaner) {
            $this->add($cleaner);
        }

        return $this;
    }

    /**
     * Adds another Cleaner.
     *
     * @param Cleaner $cleaner
     *
     * @return self
     */
    public function add(Cleaner $cleaner): self
    {
        $this->cleaners[] = $cleaner;

        return $this;
    }
}
