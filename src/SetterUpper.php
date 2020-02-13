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
     * Build using custom reporter and optionally a custom sorter
     *
     * @param Reporter $reporter
     * @param SetupStepCollection|null $collection
     * @param TopSortInterface|null $sorter
     * @return $this
     */
    public static function buildUsingReporter(
        Reporter $reporter,
        ?SetupStepCollection $collection = null,
        ?TopSortInterface $sorter = null
    ) {
        return new static(
            $collection ?: new SetupStepCollection($sorter),
            new SetupRunner($reporter)
        );
    }

    /**
     * SetterUpper constructor.
     *
     * @param SetupStepCollection|null $collection
     * @param SetupRunner|null $runner
     */
    public function __construct(?SetupStepCollection $collection = null, ?SetupRunner $runner = null)
    {
        $this->runner = $runner ?: new SetupRunner();
        $this->collection = $collection ?: new SetupStepCollection();
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
    public function runAll(bool $stopOnFailure = false): array
    {
        return $this->runner->runAll($this->collection, $stopOnFailure);
    }

    /**
     * @return SetupStepCollection|iterable|SetupStep[]
     */
    public function getSteps(): SetupStepCollection
    {
        return $this->collection;
    }

    /**
     * @return SetupRunner
     */
    public function getRunner(): SetupRunner
    {
        return $this->runner;
    }
}
