<?php
declare(strict_types=1);

namespace SetterUpper\Reporter;

use SetterUpper\Reporter;
use SetterUpper\SetupStepResult;
use Symfony\Component\Console\Output\OutputInterface;
use Throwable;

/**
 * Class SymfonyConsoleReporter
 *
 * @author Casey McLaughlin <caseyamcl@gmail.com>
 */
class SymfonyConsoleReporter implements Reporter
{
    /**
     * @var OutputInterface
     */
    private $output;

    /**
     * SymfonyConsoleReporter constructor.
     *
     * @param OutputInterface $consoleOutput
     */
    public function __construct(OutputInterface $consoleOutput)
    {
        $this->output = $consoleOutput;
    }

    /**
     * @inheritDoc
     */
    public function reportResult(SetupStepResult $result): void
    {
        switch ($result->getStatus()) {
            case SetupStepResult::STATUS_SUCCEEDED:
                $msgTemplate = '<fg=green>✓</fg=green> %s';
                break;
            case SetupStepResult::STATUS_SKIPPED:
                $msgTemplate = '<fg=green>⟳</fg=green> %s';
                break;
            case SetupStepResult::STATUS_FAILED:
                $msgTemplate = $result->wasRequired() ? '<fg=red>✗</fg=red> %s' : '<fg=yellow>⚠</fg=yellow> %s';
                break;
            case SetupStepResult::STATUS_UNDETERMINED:
                $msgTemplate = '• %s';
                break;
        }

        $this->output->writeln(sprintf($msgTemplate, $result->getMessage()));

        foreach ($result->getNotes() as $note) {
            $this->output->writeln('   • ' . $note);
        }
    }
}
