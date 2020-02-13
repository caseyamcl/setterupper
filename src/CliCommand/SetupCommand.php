<?php
declare(strict_types=1);

namespace SetterUpper\CliCommand;

use Exception;
use Psr\Log\LoggerInterface;
use SetterUpper\SetupRunner;
use SetterUpper\SetupStepCollection;
use SetterUpper\SetupStepResult;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class SetupCommand
 *
 * @author Casey McLaughlin <caseyamcl@gmail.com>
 */
class SetupCommand extends Command
{
    protected static $defaultName = 'setup';

    /**
     * @var SetupStepCollection
     */
    private $setupSteps;

    /**
     * @var LoggerInterface|null
     */
    private $logger;

    /**
     * @var string|null
     */
    private $startMessage;

    /**
     * SetupCommand constructor.
     *
     * @param SetupStepCollection $setupSteps
     * @param LoggerInterface|null $logger
     * @param string|null $startMessage
     * @param string|null $name
     */
    public function __construct(
        SetupStepCollection $setupSteps,
        ?LoggerInterface $logger = null,
        ?string $startMessage = null,
        string $name = null
    ) {
        parent::__construct($name);
        $this->setupSteps = $setupSteps;
        $this->logger = $logger;
        $this->startMessage = $startMessage;
    }

    protected function configure()
    {
        parent::configure();

        $this->addOption(
            'stop-on-fail',
            'f',
            InputOption::VALUE_NONE,
            'Whether or not the setup process should exit on first failure'
        );
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|void
     * @throws Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $consoleLogger = new ConsoleSetupLogger($output);
        $runner = new SetupRunner($this->logger ?: $consoleLogger);

        if ($this->startMessage) {
            $output->writeln($this->startMessage);
        }

        $report = $runner->runAll($this->setupSteps, $input->getOption('stop-on-fail'));

        $consoleLogger->info('Setup complete', ['setup_report' => $report]);
        if ($this->logger) {
            $this->logger->info('Setup complete', ['setup_report' => $report]);
        }

        $lastItem = end($report);
        return ($lastItem && $lastItem->getStatus() === SetupStepResult::STATUS_FAILED) ? 1 : 0;
    }
}
