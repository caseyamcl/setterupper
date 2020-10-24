<?php
declare(strict_types=1);

namespace SetterUpper;

use ArrayIterator;
use Countable;
use IteratorAggregate;
use MJS\TopSort\Implementations\StringSort;
use MJS\TopSort\TopSortInterface;
use SetterUpper\Exception\SetupStepNameCollisionException;

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

    public static function build(SetupStep ...$steps)
    {
        $that = new static();
        return call_user_func_array([$that, 'add'], $steps);
    }

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
        $stepName = get_class($step);

        if (array_key_exists($stepName, $this->steps)) {
            throw new SetupStepNameCollisionException($stepName);
        }

        $this->steps[$stepName] = $step;

        foreach ($step::mustRunBefore() as $depName) {
            $this->extraDependencies[$depName][] = $stepName;
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getIterator(): iterable
    {
        if (count($this->steps) === 0) {
            return new ArrayIterator([]);
        }

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
