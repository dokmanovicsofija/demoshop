<?php

namespace Infrastructure\Response;

use AllowDynamicProperties;

/**
 * Class HtmlResponse
 *
 * Represents a Html response. It provides methods to set the status code, headers, and body of the response
 * and to send the response to the client.
 */
#[AllowDynamicProperties] class HtmlResponse extends AbstractHttpResponse
{
    protected string $body;

    public function __construct(int $statusCode = 200, array $headers = [], string $body = '')
    {
        parent::__construct($statusCode, $headers);
        $this->body = $body;
    }

    /**
     * Sets the body content of the response.
     *
     * @param string $body The body content to set.
     */
    public function setBody(string $body): void
    {
        $this->body = $body;
    }

    /**
     * Creates an HtmlResponse instance from a view file.
     *
     * This static method renders a view file using the provided data and returns
     * an HtmlResponse object with the rendered content. The view file is included
     * and its output is captured and used as the response body.
     *
     * @param string $viewFile The path to the view file to be included.
     * @param array $data An associative array of data to be made available in the view.
     * @param int $statusCode The HTTP status code for the response (default is 200).
     * @param array $headers An associative array of HTTP headers to include in the response (default is an empty array).
     * @return HtmlResponse An HtmlResponse instance with the rendered view content, status code, and headers.
     */
    public static function fromView(string $viewFile, array $data = [], int $statusCode = 200, array $headers = []): HtmlResponse
    {
        extract($data);
        ob_start();
        include $viewFile;
        $content = ob_get_clean();
        return new self($statusCode, $headers, $content);
    }

    /**
     * Sends the HTTP response to the client.
     *
     * This method sets the HTTP status code, adds the headers, and outputs the body content.
     */
    public function send(): void
    {
        http_response_code($this->statusCode);
        foreach ($this->headers as $header => $value) {
            header("$header: $value");
        }
        echo $this->body;
    }
}