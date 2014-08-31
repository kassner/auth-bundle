<?php

namespace Kassner\AuthBundle\Security;

use Symfony\Component\Security\Core\Authorization\Voter\RoleVoter;

class Voter extends RoleVoter
{

    public function supportsAttribute($attribute)
    {
        return true;
    }

    public function supportsClass($class)
    {
        return true;
    }

}