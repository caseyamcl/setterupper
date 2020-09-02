<?php
declare(strict_types=1);

namespace SetterUpper;

use Countable;
use IteratorAggregate;
use MJS\TopSort\CircularDependencyException;
use MJS\TopSort\ElementNotFoundException;
use MJS\TopSort\Implementations\StringSort;
use MJS\TopSort\TopSortInterface;

/**
 * Decorator for marcj/topsort that allows for dependencies and 'must run before'
 *
 * @author Casey McLaughlin <caseyamcl@gmail.com>
 */
class TwoWaySorter implements IteratorAggregate, Countable
{
    /**
     * @var TopSortInterface
     */
    private $sorter;

    /**
     * @var array|string[]
     */
    private $items = [];

    /**
     * @var array|string[]
     */
    private $extraDependencies = [];

    public function __construct(?TopSortInterface $sorter = null)
    {
        $this->sorter = $sorter ?: new StringSort();
    }

    /**
     * @param string $item
     * @param array|string[] $dependencies
     * @param array|string[] $mustRunBefore
     */
    public function add(string $item, array $dependencies = [], array $mustRunBefore = []): void
    {
        $this->items[$item] = $dependencies;

        if (! empty($mustRunBefore)) {
            foreach ($mustRunBefore as $depName) {
                $this->extraDependencies[$depName][] = $item;
            }
        }
    }

    /**
     * @throws CircularDependencyException
     * @throws ElementNotFoundException
     * @return array
     */
    public function getIterator(): array
    {
        $sorter = clone $this->sorter;

        foreach ($this->items as $item => $dependencies) {
            // fancy logic here...
            if (isset($this->extraDependencies[$item])) {
                $dependencies = array_merge($dependencies, $this->extraDependencies[$item]);
            }

            $sorter->add($item, $dependencies);
        }

        return $sorter->sort();
    }

    /**
     * @return array
     * @throws CircularDependencyException
     * @throws ElementNotFoundException
     */
    public function sort(): array
    {
        return $this->getIterator();
    }

    public function count(): int
    {
        return count($this->items);
    }
}
