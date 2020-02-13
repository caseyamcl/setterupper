<?php
declare(strict_types=1);

namespace SetterUpper;

use PHPUnit\Framework\TestCase;
use SetterUpper\Fixtures\StepA;
use SetterUpper\Fixtures\StepB;
use SetterUpper\Fixtures\StepC;
use SetterUpper\Reporter\NullReporter;

/**
 * SetterUpper Test (technically an integration test)
 *
 * @author Casey McLaughlin <caseyamcl@gmail.com>
 */
class SetterUpperTest extends TestCase
{
    public function testInstantiateWithDefaultValues(): void
    {
        $sr = new SetterUpper();
        $this->assertInstanceOf(SetterUpper::class, $sr);
    }

    public function testUsingCustomConstructor(): void
    {
        $reporter = new NullReporter();
        $sr = SetterUpper::buildUsingReporter($reporter);
        $this->assertInstanceOf(SetterUpper::class, $sr);
    }

    public function testAddStepReturnsInstanceOfSetterUpperClass(): void
    {
        $sr = new SetterUpper();
        $sr2 = $sr->addStep(new StepA());
        $this->assertSame($sr, $sr2);
    }

    public function testRunIteratesSetupSteps(): void
    {
        $sr = new SetterUpper();
        $sr->add(new StepA(), new StepB(), new StepC());

        foreach ($sr->run() as $result) {
            $this->assertInstanceOf(SetupStepResult::class, $result);
        }
    }

    public function testRunAllReturnsArrayOfSetupSteps(): void
    {
        $sr = new SetterUpper();
        $sr->add(new StepA(), new StepB(), new StepC());

        $results = $sr->runAll();
        $this->assertCount(3, $results);
    }

    public function testGetSteps(): void
    {
        $sr = new SetterUpper();
        $this->assertInstanceOf(SetupStepCollection::class, $sr->getSteps());
    }

    public function testGetRunner(): void
    {
        $sr = new SetterUpper();
        $this->assertInstanceOf(SetupRunner::class, $sr->getRunner());
    }
}
