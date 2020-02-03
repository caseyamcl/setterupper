<?php
declare(strict_types=1);

namespace SetterUpper\SetupStep;

use PHPUnit\Framework\TestCase;
use SebastianBergmann\CodeCoverage\Report\Xml\Report;

/**
 * Class ReportTimeZoneStepTest
 *
 * @author Casey McLaughlin <caseyamcl@gmail.com>
 */
class ReportTimeZoneStepTest extends TestCase
{
    public function testDependsOnReturnsEmptyIterator()
    {
        $this->assertEmpty(ReportTimeZoneStep::dependsOn());
    }

    public function testMustRunBeforeReturnsEmptyIterator()
    {
        $this->assertEmpty(ReportTimeZoneStep::mustRunBefore());
    }

    public function testIsAlreadySetupReturnsFalse()
    {
        $step = new ReportTimeZoneStep();
        $this->assertFalse($step->isAlreadySetup());
    }

    public function testInvokeReportsTimezone()
    {
        $expectedTimezone = date_default_timezone_get();
        $message = (new ReportTimeZoneStep())->__invoke()->getMessage();
        $this->assertStringContainsString(sprintf('<info>%s</info>', $expectedTimezone), $message);
    }
}
