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
//        $this->method = GlobalWrapper::getInstanceForType('server')->get('REQUEST_METHOD');
//        $this->uri = strtok(GlobalWrapper::getInstanceForType('server')->get('REQUEST_URI'), '?');
//        $this->queryParams = GlobalWrapper::getInstanceForType('get')->getAll() ?? [];
//        $this->postData = GlobalWrapper::getInstanceForType('post')->getAll() ?? [];
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
     * Gets the query parameters from the request URL.
     *
     * @return array An associative array of query parameters.
     */
    public function getQueryParams(): array
    {
        return $this->queryParams;
    }

    /**
     * Gets the POST data from the request body.
     *
     * @return array An associative array of POST data.
     */
    public function getPostData(): array
    {
        return $this->postData;
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
}