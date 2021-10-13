<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;

class AppCommand extends Command
{
    protected static $defaultName = 'App:purge';
    protected static $defaultDescription = 'Description de la commande';
    protected $em;

    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct();
        $this->em = $em;
    }

    protected function configure(): void
    {
        $this
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('dry-run', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $arg1 = $input->getArgument('arg1');

        $users = $this->em->getRepository(User::class)->findOldUser();

        if($input->getOption('dry-run')) {
            $io->note(sprintf('%s Users seront %s', count($users), $arg1));
        }
        else {
            foreach ($users as $user) {
                if ('archive' === $arg1) {
                    $user->setArchive(true);
                }
                elseif('delete' === $arg1) {
                    $this->em->remove($user);
                }
            }

            $this->em->flush();

            $io->note(sprintf('%s Users %s', count($users), $arg1));
        }

        return Command::SUCCESS;
    }
}
