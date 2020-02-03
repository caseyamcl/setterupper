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
     * @param SetupStepResult $result
     */
    public function reportResult(SetupStepResult $result): void;
}
