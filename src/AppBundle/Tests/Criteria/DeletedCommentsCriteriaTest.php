<?php

namespace AppBundle\Tests\Criteria;

use AppBundle\Criteria\DeletedCommentsCriteria;

class DeletedCommentsCriteriaTest extends \PHPUnit_Framework_TestCase
{
    public function testNotMatchingWithNoDeletedComments()
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

        $criteria = new DeletedCommentsCriteria($limitMatching, $commentRepository->reveal());

        $actualResult = $criteria->match($user->reveal());

        $this->assertFalse($actualResult);
    }

    public function testNotMatchingWithInsufficientDeletedComments()
    {
        $email = 'foobar@example.com';
        $limitMatching = 3;

        $user = $this->prophesize('AppBundle\Entity\User');
        $user->getEmail()
            ->shouldBeCalled()
            ->willReturn($email);

        $comment1 = $this->getCommentStub(true);
        $comment2 = $this->getCommentStub(false);
        $comment3 = $this->getCommentStub(true);

        $commentRepository = $this->prophesize('AppBundle\Repository\CommentRepository');
        $commentRepository->getCommentsByEmailAddress($email)
            ->shouldBeCalled()
            ->willReturn([$comment1, $comment2, $comment3]);

        $criteria = new DeletedCommentsCriteria($limitMatching, $commentRepository->reveal());

        $actualResult = $criteria->match($user->reveal());

        $this->assertFalse($actualResult);
    }

    public function testMatchingWithEnoughDeletedComments()
    {
        $email = 'foobar@example.com';
        $limitMatching = 2;

        $user = $this->prophesize('AppBundle\Entity\User');
        $user->getEmail()
            ->shouldBeCalled()
            ->willReturn($email);

        $comment1 = $this->getCommentStub(true);
        $comment2 = $this->getCommentStub(true);
        $comment3 = $this->getCommentStub(true);

        $commentRepository = $this->prophesize('AppBundle\Repository\CommentRepository');
        $commentRepository->getCommentsByEmailAddress($email)
            ->shouldBeCalled()
            ->willReturn([$comment1, $comment2, $comment3]);

        $criteria = new DeletedCommentsCriteria($limitMatching, $commentRepository->reveal());

        $actualResult = $criteria->match($user->reveal());

        $this->assertTrue($actualResult);
    }

    private function getCommentStub($isDeleted)
    {
        $comment = $this->prophesize('AppBundle\Entity\Comment');
        $comment->isDeleted()
            ->willReturn($isDeleted);

        return $comment->reveal();
    }
}
