<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadUserData
{
    public static function load(ObjectManager $manager, ContainerInterface $container)
    {
        $passwordEncoder = $container->get('security.password_encoder');

        $johnUser = new User();
        $johnUser->setUsername('john_user');
        $johnUser->setEmail('john_user@symfony.com');
        $encodedPassword = $passwordEncoder->encodePassword($johnUser, 'kitten');
        $johnUser->setPassword($encodedPassword);
        $manager->persist($johnUser);

        $annaAdmin = new User();
        $annaAdmin->setUsername('anna_admin');
        $annaAdmin->setEmail('anna_admin@symfony.com');
        $annaAdmin->setRoles(array('ROLE_ADMIN'));
        $encodedPassword = $passwordEncoder->encodePassword($annaAdmin, 'kitten');
        $annaAdmin->setPassword($encodedPassword);
        $manager->persist($annaAdmin);

        $mikeAdmin = new User();
        $mikeAdmin->setUsername('mike_admin');
        $mikeAdmin->setEmail('mike_admin@symfony.com');
        $mikeAdmin->setRoles(array('ROLE_ADMIN'));
        $encodedPassword = $passwordEncoder->encodePassword($mikeAdmin, 'kitten');
        $mikeAdmin->setPassword($encodedPassword);
        $manager->persist($mikeAdmin);

        $manager->flush();
    }
}
