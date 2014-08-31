<?php

namespace Kassner\AuthBundle\Command;

use Kassner\AuthBundle\Entity\User as UserEntity;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class AuthUserCreateCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        $this
                ->setName('auth:user:create')
                ->setDescription('Create an user.')
                ->addArgument('username', InputArgument::REQUIRED, 'Username')
                ->addArgument('group', InputArgument::REQUIRED, 'Group ID')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $username = $input->getArgument('username');

        if (empty($username) || !preg_match('/([a-zA-Z0-9-_@.]+)/', $username)) {
            throw new \Exception('Invalid username.');
        }

        $userService = $this->getContainer()->get('auth.user');
        $user = $userService->findOneByUsername($username);

        if ($user) {
            throw new \Exception('This username is already registered.');
        }

        $groupService = $this->getContainer()->get('auth.group');
        $group = $groupService->find($input->getArgument('group'));

        if (empty($group)) {
			throw new \Exception('This group does not exist.');
        }

        $helper = $this->getHelperSet()->get('dialog');
		$password = $helper->askHiddenResponse($output, 'Please enter the password:', false);
		$confirmedPassword = $helper->askHiddenResponse($output, 'Please confirm the password:', false);

        if (strcmp($password, $confirmedPassword) !== 0) {
            throw new \Exception('The confirmation password don\'t match.');
        }

        $user = new UserEntity();
        $user->setUsername($username);
        $user->setGroup($group);

        $factory = $this->getContainer()->get('security.encoder_factory');
        $encoder = $factory->getEncoder($user);
        $encodedPassword = $encoder->encodePassword($password, $user->getSalt());
        $user->setPassword($encodedPassword);
        $userService->save($user);
        
        $this->getContainer()->get('doctrine.orm.default_entity_manager')->flush();
        
        $output->writeln(sprintf('<info>User %s created.</info>', $username));
    }

}
