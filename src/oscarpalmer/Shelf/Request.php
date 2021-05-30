<?php

declare(strict_types=1);

namespace oscarpalmer\Shelf;

/**
 * Request class
 */
class Request
{
    const METHOD_DELETE = 'DELETE';
    const METHOD_GET = 'GET';
    const METHOD_HEAD = 'HEAD';
    const METHOD_OPTIONS = 'OPTIONS';
    const METHOD_PATCH = 'PATCH';
    const METHOD_POST = 'POST';
    const METHOD_PUT = 'PUT';

    /**
     * @var IBlob Cookie parameters
     */
    protected IBlob $cookies;

    /**
     * @var IBlob Request parameters
     */
    protected IBlob $data;

    /**
     * @var IBlob Files parameters
     */
    protected IBlob $files;

    /**
     * @var string Current path
     */
    protected string $path_info;

    /**
     * @var string Current protocol
     */
    protected string $protocol;

    /**
     * @var IBlob Query parameters
     */
    protected IBlob $query;

    /**
     * @var string Current request method
     */
    protected string $request_method;

    /**
     * @var IBlob Server parameters
     */
    protected IBlob $server;

    /**
     * @var IBlob Session class
     */
    protected IBlob $session;

    /**
     * Creates a new Request object from two parameters:
     * one array of server information, and one optional session variable
     *
     * @param array $server Server parameters
     * @param bool|string $session True to start session, string for named session
     */
    public function __construct(array $server, bool|string $session = true)
    {
        $this->cookies = new Cookies();
        $this->data = new Blob($_POST);
        $this->files = new Blob($_FILES);
        $this->query = new Blob($_GET);
        $this->server = new Blob($server);
        $this->session = new Session($session);

        $this->protocol = $this->server->get('SERVER_PROTOCOL', 'HTTP/2');
        $this->request_method = $this->server->get('REQUEST_METHOD', self::METHOD_GET);

        $this->setPathInfo();
    }

    /**
     * Get value for Request parameter
     *
     * @param string $key Key for parameter
     * @return mixed Value for parameter
     */
    public function __get(string $key): mixed
    {
        # Prioritise parameters created by Shelf
        if (isset($this->$key)) {
            return $this->$key;
        }

        return $this->server->get(mb_strtoupper($key, 'UTF-8'));
    }

    /** Public functions */

    /**
     * Is it an AJAX request?
     *
     * @return bool True if requested via AJAX
     */
    public function isAjax(): bool
    {
        return $this->server->get('HTTP_X_REQUESTED_WITH') === 'XMLHttpRequest';
    }

    /**
     * Is it a DELETE request?
     *
     * @return bool True if request method is DELETE
     */
    public function isDelete(): bool
    {
        return $this->request_method === self::METHOD_DELETE;
    }

    /**
     * Is it a GET request?
     *
     * @return bool True if request method is GET
     */
    public function isGet(): bool
    {
        return $this->request_method === self::METHOD_GET;
    }

    /**
     * Is it a HEAD request?
     *
     * @return bool True if request method is HEAD
     */
    public function isHead(): bool
    {
        return $this->request_method === self::METHOD_HEAD;
    }

    /**
     * Is it an OPTIONS request?
     *
     * @return bool True if request method is OPTIONS.
     */
    public function isOptions(): bool
    {
        return $this->request_method === self::METHOD_OPTIONS;
    }

    /**
     * Is it a PATCH request?
     *
     * @return bool True if request method is PATCH
     */
    public function isPatch(): bool
    {
        return $this->request_method === self::METHOD_PATCH;
    }

    /**
     * Is it a POST request?
     *
     * @return bool True if request method is POST
     */
    public function isPost(): bool
    {
        return $this->request_method === self::METHOD_POST;
    }

    /**
     * Is it a PUT request?
     *
     * @return bool True if request method is PUT
     */
    public function isPut(): bool
    {
        return $this->request_method === self::METHOD_PUT;
    }

    /** Protected functions */

    /**
     * Set path_info based on QUERY_STRING, SCRIPT_NAME, and REQUEST_URI
     */
    protected function setPathInfo(): void
    {
        $query = $this->server->get('QUERY_STRING', '');
        $script = $this->server->get('SCRIPT_NAME', '');
        $uri = $this->server->get('REQUEST_URI', '/');

        $path = preg_replace(
            [
                '/\A' . preg_quote($script, '/') . '/',
                '/\A' . preg_quote(dirname($script), '/') . '/',
                '/\?' . preg_quote($query, '/') . '\z/'
            ],
            '',
            $uri
        );

        $this->path_info = '/' . ltrim($path, '/');
    }

    /** Static functions */

    /**
     * Creates a new Request object based on superglobals
     *
     * @param bool|string $session True to start session, string for named session
     * @return Request A new Request object
     */
    public static function fromGlobals(bool|string $session = true): Request
    {
        return new static($_SERVER, $session);
    }
}
