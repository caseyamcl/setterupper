<?php
declare(strict_types=1);

namespace SetterUpper;

use MJS\TopSort\CircularDependencyException;
use MJS\TopSort\ElementNotFoundException;
use PHPUnit\Framework\TestCase;
use SetterUpper\Exception\SetupStepNameCollisionException;
use SetterUpper\Fixtures\StepA;
use SetterUpper\Fixtures\StepB;
use SetterUpper\Fixtures\StepC;
use SetterUpper\Fixtures\StepE;
use SetterUpper\Fixtures\StepF;
use SetterUpper\Fixtures\StepG;

class SetupStepCollectionTest extends TestCase
{
    public function testInstantiate()
    {
        $coll = new SetupStepCollection();
        $this->assertInstanceOf(SetupStepCollection::class, $coll);
    }

    public function testCountIsZeroWhenNoItems()
    {
        $coll = new SetupStepCollection();
        $this->assertSame(0, count($coll));
    }

    public function testGetIteratorReturnsIteratorEvenWhenNoItems()
    {
        $coll = new SetupStepCollection();
        $this->assertIsIterable($coll);
    }

    public function testBasicDependencies()
    {
        $coll = new SetupStepCollection();
        $coll->add(new StepA(), new StepB());

        foreach ($coll as $item) {
            $this->assertInstanceOf(SetupStep::class, $item);
        }
    }

    public function testDependenciesGenerateInCorrectOrder()
    {
        $coll = new SetupStepCollection();
        $coll->add(new StepA(), new StepB(), new StepC());

        $array = iterator_to_array($coll);
        $this->assertInstanceOf(StepA::class, current($array));
        $this->assertInstanceOf(StepC::class, next($array));
        $this->assertInstanceOf(StepB::class, next($array));
    }

    public function testDependencyLoopThrowsException()
    {
        $this->expectException(CircularDependencyException::class);
        $this->expectExceptionMessage('Circular');

        $coll = new SetupStepCollection();
        $coll->add(new StepE(), new StepF());
        iterator_to_array($coll->getIterator());
    }

    public function testNonExistentDependencyThrowsException()
    {
        $this->expectException(ElementNotFoundException::class);
        $this->expectExceptionMessage('not found');

        $coll = new SetupStepCollection();
        $coll->add(new StepG());
        iterator_to_array($coll->getIterator());
    }

    public function testAddingTwoOfTheSameClassThrowsException()
    {
        $this->expectException(SetupStepNameCollisionException::class);
        SetupStepCollection::build(new StepA(), new StepA());
    }
}
