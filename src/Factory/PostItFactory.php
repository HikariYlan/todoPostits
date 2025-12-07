<?php

namespace App\Factory;

use App\Entity\PostIt;
use App\Enum\Status;
use Zenstruck\Foundry\LazyValue;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<PostIt>
 */
final class PostItFactory extends PersistentProxyObjectFactory
{
    public function __construct()
    {
    }

    public static function class(): string
    {
        return PostIt::class;
    }

    protected function defaults(): array|callable
    {
        return [
            'owner' => LazyValue::new(fn () => UserFactory::createOne()),
            'status' => self::faker()->randomElement(Status::cases()),
            'title' => self::faker()->text(150),
        ];
    }

    protected function initialize(): static
    {
        return $this->afterInstantiate(function (PostIt $postIt): void {
            if (Status::FINISHED == $postIt->getStatus()) {
                $postIt->setFinishDate(new \DateTime('+2 days'));
            }
        })
        ;
    }
}
