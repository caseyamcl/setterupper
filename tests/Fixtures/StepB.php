<?php
declare(strict_types=1);

namespace SetterUpper\Fixtures;

/**
 * StepB depends on StepA
 *
 * @author Casey McLaughlin <caseyamcl@gmail.com>
 */
class StepB extends AbstractStep
{
    /**
     * @inheritDoc
     */
    public static function dependsOn(): iterable
    {
        return [StepA::class];
    }
}
