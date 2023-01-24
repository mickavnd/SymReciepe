<?php

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:create-administrator',
    description: 'Create an administrator',
)]
class CreateAdministratorCommand extends Command
{
    private EntityManagerInterface $manager ;


    public function __construct(EntityManagerInterface $manager)
    {
        parent::__construct('app:create-administrator');

        $this->manager = $manager;
    }

    protected function configure(): void
    {
        $this
            ->addArgument('full_Name', InputArgument::OPTIONAL, 'Full Name')
            ->addArgument('email', InputArgument::OPTIONAL, 'email')
            ->addArgument('password', InputArgument::OPTIONAL, 'Password')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $helper =$this->getHelper('question');
        $io = new SymfonyStyle($input, $output);
        
        $fullName = $input->getArgument('full_Name');
        if(!$fullName){
              $question = new Question('Quel est le nom de  que vous voulez mettre?');
              $fullName = $helper->ask($input,$output,$question);
            }
        $email = $input->getArgument('email');
        if(!$email){
            $question = new Question('Quel est L\'email de ' .$fullName .'?');
            $email = $helper->ask($input,$output,$question);
          }
        $plainPassword = $input->getArgument('password');
        if(!$plainPassword){
            $question = new Question('Quel est le MPD ' . $fullName .' ?');
            $plainPassword = $helper->ask($input,$output,$question);
          }


        $user = new User();
        $user ->setFullName($fullName)
               ->setEmail($email)
               ->setPlainPassword($plainPassword)
               ->setRoles(['ROLE_USER','ROLE_ADMIN']);

        $this->manager->persist($user);
        $this->manager->flush();

              


        

        $io->success('le nouvel admin a etait créer');

        return Command::SUCCESS;
    }
}
