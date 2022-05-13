<?php

namespace App\Command;

use App\Repository\MessageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:delete-banword',
    description: 'Add a short description for your command',
)]
class DeleteBanwordCommand extends Command
{
    public function __construct(
        private MessageRepository $messageRepository,
        private EntityManagerInterface $entityManager
    )
    {
        parent::__construct();
    }

    protected function configure(): void
    {
 
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $banWordList = ['Pokemon', 'Digimon', 'Barbie', 'FromSoftSuck', 'UbisoftTheBest', 'BethesdaUnderatted'];
        $count = 0;
        $messageEntities = $this->messageRepository->findAll();
        foreach ($messageEntities as $message) {
            foreach ($banWordList as $banWord) {
                if (str_contains($message->getContent(), $banWord)) {
                    $count++;
                    $message->getCreatedBy()->incrementNbBanWord();
                    $this->entityManager->remove($message);
                }
            }
        }
        $this->entityManager->flush();
        $output->writeln($count . ' messages ont été supprimés.');
        return Command::SUCCESS;
    }
}
