<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Comment;
use Doctrine\ORM\EntityRepository;

class CommentRepository extends EntityRepository
{
    /**
     * @param string $email
     * @return array|Comment[]
     */
    public function getCommentsByEmailAddress($email)
    {
        return $this->findBy(['authorEmail' => $email]);
    }
}
