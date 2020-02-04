<?php
declare(strict_types=1);

namespace SetterUpper;

use MJS\TopSort\TopSortInterface;

/**
 * Class SetterUpper
 *
 * @author Casey McLaughlin <caseyamcl@gmail.com>
 */
class SetterUpper
{
    /**
     * @var SetupStepCollection
     */
    private $collection;

    /**
     * @var SetupRunner
     */
    private $runner;

    /**
     * SetterUpper constructor.
     * @param Reporter|null $reporter
     * @param TopSortInterface|null $sorter
     */
    public function __construct(?Reporter $reporter = null, ?TopSortInterface $sorter = null)
    {
        $this->collection = new SetupStepCollection($sorter);
        $this->runner = new SetupRunner($reporter);
    }

    /**
     * @param SetupStep $step
     * @return $this
     */
    public function addStep(SetupStep $step): self
    {
        $this->collection->addStep($step);
        return $this;
    }

    /**
     * @param SetupStep ...$steps
     * @return $this
     */
    public function add(SetupStep ...$steps): self
    {
        call_user_func_array([$this->collection, 'add'], $steps);
        return $this;
    }

    /**
     * @param bool $stopOnFailure
     * @return iterable
     */
    public function run(bool $stopOnFailure = false): iterable
    {
        yield from $this->runner->run($this->collection, $stopOnFailure);
    }

    /**
     * @param bool $stopOnFailure
     * @return array
     */
    public function runAll(bool $stopOnFailure): array
    {
        return $this->runner->runAll($this->collection, $stopOnFailure);
    }
}
