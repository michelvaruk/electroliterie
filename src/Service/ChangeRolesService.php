<?php

namespace App\Service;

use App\Repository\UserRepository;

class ChangeRolesService
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function isLastAdmin(array $roles): bool
    {

        if (in_array('ROLE_ADMIN', $roles)) {
            if (count($this->userRepository->findByRole('ROLE_ADMIN')) == 1) {
                return true;
            }
        }
        return false;
    }
}
