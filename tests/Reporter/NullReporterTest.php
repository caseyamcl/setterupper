<?php
declare(strict_types=1);

namespace SetterUpper\Reporter;

use PHPUnit\Framework\TestCase;
use SetterUpper\Fixtures\StepA;
use SetterUpper\SetupStepResult;

class NullReporterTest extends TestCase
{
    public function testReportStepDoesNothing(): void
    {
        $reporter = new NullReporter();
        $this->assertEmpty($reporter->reportStep(new StepA()));
    }

    /**
     * @dataProvider reportResultDataProvider
     * @param SetupStepResult $result
     */
    public function testReportResultDoesNothing(SetupStepResult $result): void
    {
        $reporter = new NullReporter();
        $this->assertEmpty($reporter->reportResult($result));
    }

    /**
     * @return array|array[]
     */
    public function reportResultDataProvider(): array
    {
        return [
            [SetupStepResult::succeed('test')],
            [SetupStepResult::fail('test', false)],
            [SetupStepResult::fail('test', true)],
            [SetupStepResult::skip('test')],
            [SetupStepResult::notesOnly('test')],
            [SetupStepResult::succeed('test', true, ['test', 'test2'])]
        ];
    }
}
