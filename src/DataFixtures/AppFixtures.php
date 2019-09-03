<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    const USERS = [
        [
            'email' => 'john@user',
            'password' => '0000'
        ]
    ];

    public function load(ObjectManager $manager)
    {
        foreach (self::USERS as $user) {
            $userEntity = new User();
            $userEntity->setEmail($user['email']);
            $userEntity->setPassword($user['password']);
            $manager->persist($userEntity);
        }

        $manager->flush();
    }
}
