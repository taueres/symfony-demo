<?php

namespace AppBundle\Tests\Criteria;

use AppBundle\Entity\User;
use AppBundle\Criteria\DistinctDeleterCriteria;

class DistinctDeleterCriteriaTest extends \PHPUnit_Framework_TestCase
{
    public function testNotMatchingWithoutDeletedComments()
    {
        $email = 'foobar@example.com';
        $limitMatching = 3;

        $user = $this->prophesize('AppBundle\Entity\User');
        $user->getEmail()
            ->shouldBeCalled()
            ->willReturn($email);

        $commentRepository = $this->prophesize('AppBundle\Repository\CommentRepository');
        $commentRepository->getCommentsByEmailAddress($email)
            ->shouldBeCalled()
            ->willReturn(array());

        $criteria = new DistinctDeleterCriteria($limitMatching, $commentRepository->reveal());

        $actualResult = $criteria->match($user->reveal());

        $this->assertFalse($actualResult);
    }

    public function testMatchingWithEnoughDistinctComments()
    {
        $email = 'foobar@example.com';
        $limitMatching = 2;

        $user1 = new User();
        $user1->setUsername('foo');
        $user2 = new User();
        $user2->setUsername('bar');

        $comment1 = $this->getCommentStub(true, $user1);
        $comment2 = $this->getCommentStub(true, $user2);
        $comment3 = $this->getCommentStub(true, $user1);

        $user = $this->prophesize('AppBundle\Entity\User');
        $user->getEmail()
            ->shouldBeCalled()
            ->willReturn($email);

        $commentRepository = $this->prophesize('AppBundle\Repository\CommentRepository');
        $commentRepository->getCommentsByEmailAddress($email)
            ->shouldBeCalled()
            ->willReturn([$comment1, $comment2, $comment3]);

        $criteria = new DistinctDeleterCriteria($limitMatching, $commentRepository->reveal());

        $actualResult = $criteria->match($user->reveal());

        $this->assertTrue($actualResult);
    }

    public function testMatchingWithoutSufficientDistinctComments()
    {
        $email = 'foobar@example.com';
        $limitMatching = 2;

        $user1 = new User();
        $user1->setUsername('foo');

        $comment1 = $this->getCommentStub(true, $user1);
        $comment2 = $this->getCommentStub(true, $user1);

        $user = $this->prophesize('AppBundle\Entity\User');
        $user->getEmail()
            ->shouldBeCalled()
            ->willReturn($email);

        $commentRepository = $this->prophesize('AppBundle\Repository\CommentRepository');
        $commentRepository->getCommentsByEmailAddress($email)
            ->shouldBeCalled()
            ->willReturn([$comment1, $comment2]);

        $criteria = new DistinctDeleterCriteria($limitMatching, $commentRepository->reveal());

        $actualResult = $criteria->match($user->reveal());

        $this->assertFalse($actualResult);
    }

    private function getCommentStub($isDeleted, $deleter = null)
    {
        $comment = $this->prophesize('AppBundle\Entity\Comment');
        $comment->isDeleted()
            ->willReturn($isDeleted);
        $comment->getDeletedBy()
            ->willReturn($deleter);

        return $comment->reveal();
    }
}
