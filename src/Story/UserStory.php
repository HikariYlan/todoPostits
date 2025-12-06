<?php

namespace App\Story;

use App\Factory\UserFactory;
use Zenstruck\Foundry\Story;

final class UserStory extends Story
{
    public function build(): void
    {
        $this->addState('important_users', [
            UserFactory::createOne([
                'username' => 'ylan',
                'password' => 'admin',
                'roles' => ['ROLE_ADMIN'],
            ]),
            UserFactory::createOne([
                'username' => 'hikari',
                'password' => 'user',
                'roles' => ['ROLE_USER']
            ])
        ]);

        $this->addToPool('random_users', UserFactory::createMany(10));
    }
}
