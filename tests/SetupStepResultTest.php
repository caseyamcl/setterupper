<?php
declare(strict_types=1);

namespace SetterUpper;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class SetupStepResultTest extends TestCase
{
    public function testInstantiate()
    {
        $obj = new SetupStepResult(SetupStepResult::STATUS_SKIPPED);
        $this->assertInstanceOf(SetupStepResult::class, $obj);
    }

    public function testSucceed()
    {
        $obj = SetupStepResult::succeed('test message');

        $this->assertSame(SetupStepResult::STATUS_SUCCEEDED, $obj->getStatus());
        $this->assertSame('test message', $obj->getMessage());
    }

    public function testNotesOnly()
    {
        $obj = SetupStepResult::notesOnly('test');

        $this->assertSame(SetupStepResult::STATUS_UNDETERMINED, $obj->getStatus());
        $this->assertSame('test', $obj->getMessage());
    }

    public function testFail()
    {
        $obj = SetupStepResult::fail('fail message');

        $this->assertSame(SetupStepResult::STATUS_FAILED, $obj->getStatus());
        $this->assertSame('fail message', $obj->getMessage());
    }

    public function testSkip()
    {
        $obj = SetupStepResult::skip('skip message');

        $this->assertSame(SetupStepResult::STATUS_SKIPPED, $obj->getStatus());
        $this->assertSame('skip message', $obj->getMessage());
    }

    public function testGetNotes()
    {
        $obj = SetupStepResult::succeed('test message', false, ['test1', 'test2']);
        $this->assertSame(['test1', 'test2'], $obj->getNotes());
    }

    public function testWithAddedNotesReturnsNewObject()
    {
        $obj = SetupStepResult::succeed('test', true, ['test2']);
        $obj2 = $obj->withAddedNotes('test3');

        $this->assertNotEquals(spl_object_hash($obj), spl_object_hash($obj2));
        $this->assertSame(['test2', 'test3'], $obj2->getNotes());
    }

    public function testWasRequired()
    {
        $obj = SetupStepResult::notesOnly('test notes');
        $this->assertFalse($obj->wasRequired());
    }

    public function testConstructorThrowsExceptionWithInvalidStatus()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid status');
        new SetupStepResult('invalid');
    }
}
