<?php
declare(strict_types=1);

namespace SetterUpper\Reporter;

use SetterUpper\Reporter;
use SetterUpper\SetupStepResult;

/**
 * Class Psr6Reporter
 *
 * @author Casey McLaughlin <caseyamcl@gmail.com>
 */
class Psr6Reporter implements Reporter
{
    /**
     * @inheritDoc
     */
    public function reportResult(SetupStepResult $result): void
    {
        // TODO: Implement reportResult() method.
    }
}
