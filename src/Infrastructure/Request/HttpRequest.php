<?php

namespace Infrastructure\Request;

use Exception;
use Infrastructure\Utility\GlobalWrapper;

/**
 * Class HttpRequest
 *
 * Represents an HTTP request. It provides methods to retrieve request information such as HTTP method,
 * URI, query parameters, and POST data.
 */
class HttpRequest
{
    /**
     * @var string The HTTP method used for the request (e.g., GET, POST).
     */
    private string $method;

    /**
     * @var string The URI of the request without the query string.
     */
    private string $uri;

    /**
     * @var array An associative array of query parameters from the request URL.
     */
    private array $queryParams;

    /**
     * @var array An associative array of POST data from the request body.
     */
    private array $postData;

    /**
     * HttpRequest constructor.
     *
     * Initializes the HttpRequest object by retrieving and setting the HTTP request method,
     * * URI, query parameters, and POST data. These values are fetched from the global `$_SERVER`,
     * * `$_GET`, and `$_POST` super globals using the `GlobalWrapper` class.
     * @throws Exception
     */
    public function __construct()
    {
        $this->method = GlobalWrapper::getGlobal('_SERVER')['REQUEST_METHOD'];
        $this->uri = strtok(GlobalWrapper::getGlobal('_SERVER')['REQUEST_URI'], '?');
        $this->queryParams = GlobalWrapper::getGlobal('_GET');
        $this->postData = GlobalWrapper::getGlobal('_POST');
    }

    /**
     * Gets the HTTP method used for the request.
     *
     * @return string The HTTP method (e.g., GET, POST).
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    public function getBody(): string
    {
        return file_get_contents('php://input');
    }

    /**
     * Gets the URI of the request without the query string.
     *
     * @return string The request URI.
     */
    public function getUri(): string
    {
        return $this->uri;
    }

    /**
     * Retrieve a query parameter from the URL by its key.
     *
     * This method returns the value of the specified query parameter if it exists,
     * or a default value if the parameter is not found.
     *
     * @param string $key The name of the query parameter to retrieve.
     * @param string|null $default The default value to return if the parameter is not found. Defaults to null.
     * @return string|null The value of the query parameter if it exists, or the default value.
     */
    public function getQueryParams(string $key, ?string $default = null): ?string
    {
        $value = $this->queryParams[$key] ?? $default;

        if ($value === '') {
            return $default;
        }

        return $value;
    }

    /**
     * Get a body parameter from the POST request.
     *
     * @param string $key The key of the body parameter.
     * @param string|null $default The default value to return if the key is not found.
     * @return string|null The value of the body parameter or the default value.
     */
    public function getBodyParam(string $key, ?string $default = null): ?string
    {
        return $this->bodyParams[$key] ?? $default;
    }

    /**
     * Gets a query parameter by key.
     *
     * @param string $key The key of the query parameter.
     * @param mixed|null $default The default value to return if the key is not found.
     * @return mixed The value of the query parameter or the default value if not found.
     */
    public function get(string $key, mixed $default = null): mixed
    {
        return $this->queryParams[$key] ?? $default;
    }

    /**
     * Gets a POST parameter by key.
     *
     * @param string $key The key of the POST parameter.
     * @param mixed|null $default The default value to return if the key is not found.
     * @return mixed The value of the POST parameter or the default value if not found.
     */
    public function post(string $key, mixed $default = null): mixed
    {
        return $this->postData[$key] ?? $default;
    }

    /**
     * Gets and decodes the JSON body of the request.
     *
     * @return array The parsed JSON body as an associative array.
     */
    public function getParsedBody(): array
    {
        $body = file_get_contents('php://input');
        $data = json_decode($body, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return [];
        }

        return $data ?? [];
    }

    /**
     * Gets the uploaded files from the request.
     *
     * @return array An associative array of uploaded files.
     */
    public function getUploadedFiles(): array
    {
        return $_FILES;
    }

    /**
     * Retrieves the POST data from the HTTP request.
     *
     * This method returns the contents of the $_POST super global, which contains
     * the data sent in the body of an HTTP POST request. This is typically used
     * to access form data or other payloads submitted by the client in a POST request.
     *
     * @return array An associative array of POST data, where the keys are the names of the form fields
     *               and the values are the data submitted by the client.
     */
    public function bodyParams(): array
    {
        return $_POST;
    }
}