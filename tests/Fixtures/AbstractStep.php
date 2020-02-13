<?php
declare(strict_types=1);

namespace SetterUpper\Fixtures;

use SetterUpper\SetupStep;
use SetterUpper\SetupStepResult;

/**
 * Class AbstractStep
 *
 * @author Casey McLaughlin <caseyamcl@gmail.com>
 */
abstract class AbstractStep implements SetupStep
{
    /**
     * @var string
     */
    private $status;

    /**
     * @var bool
     */
    private $required;

    /**
     * AbstractStep constructor.
     * @param string $status
     * @param bool $required
     */
    public function __construct(string $status = SetupStepResult::STATUS_SUCCEEDED, bool $required = false)
    {
        $this->status = $status;
        $this->required = $required;
    }

    public static function dependsOn(): iterable
    {
        return [];
    }

    public static function mustRunBefore(): iterable
    {
        return [];
    }

    public function isAlreadySetup(): bool
    {
        return false;
    }

    public function __invoke(): SetupStepResult
    {
        return new SetupStepResult($this->status, get_called_class(), $this->required, ['notes']);
    }

    public function __toString(): string
    {
        return get_called_class();
    }
}
