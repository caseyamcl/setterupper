<?php
declare(strict_types=1);

namespace SetterUpper;

use Countable;
use IteratorAggregate;
use MJS\TopSort\Implementations\StringSort;
use MJS\TopSort\TopSortInterface;

/**
 * Class SetupRunner
 *
 * @author Casey McLaughlin <caseyamcl@gmail.com>
 */
class SetupStepCollection implements IteratorAggregate, Countable
{
    /**
     * @var StringSort
     */
    private $sorter;

    /**
     * @var array|SetupStep[]
     */
    private $steps = [];

    /**
     * @var array|array[]  Each sub-array is an array of strings
     */
    private $extraDependencies = [];

    /**
     * SetupRunner constructor.
     *
     * @param TopSortInterface|null $sorter  If not specified, will use the default StringSort
     */
    public function __construct(?TopSortInterface $sorter = null)
    {
        $this->sorter = $sorter ?: new StringSort();
    }

    /**
     * Add multiple steps to the collection
     *
     * @param SetupStep ...$steps
     * @return $this
     */
    public function add(SetupStep ...$steps): self
    {
        foreach ($steps as $step) {
            $this->addStep($step);
        }

        return $this;
    }

    /**
     * Add a step to the collection
     *
     * @param SetupStep $step
     * @return $this
     */
    public function addStep(SetupStep $step): self
    {
        $this->steps[get_class($step)] = $step;

        foreach ($step::mustRunBefore() as $stepName) {
            $this->extraDependencies[$stepName][] = get_class($step);
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getIterator()
    {
        $sorter = clone $this->sorter;

        foreach ($this->steps as $stepClassName => $step) {
            $dependencies = $step::dependsOn();

            // fancy logic here...
            if (isset($this->extraDependencies[$stepClassName])) {
                $dependencies = array_merge($dependencies, $this->extraDependencies[$stepClassName]);
            }

            $sorter->add($stepClassName, $dependencies);
        }

        foreach ($sorter->sort() as $stepName) {
            yield $stepName => $this->steps[$stepName];
        }
    }

    /**
     * @inheritDoc
     */
    public function count()
    {
        return count($this->steps);
    }
}
