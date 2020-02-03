<?php
declare(strict_types=1);

namespace SetterUpper;

use InvalidArgumentException;
use SetterUpper\Reporter\NullReporter;

/**
 * Class SetupRunner
 *
 * @author Casey McLaughlin <caseyamcl@gmail.com>
 */
class SetupRunner
{
    /**
     * @var Reporter
     */
    private $reporter;

    /**
     * SetupRunner constructor.
     * @param Reporter|null $reporter
     */
    public function __construct(?Reporter $reporter = null)
    {
        $this->reporter = $reporter ?: new NullReporter();
    }

    /**
     * @param iterable|SetupStep[] $steps  Any iterable containing setup steps
     * @param bool $stopOnError            If true, this method will stop on the first failure
     * @return iterable|SetupStepResult[]  Generator which yields SetupStepResult instances
     */
    public function run(iterable $steps, bool $stopOnError = false): iterable
    {
        foreach ($steps as $step) {
            if (! $step instanceof SetupStep) {
                throw new InvalidArgumentException(sprintf(
                    'Each item in the iterator must be an instance of %s (you sent a/an %s)',
                    is_object($step) ? ('instance of ' . get_class(SetupStep::class)) : gettype($step),
                    get_class($step)
                ));
            }

            $result = $step->__invoke();
            $this->reporter->reportResult($result);

            if ($stopOnError && $result->getStatus() === SetupStepResult::STATUS_FAILED) {
                return $result;
            } else {
                yield $result;
            }
        }
    }
}
