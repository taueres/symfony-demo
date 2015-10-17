<?php

namespace AppBundle\Criteria;

use AppBundle\Entity\User;

class AndCriteria implements Criteria
{
    private $collection;

    public function __construct(array $criteriaCollection)
    {
        foreach ($criteriaCollection as $criteria) {
            if ( ! $criteria instanceof Criteria) {
                throw new \InvalidArgumentException("Array of criterias expected");
            }
        }

        $this->collection = $criteriaCollection;
    }

    public function match(User $user)
    {
        foreach ($this->collection as $criteria) {
            if ( ! $criteria->match($user)) {
                return false;
            }
        }

        return true;
    }
}
