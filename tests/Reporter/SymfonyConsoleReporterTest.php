<?php
declare(strict_types=1);

namespace SetterUpper\Reporter;

use PHPUnit\Framework\TestCase;
use SetterUpper\Fixtures\StepA;
use SetterUpper\SetupStepResult;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Output\Output;

class SymfonyConsoleReporterTest extends TestCase
{
    public function testReportStepReportsStepsWhenOutputIsVerbose(): void
    {
        $output = new BufferedOutput(Output::VERBOSITY_VERBOSE);
        $reporter = new SymfonyConsoleReporter($output);
        $reporter->reportStep(new StepA());
        $this->assertSame('Running setup step: SetterUpper\Fixtures\StepA', trim($output->fetch()));
    }

    public function testReportStepDoesNotReportStepsWhenOutputIsNotVerbose(): void
    {
        $output = new BufferedOutput(Output::VERBOSITY_NORMAL);
        $reporter = new SymfonyConsoleReporter($output);
        $reporter->reportStep(new StepA());
        $this->assertEmpty(trim($output->fetch()));
    }

    /**
     * @dataProvider reportResultDataProvider
     * @param SetupStepResult $result
     * @param string $expectedOutput
     */
    public function testReportResult(SetupStepResult $result, string $expectedOutput): void
    {
        $output = new BufferedOutput();
        $reporter = new SymfonyConsoleReporter($output);
        $reporter->reportResult($result);
        $this->assertEquals($expectedOutput, trim($output->fetch()));
    }

    /**
     * @return array|array[]
     */
    public function reportResultDataProvider(): array
    {
        return [
            [SetupStepResult::succeed('test'), '✓ test'],
            [SetupStepResult::fail('test', false), '⚠ test'],
            [SetupStepResult::fail('test', true), '✗ test'],
            [SetupStepResult::skip('test'), '⟳ test'],
            [SetupStepResult::notesOnly('test'), '• test'],
            [SetupStepResult::succeed('test', true, ['test', 'test2']), "✓ test\n   • test\n   • test2"]
        ];
    }
}
