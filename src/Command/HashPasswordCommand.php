<?php

namespace App\Command;

use App\Repository\AccountRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:hashPassword',
    description: 'Add a short description for your command',
)]
class HashPasswordCommand extends Command
{
    private AccountRepository $accountRepository;
    private EntityManagerInterface $em;

    public function __construct(
        accountRepository $accountRepository,
        EntityManagerInterface $em,
    ) { 
        parent::__construct(); //J'appelle le constructeur du parent
        $this->accountRepository = $accountRepository;
        $this->em = $em;
    }
    protected function configure(): void
    {

    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $i = 0;
        $accountEntity = $this->accountRepository->findAll();
        foreach ($accountEntity as $account) {
            $account->setPassword('$2y$13$7B2Dotb7hi61U4EoQHXh3eIzQVUV8bB2VksDwWha.Ms71e27pEKFa');
            $this->em->persist($account);
            $i++;
        }
        $this->em->flush();
        $output->writeln("Il y a eu $i comptes modifiés");
        return Command::SUCCESS;
    }
}
