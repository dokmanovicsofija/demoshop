<?php

namespace Presentation\Controller\FrontController;

use Business\Interfaces\ServiceInterface\LoginServiceInterface;
use Infrastructure\Request\HttpRequest;
use Infrastructure\Response\HtmlResponse;

class LoginController
{
    public function __construct(private LoginServiceInterface $loginService)
    {
    }

    public function showLoginForm(): HtmlResponse
    {
        return HtmlResponse::fromView(__DIR__ . '/../../Views/login.php');
    }

    public function processLogin(HttpRequest $request): HtmlResponse
    {
        $username = $request->post('username');
        $password = $request->post('password');
        $keepLoggedIn = $request->post('keepLoggedIn') === 'on';

        $result = $this->loginService->authenticate($username, $password, $keepLoggedIn);

        if ($result['success']) {
            return HtmlResponse::fromView(__DIR__ . '/../../Views/dashboard.php', []);
        } else {
            return HtmlResponse::fromView(__DIR__ . '/../../Views/login.php', [
                'errorMessage' => $result['message']]);
        }
    }
}

