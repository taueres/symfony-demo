<?php

namespace AppBundle\Service;

use AppBundle\Criteria\Criteria;
use AppBundle\Entity\User;

class UserBanOperator
{
    private $userManager;
    private $criteria;

    public function __construct(UserManager $userManager, Criteria $criteria)
    {
        $this->userManager = $userManager;
        $this->criteria = $criteria;
    }

    /**
     * @param User $user
     * @return bool
     */
    public function handleAutomaticBanForUser(User $user)
    {
        if ( ! $user->isAdmin() && $this->criteria->match($user)) {
            $this->userManager->ban($user);
            return true;
        }

        return false;
    }
}
