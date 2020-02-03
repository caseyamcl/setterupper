<?php
declare(strict_types=1);

namespace SetterUpper\Reporter;

use PHPUnit\Framework\TestCase;
use SetterUpper\SetupStepResult;
use Symfony\Component\Console\Output\BufferedOutput;

class NullReporterTest extends TestCase
{
    /**
     * @dataProvider reportResultDataProvider
     * @param SetupStepResult $result
     */
    public function testReportResult(SetupStepResult $result)
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
