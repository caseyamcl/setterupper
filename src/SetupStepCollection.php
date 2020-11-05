<?php
declare(strict_types=1);

namespace SetterUpper;

use Countable;
use IteratorAggregate;
use MJS\TopSort\TopSortInterface;
use SetterUpper\Exception\SetupStepNameCollisionException;
use SortableTasks\SortableTasksIterator;

/**
 * Class SetupRunner
 *
 * @author Casey McLaughlin <caseyamcl@gmail.com>
 */
class SetupStepCollection implements IteratorAggregate, Countable
{
    private SortableTasksIterator $innerCollection;

    public static function build(SetupStep ...$steps): self
    {
        $that = new static();
        call_user_func_array([$that, 'add'], $steps);
        return $that;
    }

    public function __construct(TopSortInterface $sorter = null)
    {
        $this->innerCollection = new SortableTasksIterator($sorter);
    }

    /**
     * Add a step to the collection
     *
     * @param SetupStep[] $steps
     * @return $this
     */
    public function add(SetupStep ...$steps): self
    {
        foreach ($steps as $step) {
            if ($this->innerCollection->contains(get_class($step))) {
                throw new SetupStepNameCollisionException(get_class($step), 409);
            }
            $this->innerCollection->add($step);
        }

        return $this;
    }

    public function addStep(SetupStep $step): self
    {
        return $this->add($step);
    }

    public function getIterator(): iterable
    {
        yield from $this->innerCollection;
    }

    public function count(): int
    {
        return $this->innerCollection->count();
    }
}
