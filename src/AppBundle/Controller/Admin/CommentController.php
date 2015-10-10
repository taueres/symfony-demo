<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Comment;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CommentController
 * @Route("/admin/comment")
 */
class CommentController extends Controller
{
    /**
     * @Route("/{comment}/delete", name="admin_comment_delete")
     * @Method("GET")
     *
     * @param Request $request
     * @param Comment $comment
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteCommentAction(Request $request, Comment $comment)
    {
        $commentManager = $this->get('comment_manager');
        $entityManager = $this->get('doctrine.orm.entity_manager');

        try {
            $commentManager->delete($comment, $this->getUser());
            $entityManager->flush();
            $this->addFlash('notice', 'Comment deleted');
        } catch (\Exception $ex) {
            $this->addFlash('error', $ex->getMessage());
        }

        if ($referer = $request->headers->get('referer')) {
            return $this->redirect($referer);
        }

        return $this->redirectToRoute('admin_post_index');
    }
}
