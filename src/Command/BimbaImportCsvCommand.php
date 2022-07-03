<?php

namespace App\Command;

use App\Service\ImportCsv;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'bimba:import:csv',
    description: 'Import IMDB file',
)]
class BimbaImportCsvCommand extends Command
{
    private ImportCsv $import;

    public function __construct(ImportCsv $import, string $name = null)
    {
        $this->import = $import;
        parent::__construct($name);
    }

    protected function configure(): void
    {
        $this
            ->addArgument('file', InputArgument::REQUIRED, 'Archivo CSV')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $file = $input->getArgument('file');

        ($this->import)($file);

        return Command::SUCCESS;
    }
}
