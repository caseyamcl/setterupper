<?php
declare(strict_types=1);

namespace SetterUpper\SetupStep;

use PHPUnit\Framework\TestCase;

/**
 * Class ReportTimeZoneStepTest
 *
 * @author Casey McLaughlin <caseyamcl@gmail.com>
 */
class ReportTimeZoneStepTest extends TestCase
{
    public function testDependsOnReturnsEmptyIterator(): void
    {
        $this->assertEmpty(ReportTimeZoneStep::dependsOn());
    }

    public function testMustRunBeforeReturnsEmptyIterator(): void
    {
        $this->assertEmpty(ReportTimeZoneStep::mustRunBefore());
    }

    public function testToString(): void
    {
        $step = new ReportTimeZoneStep();
        $this->assertStringContainsString('timezone', (string) $step);
    }

    public function testInvokeReportsTimezone(): void
    {
        $expectedTimezone = date_default_timezone_get();
        $message = (new ReportTimeZoneStep())->__invoke()->getMessage();
        $this->assertStringContainsString(sprintf('<info>%s</info>', $expectedTimezone), $message);
    }
}
