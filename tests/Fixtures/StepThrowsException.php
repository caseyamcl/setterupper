<?php
declare(strict_types=1);

namespace SetterUpper\Fixtures;

use RuntimeException;
use SetterUpper\SetupStepResult;

/**
 * Step that throws exception (depends on StepA, and runs before StepC if it is defined)
 *
 * @author Casey McLaughlin <caseyamcl@gmail.com>
 */
class StepThrowsException extends AbstractStep
{
    public static function dependsOn(): iterable
    {
        return [StepA::class];
    }

    public static function mustRunBefore(): iterable
    {
        return [StepC::class];
    }

    /**
     * @return SetupStepResult
     */
    public function __invoke(): SetupStepResult
    {
        throw new RuntimeException('error occurred during processing');
    }
}
