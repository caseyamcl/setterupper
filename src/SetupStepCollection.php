<?php
declare(strict_types=1);

namespace SetterUpper;

use SetterUpper\Exception\SetupStepNameCollisionException;
use SortableTasks\SortableTasksIterator;

/**
 * Class SetupRunner
 *
 * @author Casey McLaughlin <caseyamcl@gmail.com>
 */
class SetupStepCollection extends SortableTasksIterator
{
    /**
     * Add multiple steps to the collection
     *
     * @param SetupStep ...$steps
     * @return $this
     */
    public function addSteps(SetupStep ...$steps): self
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

        if (array_key_exists($stepName, $this->tasks)) {
            throw new SetupStepNameCollisionException($stepName);
        }

        $this->add($step);
        return $this;
    }
}
