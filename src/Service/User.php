<?php

namespace Kassner\AuthBundle\Service;

use Doctrine\ORM\EntityManagerInterface;
use Kassner\AuthBundle\Entity\User as UserEntity;

class User
{

    protected $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function findOneByUsername($username)
    {
        return $this->entityManager->getRepository('KassnerAuthBundle:User')->findOneBy(array(
            'username' => $username
        ));
    }

    public function save(UserEntity $user)
    {
        $this->entityManager->persist($user);
    }

}
