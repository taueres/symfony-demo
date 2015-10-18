<?php

namespace AppBundle\Criteria;

use AppBundle\Entity\User;

class DeletedCommentsCriteria implements Criteria
{
    private $limitDeletion;
    private $commentRepository;

    public function __construct($limitDeletion, $commentRepository)
    {
        $this->limitDeletion = $limitDeletion;
        $this->commentRepository = $commentRepository;
    }

    public function match(User $user)
    {
        $email = $user->getEmail();
        $comments = $this->commentRepository->getCommentsByEmailAddress($email);

        $deletionCounter = 0;

        foreach ($comments as $comment) {
            if ($comment->isDeleted()) {
                $deletionCounter++;
            }
        }

        return $deletionCounter >= $this->limitDeletion;
    }
}
