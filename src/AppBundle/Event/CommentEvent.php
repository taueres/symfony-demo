<?php

namespace AppBundle\Event;

use AppBundle\Entity\Comment;
use Symfony\Component\EventDispatcher\GenericEvent;

class CommentEvent extends GenericEvent
{
    const EVENT_COMMENT_DELETED = 'comment.deleted';

    public function __construct(Comment $comment)
    {
        parent::__construct($comment);
    }

    /**
     * @return Comment
     */
    public function getComment()
    {
        return $this->getSubject();
    }
}
