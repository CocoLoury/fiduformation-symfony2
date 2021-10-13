<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        $nbUser = 200;

        for ($i = 0; $i < $nbUser; $i++) {
            $user = new User();
            $user
                ->setEmail($i.'user@test.com')
                ->setPassword(uniqid())
                ->setUpdated((new \DateTime())->modify("-$i months"))
            ;

            $manager->persist($user);
        }

        $manager->flush();
    }
}
