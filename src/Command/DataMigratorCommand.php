<?php

namespace App\Command;

use App\Service\RestCountriesAPIHelper;
use Doctrine\DBAL\Exception;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

#[AsCommand(
    name: 'app:data-migrator',
    description: 'This console application allows you to import data from RestCountries API',
)]
class DataMigratorCommand extends Command
{

    private RestCountriesAPIHelper $restCountriesAPIHelper;

    public function __construct(RestCountriesAPIHelper $restCountriesAPIHelper)
    {
        parent::__construct();
        $this->restCountriesAPIHelper = $restCountriesAPIHelper;
    }

    protected function configure(): void
    {
        $this
            ->addArgument('mode', InputArgument::OPTIONAL,
                'Data load mode  <Append, Overwrite>')
            ->addOption('load-regions', null, InputOption::VALUE_NONE,
                'Load all region data into the database')
            ->addOption('load-sub-regions', null, InputOption::VALUE_NONE,
                'Load all sub-region data into the database')
            ->addOption('get-countries', null, InputOption::VALUE_NONE,
                'Load all country data into the database');
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     * @throws Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $mode = $input->getArgument('mode');

        if (!$mode)
            $mode = 'default';

        $io->note(sprintf('You passed an argument: %s', $mode));


        if ($input->getOption('load-regions')) {
            $io->info(sprintf("Loading regions using <%s> mode...", $mode));
            $count = $this->restCountriesAPIHelper->loadAllRegions($mode);
            $io->success(sprintf("%s regions successfully recorded in database", $count));
        } elseif ($input->getOption('load-sub-regions')) {
            $io->info(sprintf("Loading sub regions using <%s> mode...", $mode));
            $count = $this->restCountriesAPIHelper->loadAllSubRegions($mode);
            $io->success(sprintf("%s sub regions successfully recorded in database", $count));
        }

        return Command::SUCCESS;
    }


}
