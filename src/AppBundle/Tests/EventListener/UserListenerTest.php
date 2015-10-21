<?php

namespace AppBundle\Tests\EventListener;

use AppBundle\Entity\Comment;
use AppBundle\Entity\User;
use AppBundle\Event\CommentEvent;
use AppBundle\EventListener\UserListener;
use Prophecy\Argument;

class UserListenerTest extends \PHPUnit_Framework_TestCase
{
    public function testListenerInvokesUserBanOperator()
    {
        $email = 'foobar@example.org';
        $user = new User();

        $comment = new Comment();
        $comment->setAuthorEmail($email);

        $commentEvent = new CommentEvent($comment);

        $userRepository = $this->prophesize('AppBundle\Repository\UserRepository');
        $userRepository->getUserByEmailAddress($email)
            ->shouldBeCalled()
            ->willReturn($user);

        $userBanOperator = $this->prophesize('AppBundle\Service\UserBanOperator');
        $userBanOperator->handleAutomaticBanForUser($user)
            ->shouldBeCalled()
            ->willReturn(true);

        $listener = new UserListener($userRepository->reveal(), $userBanOperator->reveal());

        $listener->onCommentDeleted($commentEvent);
    }

    public function testListenerWillNotProceedWithNullUser()
    {
        $email = 'foobar@example.org';

        $comment = new Comment();
        $comment->setAuthorEmail($email);

        $commentEvent = new CommentEvent($comment);

        $userRepository = $this->prophesize('AppBundle\Repository\UserRepository');
        $userRepository->getUserByEmailAddress($email)
            ->shouldBeCalled()
            ->willReturn(null);

        $userBanOperator = $this->prophesize('AppBundle\Service\UserBanOperator');
        $userBanOperator->handleAutomaticBanForUser(Argument::type('AppBundle\Entity\User'))
            ->shouldNotBeCalled();

        $listener = new UserListener($userRepository->reveal(), $userBanOperator->reveal());

        $listener->onCommentDeleted($commentEvent);
    }
}
