<?php

namespace Kassner\AuthBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Group
 *
 * @ORM\Table(name="auth_group")
 * @ORM\Entity
 * @UniqueEntity(fields={"name"}, message="group.message.nameInUse")
 */
class Group
{

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     * @Assert\NotBlank()
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="Kassner\AuthBundle\Entity\User", mappedBy="group")
     */
    private $users;

    /**
     * @ORM\ManyToMany(targetEntity="Kassner\AuthBundle\Entity\Rule", inversedBy="groups")
     * @ORM\JoinTable(name="auth_group_rules")
     */
    private $rules;

    public function __construct()
    {
        $this->rules = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getRuleCodes()
    {
        $rules = array();

        foreach ($this->rules as $rule) {
            $rules[] = $rule->getCode();
        }

        return $rules;
    }

    public function getRules()
    {
        return $this->rules;
    }

}