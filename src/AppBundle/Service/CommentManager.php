<?php

namespace AppBundle\Service;

use AppBundle\Entity\Comment;
use AppBundle\Entity\User;
use AppBundle\Event\CommentEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class CommentManager
{
    /** @var EventDispatcherInterface */
    private $eventDispatcher;

    /**
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(EventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @param Comment $comment
     * @param User $who
     * @param \DateTime|null $when
     * @return Comment
     */
    public function delete(Comment $comment, User $who, \DateTime $when = null)
    {
        if ($comment->isDeleted()) throw new \LogicException('Comment already deleted');

        if (null === $when) $when = new \DateTime('now');

        $comment->setDeletedBy($who);
        $comment->setDeletedAt($when);

        $event = new CommentEvent($comment);
        $this->eventDispatcher->dispatch(CommentEvent::EVENT_COMMENT_DELETED, $event);

        return $comment;
    }
}
