<?php

namespace App\Command;

use App\Repository\GameRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:change-game-name',
    description: 'Add a short description for your command',
)]
class ChangeGameNameCommand extends Command
{
    private GameRepository $gameRepository;
    private EntityManagerInterface $em;

    public function __construct(
        GameRepository $gameRepository,
        EntityManagerInterface $em,
    ) { 
        parent::__construct(); //J'appelle le constructeur du parent
        $this->gameRepository = $gameRepository;
        $this->em = $em;
    }

    protected function configure(): void
    {
        $this
            ->addArgument('id', InputArgument::REQUIRED, "Id d'un jeu existants")
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $id = $input->getArgument('id');
        $gameEntity = $this->gameRepository->find($id);
        if($gameEntity === null){
            $output->writeln('Jeu introuvable!');
            return Command::FAILURE;
        } else {
            $gameEntity->setName('Toto');
            $this->em->persist($gameEntity);
            $this->em->flush();
            $output->writeln("Le jeu avec l'id ". $id ." a maintenant le nom Toto");
            return Command::SUCCESS;
        }
    }
}
