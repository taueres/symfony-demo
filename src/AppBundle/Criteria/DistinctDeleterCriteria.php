<?php

namespace AppBundle\Criteria;

use AppBundle\Entity\User;
use AppBundle\Repository\CommentRepository;

class DistinctDeleterCriteria implements Criteria
{
    private $limit;
    private $commentRepository;

    /**
     * DistinctDeleterCriteria constructor.
     * @param int $limit
     * @param CommentRepository $commentRepository
     */
    public function __construct($limit, CommentRepository $commentRepository)
    {
        $this->limit = $limit;
        $this->commentRepository = $commentRepository;
    }

    /**
     * @param User $user
     * @return bool
     */
    function match(User $user)
    {
        $email = $user->getEmail();
        $comments = $this->commentRepository->getCommentsByEmailAddress($email);

        $deleters = array();
        foreach ($comments as $comment) {
            if (
                $comment->isDeleted()
                && ($currentDeleter = $comment->getDeletedBy())
            ) {
                $deleters[$currentDeleter->getUsername()] = true;
            }
        }

        return count($deleters) >= $this->limit;
    }
}
