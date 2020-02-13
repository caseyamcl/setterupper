<?php
declare(strict_types=1);

namespace SetterUpper\SetupStep;

use SetterUpper\SetupStep;
use SetterUpper\SetupStepResult;

/**
 * Class ReportTimeZoneStep
 *
 * @author Casey McLaughlin <caseyamcl@gmail.com>
 */
class ReportTimeZoneStep implements SetupStep
{
    /**
     * @inheritDoc
     */
    public static function dependsOn(): iterable
    {
        return [];
    }

    /**
     * @inheritDoc
     */
    public static function mustRunBefore(): iterable
    {
        return [];
    }

    /**
     * @return SetupStepResult
     */
    public function __invoke(): SetupStepResult
    {
        return SetupStepResult::notesOnly(sprintf('Timezone is <info>%s</info>', date_default_timezone_get()));
    }

    public function __toString(): string
    {
        return 'report the timezone configured in PHP';
    }


}
