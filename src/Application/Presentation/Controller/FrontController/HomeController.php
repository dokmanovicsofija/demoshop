<?php

namespace Application\Presentation\Controller\FrontController;

use Application\Integration\Utility\PathHelper;
use Infrastructure\Response\HtmlResponse;

/**
 * Class HomeController
 *
 * Handles the homepage display of the application.
 * This controller manages the response when users access the home page.
 */
class HomeController
{
    /**
     * HomeController constructor.
     *
     * Initializes the HomeController. Currently, no specific initialization is performed.
     */
    public function __construct()
    {
    }

    /**
     * Renders the homepage view.
     *
     * This method is responsible for generating the HTML response for the home page.
     * It uses the HtmlResponse class to render the `home.php` view located at the path
     * specified by the PathHelper utility.
     *
     * @return HtmlResponse The HTML response for the homepage.
     */
    public function index(): HtmlResponse
    {
        return HtmlResponse::fromView(PathHelper::view('home.php'));
    }
}