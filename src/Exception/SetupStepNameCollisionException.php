<?php
declare(strict_types=1);

namespace SetterUpper\Exception;

use RuntimeException;
use Throwable;

/**
 * Class SetupStepNameCollissionException
 *
 * @author Casey McLaughlin <caseyamcl@gmail.com>
 */
class SetupStepNameCollisionException extends RuntimeException
{
    public function __construct(string $className, int $code = 0, Throwable $previous = null)
    {
        $message = sprintf(
            'Name collision detected in setup step collection for class: %s',
            $className
        );

        parent::__construct($message, $code, $previous);
    }
}
