<?php
declare(strict_types=1);

namespace SetterUpper;

use SortableTasks\SortableTask;

/**
 * Interface SetupStep
 *
 * @author Casey McLaughlin <caseyamcl@gmail.com>
 */
interface SetupStep extends SortableTask
{
    /**
     * Run the step
     *
     * @return SetupStepResult
     */
    public function __invoke(): SetupStepResult;

    /**
     * Return a brief description of what this step does
     *
     * @return string
     */
    public function __toString(): string;
}
