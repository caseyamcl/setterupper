<?php
declare(strict_types=1);

namespace SetterUpper\CliCommand;

use Psr\Log\AbstractLogger;
use SetterUpper\SetupStep;
use SetterUpper\SetupStepResult;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class SetupConsoleLogger
 *
 * @author Casey McLaughlin <caseyamcl@gmail.com>
 */
class ConsoleSetupLogger extends AbstractLogger
{
    /**
     * @var OutputInterface
     */
    private $output;
    /**
     * @var int
     */
    private $minStepReportVerbosity;

    /**
     * SetupConsoleLogger constructor.
     * @param OutputInterface $output
     * @param int $minStepReportVerbosity
     */
    public function __construct(OutputInterface $output, $minStepReportVerbosity = OutputInterface::VERBOSITY_VERBOSE)
    {
        $this->output = $output;
        $this->minStepReportVerbosity = $minStepReportVerbosity;
    }

    /**
     * {@inheritDoc}
     */
    public function log($level, $message, array $context = array())
    {
        if (($context['setup_step'] ?? null) instanceof SetupStep
            && $this->output->getVerbosity() >= $this->minStepReportVerbosity) {
            $this->output->writeln($message);
        }

        if (($context['step_result'] ?? null) instanceof SetupStepResult) {
            $result = $context['step_result'];

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
                default:
                    $msgTemplate = '• %s';
                    break;
            }

            $this->output->writeln(sprintf($msgTemplate, $result->getMessage()));

            foreach ($result->getNotes() as $note) {
                $this->output->writeln('   • ' . trim($note));
            }
        }
    }
}
