<?php

declare(strict_types=1);

namespace App\Command;

use App\Config\Configuration;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;

#[AsCommand(
    name: self::COMMAND_NAME,
    description: 'Running the data transformation.',
)]
class DataTransformCommand extends Command
{
    final public const COMMAND_NAME = 'data:transform';

    private const ARG_CONFIG_FILE = 'config-file';

    protected function configure(): void
    {
        $this
            ->addArgument(
                self::ARG_CONFIG_FILE,
                InputArgument::REQUIRED,
                'The path to the configuration file.',
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        /** @var string $filePath */
        $filePath = $input->getArgument(self::ARG_CONFIG_FILE);

        $output->writeln("Loading configuration from: ${filePath}");
        new Configuration(
            $filePath,
            new Filesystem(),
        );

        return Command::SUCCESS;
    }
}
