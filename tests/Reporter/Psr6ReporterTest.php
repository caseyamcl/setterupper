<?php
declare(strict_types=1);

namespace SetterUpper\Reporter;

use PHPUnit\Framework\TestCase;
use Psr\Log\Test\TestLogger;
use SetterUpper\SetupStepResult;

class Psr6ReporterTest extends TestCase
{
    /**
     * @dataProvider reportResultDataProvider
     */
    public function testReportResult(SetupStepResult $result, array $expected)
    {
        $buffer = new TestLogger();
        $obj = new Psr6Reporter($buffer);
        $obj->reportResult($result);
        $this->assertEquals($expected, current($buffer->records));
    }

    /**
     * @return array|array[]
     */
    public function reportResultDataProvider(): array
    {
        return [
            [SetupStepResult::succeed('test'), ['level' => 'info', 'message' => 'test', 'context' => []]],
            [SetupStepResult::fail('test', false), ['level' => 'warning', 'message' => 'test', 'context' => []]],
            [SetupStepResult::fail('test', true), ['level' => 'error', 'message' => 'test', 'context' => []]],
            [SetupStepResult::skip('test'), ['level' => 'notice', 'message' => 'test', 'context' => []]],
            [SetupStepResult::notesOnly('test'), ['level' => 'notice', 'message' => 'test', 'context' => []]],
            [
                SetupStepResult::succeed('test', true, ['test', 'test2']),
                ['level' => 'info', 'message' => 'test', 'context' => ['notes' => ['test', 'test2']]]
            ]
        ];
    }
}
