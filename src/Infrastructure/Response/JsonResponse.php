<?php

namespace Infrastructure\Response;

/**
 * Class JsonResponse
 *
 * Represents a concrete implementation of a response that uses JSON format.
 */
class JsonResponse extends AbstractHttpResponse
{
    /**
     * JsonResponse constructor.
     *
     * @param array $data The data to be sent as JSON response.
     * @param int $statusCode The HTTP status code (default is 200).
     * @param array $headers An associative array of headers (optional).
     */
    public function __construct(private array $data = [], protected int $statusCode = 200, protected array $headers = [])
    {
        parent::__construct($statusCode);
        $this->headers['Content-Type'] = 'application/json';
    }

    /**
     * Set the body of the response.
     *
     * @param string $body The body content (which would override the JSON data in this case).
     */
    public function setBody(string $body): void
    {
        $this->data = json_decode($body, true);
    }

    /**
     * Send the JSON response to the client.
     */
    public function send(): void
    {
        http_response_code($this->statusCode);

        foreach ($this->headers as $header => $value) {
            header("$header: $value");
        }

        echo json_encode($this->data);
    }
}