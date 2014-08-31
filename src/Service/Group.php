<?php

namespace Kassner\AuthBundle\Service;

use Doctrine\ORM\EntityManagerInterface;
use Kassner\AuthBundle\Entity\Group as GroupEntity;

class Group
{

    protected $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function find($id)
    {
        return $this->entityManager->getRepository('KassnerAuthBundle:Group')->find($id);
    }

    public function save(GroupEntity $user)
    {
        $this->entityManager->persist($user);
    }

}
