<?php
declare(strict_types=1);

namespace SetterUpper;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use SetterUpper\Fixtures\StepA;
use SetterUpper\Fixtures\StepB;
use SetterUpper\Fixtures\StepC;
use SetterUpper\Fixtures\StepThrowsException;

class SetupRunnerTest extends TestCase
{
    public function testInstantiate()
    {
        $obj = new SetupRunner();
        $this->assertInstanceOf(SetupRunner::class, $obj);
    }

    public function testRunWithEmptyIterator()
    {
        $obj = new SetupRunner();
        $this->assertEmpty($obj->runAll([]));
    }

    public function testRunWithStepsInArray()
    {
        $obj = new SetupRunner();
        $results = $obj->runAll([new StepA(), new StepB(), new StepC()]);
        $this->assertCount(3, $results);
    }

    public function testWithFailureStep()
    {
        $obj = new SetupRunner();
        $coll = SetupStepCollection::build(
            new StepA(SetupStepResult::STATUS_FAILED),
            new StepB(SetupStepResult::STATUS_SUCCEEDED)
        );
        $results = $obj->runAll($coll);
        $this->assertSame(SetupStepResult::STATUS_FAILED, current($results)->getStatus());
        $this->assertSame(SetupStepResult::STATUS_SUCCEEDED, next($results)->getStatus());
    }

    public function testWithFailureStepAndStopOnFailIsTrue()
    {
        $obj = new SetupRunner();
        $coll = SetupStepCollection::build(
            new StepA(SetupStepResult::STATUS_FAILED),
            new StepB(SetupStepResult::STATUS_SUCCEEDED)
        );
        $results = $obj->runAll($coll, true);

        $this->assertSame(1, count($results));
        $this->assertSame(SetupStepResult::STATUS_FAILED, current($results)->getStatus());
    }

    public function testRunWithStepThatThrowsException()
    {
        $this->expectException(RuntimeException::class);

        $obj = new SetupRunner();
        $coll = SetupStepCollection::build(new StepA(), new StepThrowsException(), new StepC());
        $obj->runAll($coll);
    }

    public function testRunWithInvalidArguments()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Each item in the iterator must be an instance of');
        $obj = new SetupRunner();
        $obj->runAll([1, 2, 3]); // not instances of SetupStep
    }
}
