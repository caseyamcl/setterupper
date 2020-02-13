<?php
declare(strict_types=1);

namespace SetterUpper\Reporter;

use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;
use SetterUpper\Reporter;
use SetterUpper\SetupStep;
use SetterUpper\SetupStepResult;

/**
 * Class Psr6Reporter
 *
 * @author Casey McLaughlin <caseyamcl@gmail.com>
 */
class Psr6Reporter implements Reporter
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param SetupStep $step
     */
    public function reportStep(SetupStep $step): void
    {
        $this->logger->info('Running setup step: ' . (string) $step);
    }

    /**
     * @inheritDoc
     */
    public function reportResult(SetupStepResult $result): void
    {
        // Set a default, which will almost certainly be overridden
        $logLevel = LogLevel::ERROR;

        switch ($result->getStatus()) {
            case SetupStepResult::STATUS_SUCCEEDED:
                $logLevel = LogLevel::INFO;
                break;
            case SetupStepResult::STATUS_FAILED:
                $logLevel = $result->wasRequired() ? LogLevel::ERROR : LogLevel::WARNING;
                break;
            case SetupStepResult::STATUS_SKIPPED:
            case SetupStepResult::STATUS_UNDETERMINED:
                $logLevel = LogLevel::NOTICE;
                break;
        }

        $notesArray = (! empty($result->getNotes())) ? ['notes' => $result->getNotes()] : [];
        $this->logger->log($logLevel, $result->getMessage(), $notesArray);
    }
}
