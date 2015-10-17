<?php

namespace AppBundle\Event;

use AppBundle\Entity\User;
use Symfony\Component\EventDispatcher\GenericEvent;

class UserEvent extends GenericEvent
{
    const EVENT_USER_LOCKED = 'user.locked';

    public function __construct(User $user)
    {
        parent::__construct($user);
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->getSubject();
    }
}
