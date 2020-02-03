<?php
declare(strict_types=1);

namespace SetterUpper\Reporter;

use SetterUpper\Reporter;
use SetterUpper\SetupStepResult;
use Throwable;

/**
 * Class NullReporter
 *
 * @author Casey McLaughlin <caseyamcl@gmail.com>
 */
class NullReporter implements Reporter
{
    /**
     * @inheritDoc
     */
    public function reportResult(SetupStepResult $result): void
    {
        // Do nothing.
    }
}
