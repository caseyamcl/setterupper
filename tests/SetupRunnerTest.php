<?php
declare(strict_types=1);

namespace SetterUpper;

use PHPUnit\Framework\TestCase;

class SetupRunnerTest extends TestCase
{
    public function testInstantiate()
    {
        $obj = new SetupRunner();
        $this->assertInstanceOf(SetupRunner::class, $obj);
    }

    public function testRun()
    {

    }
}
