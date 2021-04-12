<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\User;
use Symfony\Component\Security\Core\Security;

class UserService
{
    private Security $security;

    /**
     * UserService constructor.
     */
    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    /**
     * @return array
     */
    public function getProfile(): array
    {
        /** @var User $user */
        $user = $this->security->getUser();

        return [
            'id' => $user->getId(),
            'login' => $user->getLogin(),
            'phone' => $user->getPhone(),
        ];
    }
}
