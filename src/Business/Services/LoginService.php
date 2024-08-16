<?php

namespace Business\Services;

use Business\Interfaces\RepositoryInterface\LoginRepositoryInterface;
use Business\Interfaces\ServiceInterface\LoginServiceInterface;
use Infrastructure\SessionManager;

class LoginService implements LoginServiceInterface
{
    public function __construct(private LoginRepositoryInterface $adminRepository)
    {
    }

    public function authenticate(string $username, string $password, bool $keepLoggedIn): array
    {
        $admin = $this->adminRepository->findByUsername($username);

        if ($admin && password_verify($password, $admin->getPassword())) {
            $sessionManager = SessionManager::getInstance();
            $sessionManager->set('admin', $admin->getId());

            if ($keepLoggedIn) {
                $sessionManager->setCookie('admin', $admin->getId(), 86400 * 30);
            }

            return ['success' => true, 'message' => ''];
        } else {
            return ['success' => false, 'message' => 'Invalid username or password'];
        }
    }
}