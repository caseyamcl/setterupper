<?php
declare(strict_types=1);

namespace SetterUpper\CliCommand;

use PHPUnit\Framework\TestCase;
use Psr\Log\LogLevel;
use SetterUpper\Fixtures\StepA;
use SetterUpper\SetupStepResult;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Output\OutputInterface;

class SetupConsoleLoggerTest extends TestCase
{
    public function testLogStep()
    {
        $output = new BufferedOutput(OutputInterface::VERBOSITY_VERBOSE);
        $logger = new ConsoleSetupLogger($output);

        $logger->log(LogLevel::INFO, 'Running step A', ['setup_step' => new StepA()]);
        $this->assertSame('Running step A', trim($output->fetch()));
    }

    /**
     * @dataProvider getTestLogResultData
     *
     * @param SetupStepResult $stepResult
     * @param string $expectedOutput
     */
    public function testLogResult(SetupStepResult $stepResult, string $expectedOutput)
    {
        $output = new BufferedOutput();
        $logger = new ConsoleSetupLogger($output);

        // Log level and message are irrelevant here, because the logger pulls its data directly from StepResult object
        $logger->log(LogLevel::INFO, $stepResult->getMessage(), ['step_result' => $stepResult]);
        $this->assertEquals($expectedOutput, trim($output->fetch()));
    }

    public function getTestLogResultData(): array
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
