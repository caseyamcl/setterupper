<?php
declare(strict_types=1);

namespace SetterUpper;

/**
 * Interface Reporter
 *
 * @author Casey McLaughlin <caseyamcl@gmail.com>
 */
interface Reporter
{
    /**
     * Report that a step is being run (triggered from SetupStepRunner::run())
     *
     * @param SetupStep $step
     */
    public function reportStep(SetupStep $step): void;

    /**
     * Report step having been run (triggered from SetupStepRunner::run())
     *
     * @param SetupStepResult $result
     */
    public function reportResult(SetupStepResult $result): void;
}
