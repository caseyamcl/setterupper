<?php
declare(strict_types=1);

namespace SetterUpper;

use Generator;
use InvalidArgumentException;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;
use Psr\Log\NullLogger;

/**
 * Class SetupRunner
 *
 * @author Casey McLaughlin <caseyamcl@gmail.com>
 */
class SetupRunner
{
    use LoggerAwareTrait;

    /**
     * SetupRunner constructor.
     *
     * @param LoggerInterface $logger
     */
    public function __construct(?LoggerInterface $logger = null)
    {
        $this->logger = $logger ?: new NullLogger();
    }

    /**
     * @param iterable|SetupStep[] $steps   Any iterable containing setup steps
     * @param bool $stopOnError             If true, this method will stop on the first failure
     * @return Generator|SetupStepResult[]  Generator which yields SetupStepResult instances
     */
    public function run(iterable $steps, bool $stopOnError = false): iterable
    {
        foreach ($steps as $step) {
            // Check step
            if (! $step instanceof SetupStep) {
                throw new InvalidArgumentException(sprintf(
                    'Each item in the iterator must be an instance of %s (you sent a/an %s)',
                    SetupStep::class,
                    is_object($step) ? get_class($step) : gettype($step)
                ));
            }

            // Log step, invoke it, and log result
            $this->logger->info(sprintf('Running step: %s', (string) $step), ['setup_step' => $step]);
            $result = $step->__invoke();
            $this->logResult($step, $result);

            yield $result;

            if ($stopOnError && $result->getStatus() === SetupStepResult::STATUS_FAILED) {
                return;
            }
        }
    }

    /**
     * Run all steps and return a report
     *
     * @param iterable|SetupStep[] $steps
     * @param bool $stopOnError
     * @return array|SetupStepResult[]
     */
    public function runAll(iterable $steps, bool $stopOnError = false): array
    {
        return iterator_to_array($this->run($steps, $stopOnError));
    }

    /**
     * @param SetupStep $step
     * @param SetupStepResult $result
     */
    private function logResult(SetupStep $step, SetupStepResult $result): void
    {
        $context = ['setup_step' => $step, 'step_result' => $result];

        switch ($result->getStatus()) {
            case SetupStepResult::STATUS_SUCCEEDED:
                $logLevel = LogLevel::INFO;
                break;
            case SetupStepResult::STATUS_FAILED:
                $logLevel = $result->wasRequired() ? LogLevel::ERROR : LogLevel::WARNING;
                break;
            default:
                $logLevel = LogLevel::NOTICE;
                break;
        }

        $this->logger->log($logLevel, $result->getMessage(), $context);
    }
}
