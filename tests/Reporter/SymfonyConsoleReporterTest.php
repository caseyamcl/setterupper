<?php
declare(strict_types=1);

namespace SetterUpper\Reporter;

use PHPUnit\Framework\TestCase;
use SetterUpper\SetupStepResult;
use Symfony\Component\Console\Output\BufferedOutput;

class SymfonyConsoleReporterTest extends TestCase
{
    /**
     * @dataProvider reportResultDataProvider
     * @param SetupStepResult $result
     * @param string $expectedOutput
     */
    public function testReportResult(SetupStepResult $result, string $expectedOutput)
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
