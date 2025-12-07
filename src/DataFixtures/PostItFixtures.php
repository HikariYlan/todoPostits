<?php

namespace App\DataFixtures;

use App\Factory\PostItFactory;
use App\Factory\UserFactory;
use App\Story\UserStory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class PostItFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        PostItFactory::createMany(50, function () {
            if (1 == random_int(0, 1)) {
                $dueDate = new \DateTime('+20 days');
            }

            return [
                'owner' => UserStory::getRandom('random_users'),
                'dueDate' => $dueDate ?? null,
            ];
        });

        PostItFactory::createMany(10, function () {
            if (1 == random_int(0, 1)) {
                $dueDate = new \DateTime('+20 days');
            }

            return [
                'owner' => UserFactory::findBy(['username' => 'ylan'])[0],
                'dueDate' => $dueDate ?? null,
            ];
        });
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
        ];
    }
}
