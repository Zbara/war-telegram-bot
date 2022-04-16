<?php

namespace App\Command;

use App\Parser\Alerts;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'zbara:parse',
    description: 'Парсинг тревог',
)]
class ParseCommand extends Command
{

    public function __construct(
        private Alerts $alerts
    )
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        while (true) {
            $this->alerts->handle();

            sleep(40);
        }
    }
}
