<?php

namespace AppBundle\Test\Service;

use AppBundle\Service\UserBanOperator;

class UserBanOperatorTest extends \PHPUnit_Framework_TestCase
{
    public function testWillNotBanAdminUser()
    {
        $user = $this->prophesize('AppBundle\Entity\User');
        $user->isAdmin()
            ->shouldBeCalled()
            ->willReturn(true);

        $criteria = $this->prophesize('AppBundle\Criteria\Criteria');
        $criteria->match($user)
            ->shouldNotBeCalled();

        $userManager = $this->prophesize('AppBundle\Service\UserManager');
        $userManager->ban($user)
            ->shouldNotBeCalled();

        $operator = new UserBanOperator($userManager->reveal(), $criteria->reveal());

        $actualResult = $operator->handleAutomaticBanForUser($user->reveal());

        $this->assertFalse($actualResult);
    }

    public function testWillNotBanUserNotMatching()
    {
        $user = $this->prophesize('AppBundle\Entity\User');
        $user->isAdmin()
            ->shouldBeCalled()
            ->willReturn(false);

        $criteria = $this->prophesize('AppBundle\Criteria\Criteria');
        $criteria->match($user)
            ->shouldBeCalled()
            ->willReturn(false);

        $userManager = $this->prophesize('AppBundle\Service\UserManager');
        $userManager->ban($user)
            ->shouldNotBeCalled();

        $operator = new UserBanOperator($userManager->reveal(), $criteria->reveal());

        $actualResult = $operator->handleAutomaticBanForUser($user->reveal());

        $this->assertFalse($actualResult);
    }

    public function testWillBanUserMatching()
    {
        $user = $this->prophesize('AppBundle\Entity\User');
        $user->isAdmin()
            ->shouldBeCalled()
            ->willReturn(false);

        $criteria = $this->prophesize('AppBundle\Criteria\Criteria');
        $criteria->match($user)
            ->shouldBeCalled()
            ->willReturn(true);

        $userManager = $this->prophesize('AppBundle\Service\UserManager');
        $userManager->ban($user)
            ->shouldBeCalled();

        $operator = new UserBanOperator($userManager->reveal(), $criteria->reveal());

        $actualResult = $operator->handleAutomaticBanForUser($user->reveal());

        $this->assertTrue($actualResult);
    }
}
