<?php
declare(strict_types=1);

namespace SetterUpper\Fixtures;

use SetterUpper\SetupStep;
use SetterUpper\SetupStepResult;

/**
 * StepC depends on StepA, and must run before StepB
 *
 * @author Casey McLaughlin <caseyamcl@gmail.com>
 */
class StepC extends AbstractStep
{
    /**
     * @inheritDoc
     */
    public static function dependsOn(): iterable
    {
        return [StepA::class];
    }

    /**
     * @inheritDoc
     */
    public static function mustRunBefore(): iterable
    {
        return [StepB::class];
    }
}
