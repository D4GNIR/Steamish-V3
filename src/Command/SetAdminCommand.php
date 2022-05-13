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
    name: 'app:set-admin',
    description: 'Add a short description for your command',
)]
class SetAdminCommand extends Command
{
    private AccountRepository $accountRepository;
    private EntityManagerInterface $entityManager;

    public function __construct(AccountRepository $accountRepository, EntityManagerInterface $entityManager)
    {
        parent::__construct();
        $this->accountRepository = $accountRepository;
        $this->entityManager = $entityManager;
    }

    protected function configure(): void
    {
        $this->addArgument('mail', InputArgument::REQUIRED, "Mail de l'account à passer en admin ")
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $mail = $input->getArgument('mail');
        $accountEntity = $this->accountRepository->findOneBy(['email'=>$mail]);
        
        if($accountEntity === null){
            $output->writeln('Utilisateur introuvable');
            return command::FAILURE;
        }else{
          $accountEntity->setRoles(['ROLE_ADMIN']);
          $this->entityManager->persist($accountEntity);
          $this->entityManager->flush();
          
          $output->writeln($accountEntity->getName().' a le rôle Admin');
          return command::SUCCESS;
        }
    }
}
