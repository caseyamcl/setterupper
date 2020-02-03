<?php
declare(strict_types=1);

namespace SetterUpper;

/**
 * Interface SetupStep
 *
 * @author Casey McLaughlin <caseyamcl@gmail.com>
 */
interface SetupStep
{
    /**
     * Get the classes that must run before this step
     *
     * @return iterable|string[]
     */
    public static function dependsOn(): iterable;

    /**
     * Get the classes that must run after this step
     *
     * @return iterable|string[]
     */
    public static function mustRunBefore(): iterable;

    /**
     *
     * @return bool
     */
    public function isAlreadySetup(): bool;

    /**
     * @return SetupStepResult
     */
    public function __invoke(): SetupStepResult;
}
