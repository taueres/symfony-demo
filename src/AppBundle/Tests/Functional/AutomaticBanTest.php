<?php

namespace AppBundle\Tests\Functional;

use AppBundle\Repository\CommentRepository;
use AppBundle\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AutomaticBanTest extends WebTestCase
{
    private $annaClient;
    private $mikeClient;
    private $container;
    private $entityManager;

    public function setUp()
    {
        self::bootKernel();
        $this->container = static::$kernel->getContainer();
        $this->entityManager = $this->container->get('doctrine')->getManager();

        $this->annaClient = $this->createClient(array(), array(
            'PHP_AUTH_USER' => 'anna_admin',
            'PHP_AUTH_PW' => 'kitten',
        ));

        $this->mikeClient = $this->createClient(array(), array(
            'PHP_AUTH_USER' => 'mike_admin',
            'PHP_AUTH_PW' => 'kitten',
        ));
    }

    public function testAfterThreeCommentDeletionUserWillBeBanned()
    {
        /** @var CommentRepository $commentRepository */
        $commentRepository = $this->container->get('comment_repository');
        $comments = $commentRepository->getCommentsByEmailAddress('john_user@symfony.com');

        /** @var UserRepository $userRepository */
        $userRepository = $this->container->get('user_repository');
        $john = $userRepository->getUserByEmailAddress('john_user@symfony.com');

        $this->annaClient->request('GET', '/en/admin/comment/' . $comments[0]->getId() . '/delete');
        $this->assertTrue($this->annaClient->getResponse()->isRedirect());
        $this->entityManager->refresh($john);
        $this->assertFalse($john->isBanned());

        $this->annaClient->request('GET', '/en/admin/comment/' . $comments[1]->getId() . '/delete');
        $this->assertTrue($this->annaClient->getResponse()->isRedirect());
        $this->entityManager->refresh($john);
        $this->assertFalse($john->isBanned());

        $this->mikeClient->request('GET', '/en/admin/comment/' . $comments[2]->getId() . '/delete');
        $this->assertTrue($this->mikeClient->getResponse()->isRedirect());
        $this->entityManager->refresh($john);
        $this->assertTrue($john->isBanned());
    }
}
