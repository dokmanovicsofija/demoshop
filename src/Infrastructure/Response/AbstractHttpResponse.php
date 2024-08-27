<?php

namespace Infrastructure\Response;

/**
 * Class AbstractHttpResponse
 *
 * Represents an abstract response. Provides common methods to set status code, headers, and body.
 */
abstract class AbstractHttpResponse
{
    /**
     * HttpResponse constructor.
     * Initializes the response with a default status code and empty headers.
     *
     * @param int $statusCode HTTP status code (e.g., 200, 404).
     * @param array $headers Headers to be sent with the response.
     */
    public function __construct(
        protected int $statusCode = 200,
        protected array $headers = []
    ) {
    }

    /**
     * Sets the HTTP status code for the response.
     *
     * This method allows you to specify the HTTP status code that will be sent with the response.
     * Common status codes include 200 (OK), 404 (Not Found), 500 (Internal Server Error), etc.
     *
     * @param int $statusCode The HTTP status code to set.
     */
    public function setStatusCode(int $statusCode): void
    {
        $this->statusCode = $statusCode;
    }

    /**
     * Adds a header to the HTTP response.
     *
     * This method allows you to add a custom header to the response, such as 'Content-Type', 'Authorization', etc.
     * Headers are key-value pairs that provide additional information about the response.
     *
     * @param string $name The name of the header.
     * @param string $value The value of the header.
     */
    public function addHeader(string $name, string $value): void
    {
        $this->headers[$name] = $value;
    }

    /**
     * Sets the body content of the response.
     *
     * @param string $body The body content to set.
     */
    abstract public function setBody(string $body): void;

    /**
     * Sends the HTTP response to the client.
     */
    abstract public function send(): void;
}