<?php

namespace App\Security\Voter;

use App\Entity\FormDefinition;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

final class FormDefinitionOwnerVoter extends Voter
{
    public const ROLE_OWNER = 'ROLE_OWNER';

    protected function supports(string $attribute, mixed $subject): bool
    {
        return $attribute === self::ROLE_OWNER && $subject instanceof FormDefinition;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            return false;
        }

        /** @var FormDefinition $subject */
        return $subject->getOwner() === $user;
    }
}

