<?php
declare(strict_types=1);

namespace SetterUpper\Fixtures;

/**
 * Step G depends on a non-existent element
 *
 * @author Casey McLaughlin <caseyamcl@gmail.com>
 */
class StepG extends AbstractStep
{
    public static function dependsOn(): iterable
    {
        return ['NonExistent'];
    }

}
