<?php

namespace App\Security\Voter;

use App\Entity\PostIt;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

final class PostItVoter extends Voter
{
    public const EDIT = 'edit';
    public const VIEW = 'view';
    public const DELETE = 'delete';
    public const OWNER = 'owner';

    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [self::EDIT, self::VIEW, self::DELETE, self::OWNER])
            && $subject instanceof PostIt;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        if (!$this->supports($attribute, $subject)) {
            return false;
        }

        /** @var User $user */
        $user = $token->getUser();

        return match ($attribute) {
            self::EDIT, self::VIEW, self::DELETE, self::OWNER => $this->isOwner($subject, $user),
        };
    }

    protected function isOwner(PostIt $postIt, User $user): bool
    {
        if (!$user === $postIt->getOwner() || !in_array('ROLE_ADMIN', $user->getRoles())) {
            return false;
        }

        return true;
    }
}
