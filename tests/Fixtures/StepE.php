<?php
declare(strict_types=1);

namespace SetterUpper\Fixtures;

/**
 * Step E depends on Step F, which depends on Step E, thereby creating a dependency loop
 *
 * @author Casey McLaughlin <caseyamcl@gmail.com>
 */
class StepE extends AbstractStep
{
    public static function dependsOn(): iterable
    {
        return [StepF::class];
    }

}
