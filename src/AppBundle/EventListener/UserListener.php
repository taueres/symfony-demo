<?php

namespace AppBundle\EventListener;

use AppBundle\Entity\User;
use AppBundle\Event\CommentEvent;
use AppBundle\Repository\UserRepository;
use AppBundle\Service\UserBanOperator;

class UserListener
{
    private $userRepository;
    private $userBanOperator;

    public function __construct(UserRepository $userRepository, UserBanOperator $userBanOperator)
    {
        $this->userRepository = $userRepository;
        $this->userBanOperator = $userBanOperator;
    }

    public function onCommentDeleted(CommentEvent $commentEvent)
    {
        $comment = $commentEvent->getComment();
        $authorEmail = $comment->getAuthorEmail();

        $user = $this->userRepository->getUserByEmailAddress($authorEmail);

        if ($user instanceof User) {
            $this->userBanOperator->handleAutomaticBanForUser($user);
        }
    }
}
