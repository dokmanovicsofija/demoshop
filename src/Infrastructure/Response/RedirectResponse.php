<?php

namespace Infrastructure\Response;

class RedirectResponse extends AbstractHttpResponse
{
    /**
     * @var string The URL to redirect to.
     */
    protected string $url;

    /**
     * RedirectResponse constructor.
     *
     * @param string $url The URL to redirect to.
     * @param int $statusCode HTTP status code for the redirect (default is 302).
     */
    public function __construct(string $url, int $statusCode = 302)
    {
        $this->url = $url;

        parent::__construct($statusCode, []);
    }

    /**
     * Sends the redirect headers to the client.
     *
     * @return void
     */
    public function send(): void
    {
        $this->addHeader('Location', $this->url);
        $this->sendHeaders();
        exit;
    }

    /**
     * Adds a header to the response.
     *
     * @param string $name
     * @param string $value
     */
    public function addHeader(string $name, string $value): void
    {
        header("$name: $value");
    }

    /**
     * Sets the HTTP status code for the response.
     *
     * @param int $statusCode
     */
    public function setStatusCode(int $statusCode): void
    {
        $this->statusCode = $statusCode;
    }

    /**
     * Sets the body of the response (not used in redirects).
     *
     * @param string $body
     */
    public function setBody(string $body): void
    {
        // RedirectResponse does not need to set a body, so this can be left empty.
    }

    /**
     * Sends all headers to the client.
     *
     * @return void
     */
    protected function sendHeaders(): void
    {
        http_response_code($this->statusCode);
    }
}
