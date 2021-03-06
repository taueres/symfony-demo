<?php

namespace AppBundle\Service;

use AppBundle\Entity\User;
use AppBundle\Event\UserEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class UserManager
{
    /** @var  EventDispatcherInterface */
    private $eventDispatcher;

    /**
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(EventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @param User $user
     * @return User
     */
    public function ban(User $user)
    {
        if ($user->isBanned()) throw new \LogicException('User is already banned!');

        $user->setBanned(true);

        $this->eventDispatcher->dispatch(UserEvent::EVENT_USER_BANNED, new UserEvent($user));

        return $user;
    }
}