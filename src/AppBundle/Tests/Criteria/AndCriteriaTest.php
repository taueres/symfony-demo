<?php

namespace AppBundle\Tests\Criteria;

use AppBundle\Criteria\AndCriteria;
use AppBundle\Entity\User;

class AndCriteriaTest extends \PHPUnit_Framework_TestCase
{
    public function testWillReturnFalseWithOnlyFalseCriteria()
    {
        $user = new User();

        $criteria = $this->prophesize('AppBundle\Criteria\Criteria');
        $criteria->match($user)
            ->willReturn(false);

        $criteriaArray = [$criteria->reveal()];

        $andCriteria = new AndCriteria($criteriaArray);

        $this->assertFalse($andCriteria->match($user));
    }

    public function testWillReturnFalseWithOneFalseCriteria()
    {
        $user = new User();

        $criteriaTrue = $this->prophesize('AppBundle\Criteria\Criteria');
        $criteriaTrue->match($user)
            ->willReturn(true);

        $criteriaFalse = $this->prophesize('AppBundle\Criteria\Criteria');
        $criteriaFalse->match($user)
            ->willReturn(false);

        $criteriaArray = [$criteriaTrue->reveal(), $criteriaFalse->reveal()];

        $andCriteria = new AndCriteria($criteriaArray);

        $this->assertFalse($andCriteria->match($user));
    }

    public function testWillReturnTrueWithAllTrueCriteria()
    {
        $user = new User();

        $criteria = $this->prophesize('AppBundle\Criteria\Criteria');
        $criteria->match($user)
            ->willReturn(true);

        $criteriaArray = [$criteria->reveal()];

        $andCriteria = new AndCriteria($criteriaArray);

        $this->assertTrue($andCriteria->match($user));
    }

    public function testInvalidArray()
    {
        $array = [1, 2, 3];

        $this->setExpectedException('InvalidArgumentException');

        $criteria = new AndCriteria($array);
    }
}
