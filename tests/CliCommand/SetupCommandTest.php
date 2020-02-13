<?php
declare(strict_types=1);

namespace SetterUpper\CliCommand;

use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;
use SetterUpper\Fixtures\StepA;
use SetterUpper\Fixtures\StepB;
use SetterUpper\Fixtures\StepC;
use SetterUpper\Fixtures\StepD;
use SetterUpper\SetupStepCollection;
use SetterUpper\SetupStepResult;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;

class SetupCommandTest extends TestCase
{
    public function testInstantiate(): void
    {
        $collection = SetupStepCollection::build(new StepA(), new StepB(), new StepC());
        $cmdObj = new SetupCommand($collection);

        $this->assertInstanceOf(SetupCommand::class, $cmdObj);
    }

    public function testExecuteWithDefaultOptions(): void
    {
        $collection = SetupStepCollection::build(new StepA(), new StepB(), new StepC());
        $cmdObj = new SetupCommand($collection);

        $result = $cmdObj->run(new ArrayInput([], $cmdObj->getDefinition()), new BufferedOutput());
        $this->assertSame(0, $result);
    }

    public function testExecuteWithStopOnFailOptionAndFailingStep(): void
    {
        $collection = SetupStepCollection::build(
            new StepA(),
            new StepB(SetupStepResult::STATUS_FAILED, true),
            new StepD()
        );

        $cmdObj = new SetupCommand($collection);
        $result = $cmdObj->run(
            new ArrayInput(['-f' => true], $cmdObj->getDefinition()),
            new BufferedOutput()
        );
        $this->assertSame(1, $result);
    }

    public function testExecuteWithStopOnFailOptionAndNoFailingSteps(): void
    {
        $collection = SetupStepCollection::build(new StepA(), new StepB());
        $cmdObj = new SetupCommand($collection);
        $result = $cmdObj->run(
            new ArrayInput(['-f' => true], $cmdObj->getDefinition()),
            new BufferedOutput()
        );
        $this->assertSame(0, $result);
    }

    public function testExecuteWithCustomLoggerSet(): void
    {
        $collection = SetupStepCollection::build(new StepA(), new StepB());
        $cmdObj = new SetupCommand($collection, new NullLogger());
        $output = new BufferedOutput();
        $cmdObj->run(new ArrayInput(['-f' => true], $cmdObj->getDefinition()), $output);
        $this->assertEmpty(trim($output->fetch()));
    }

    public function testExecuteWithAppNameSet(): void
    {
        $collection = SetupStepCollection::build(new StepA(), new StepB());
        $cmdObj = new SetupCommand($collection, new NullLogger(), 'Setting up Test');
        $output = new BufferedOutput();
        $cmdObj->run(new ArrayInput(['-f' => true], $cmdObj->getDefinition()), $output);

        $this->assertSame('Setting up Test', trim($output->fetch()));
    }

    public function testCustomName(): void
    {
        $collection = SetupStepCollection::build(new StepA(), new StepB());
        $cmdObj = new SetupCommand($collection, new NullLogger(), 'Setting up Test', 'xsetupx');
        $this->assertSame('xsetupx', $cmdObj->getName());
    }
}
