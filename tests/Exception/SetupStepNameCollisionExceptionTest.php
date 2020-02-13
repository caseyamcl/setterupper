<?php
declare(strict_types=1);

namespace SetterUpper\Exception;

use PHPUnit\Framework\TestCase;
use SetterUpper\Fixtures\StepA;

/**
 * Setup Step Name Collision Exception
 *
 * Thrown when attempting to add multiple steps with the same key to a collection
 *
 * @author Casey McLaughlin <caseyamcl@gmail.com>
 */
class SetupStepNameCollisionExceptionTest extends TestCase
{
    public function testInstantiate(): void
    {
        $exception = new SetupStepNameCollisionException(StepA::class);
        $this->assertSame(
            sprintf('Name collision detected in setup step collection for class: %s', StepA::class),
            $exception->getMessage()
        );
    }
}
