<?php

namespace Application\Integration\Exceptions;

use Infrastructure\Exceptions\HttpNotFoundException;
use Infrastructure\Response\HtmlResponse;
use Application\Integration\Utility\PathHelper;
use Infrastructure\Response\JsonResponse;
use Infrastructure\Response\RedirectResponse;
use JetBrains\PhpStorm\NoReturn;
use Throwable;

/**
 * Class ExceptionHandler
 *
 * Global exception handler for the application. This class handles all exceptions thrown
 * during the execution of the application and generates an appropriate HTTP response
 * based on the type of exception.
 */
class ExceptionHandler
{
    /**
     * Initializes the global exception handler.
     *
     * This method sets the global exception handler to the handle method of this class.
     *
     * @return void
     */
    public static function init(): void
    {
        set_exception_handler([self::class, 'handle']);
    }

    /**
     * Handle all exceptions thrown in the application.
     *
     * This method acts as the global exception handler for the application. It takes an exception
     * as input, determines its type, and returns an appropriate HTTP response. Specifically,
     * it handles `HttpNotFoundException` separately to return a 404 error page. For all other
     * exceptions, it returns a generic 500 error page.
     *
     * @param Throwable $exception The exception that was thrown during the application's execution.
     * @return void
     */
    #[NoReturn] public static function handle(Throwable $exception): void
    {
        if ($exception instanceof HttpNotFoundException) {
            $response = HtmlResponse::fromView(
                PathHelper::view('errors/404.php'),
                [],
                404
            );
            $response->send();
            return;
        }

        if ($exception instanceof RedirectToLoginException) {
            $response = HtmlResponse::fromView(
                PathHelper::view('login.php'),
                [],
                401
            );
            $response->send();
            return;
        }

        if ($exception instanceof AuthenticationException) {
            $response = HtmlResponse::fromView(
                PathHelper::view('login.php'),
                ['errorMessage' => $exception->getMessage()],
                401
            );
            $response->send();
            return;
        }

        if ($exception instanceof AuthorizationException) {
            $response = HtmlResponse::fromView(
                PathHelper::view('errors/403.php'),
                [],
                403
            );
            $response->send();
            return;
        }

        if ($exception instanceof \InvalidArgumentException) {
            $response = new JsonResponse(
                ['error-message' => $exception->getMessage()],
                400
            );
            $response->send();
            return;
        }

        if ($exception instanceof \RuntimeException) {
            $response = HtmlResponse::fromView(
                PathHelper::view('errors/500.php'),
                ['errorMessage' => $exception->getMessage()],
                500
            );
            $response->send();
            return;
        }

        // Default case for all other exceptions
        $response = HtmlResponse::fromView(
            PathHelper::view('errors/500.php'),
            [],
            500
        );
        $response->send();
    }
}
