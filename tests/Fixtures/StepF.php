<?php
declare(strict_types=1);

namespace SetterUpper\Fixtures;

/**
 * Step F depends on Step E, which depends on Step F, thereby creating a dependency loop
 *
 * @author Casey McLaughlin <caseyamcl@gmail.com>
 */
class StepF extends AbstractStep
{
    public static function dependsOn(): iterable
    {
        return [StepE::class];
    }
}
