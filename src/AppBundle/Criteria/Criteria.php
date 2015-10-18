<?php

namespace AppBundle\Criteria;

use AppBundle\Entity\User;

interface Criteria
{
    /**
     * @param User $user
     * @return bool
     */
    function match(User $user);
}
