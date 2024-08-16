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
        protected int   $statusCode = 200,
        protected array $headers = []
    )
    {
    }

    /**
     * Sets the HTTP status code for the response.
     *
     * @param int $statusCode The HTTP status code to set.
     */
    abstract public function setStatusCode(int $statusCode): void;

    /**
     * Adds a header to the response.
     *
     * @param string $name The name of the header.
     * @param string $value The value of the header.
     */
    abstract public function addHeader(string $name, string $value): void;

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