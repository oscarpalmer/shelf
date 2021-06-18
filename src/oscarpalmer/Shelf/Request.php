<?php

declare(strict_types=1);

namespace oscarpalmer\Shelf;

mb_internal_encoding('UTF-8');

use oscarpalmer\Shelf\Blob\IBlob;
use oscarpalmer\Shelf\Blob\Blob;
use oscarpalmer\Shelf\Blob\Cookies;
use oscarpalmer\Shelf\Blob\Session;
use oscarpalmer\Shelf\Files\Files;

/**
 * Request class
 */
class Request
{
    /**
     * @var string Delete request method
     */
    const METHOD_DELETE = 'DELETE';

    /**
     * @var string Get request method
     */
    const METHOD_GET = 'GET';

    /**
     * @var string Head request method
     */
    const METHOD_HEAD = 'HEAD';

    /**
     * @var string Options request method
     */
    const METHOD_OPTIONS = 'OPTIONS';

    /**
     * @var string Patch request method
     */
    const METHOD_PATCH = 'PATCH';

    /**
     * @var string Post request method
     */
    const METHOD_POST = 'POST';

    /**
     * @var string Put request method
     */
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
     * @var Files Files object
     */
    protected Files $files;

    /**
     * @var string Current path
     */
    protected string $path_info;

    /**
     * @var IBlob Query parameters
     */
    protected IBlob $query;

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
        $this->files = new Files($_FILES);
        $this->query = new Blob($_GET);
        $this->server = new Blob($server);
        $this->session = new Session($session);

        if ($this->server->has('SERVER_PROTOCOL') === false) {
            $this->server->set('SERVER_PROTOCOL', 'HTTP/2');
        }

        if ($this->server->has('REQUEST_METHOD') === false) {
            $this->server->set('REQUEST_METHOD', self::METHOD_GET);
        }

        $this->setPathInfo();
    }

    /** Public functions */

    /**
     * Retrieve Cookies Blob
     * 
     * @return IBlob Cookies Blob
     */
    public function getCookies(): IBlob
    {
        return $this->cookies;
    }

    /**
     * Retrieve Data Blob
     * 
     * @return IBlob Data Blob
     */
    public function getData(): IBlob
    {
        return $this->data;
    }

    /**
     * Retrieve Files object
     * 
     * @return array Files object
     */
    public function getFiles(): Files
    {
        return $this->files;
    }

    /**
     * Retrieve path information
     * 
     * @return IBlob Path information
     */
    public function getPathInfo(): string
    {
        return $this->path_info;
    }

    /**
     * Retrieve HTTP protocol
     * 
     * @return string HTTP protocol
     */
    public function getProtocol(): string
    {
        return $this->server->get('SERVER_PROTOCOL');
    }

    /**
     * Retrieve Query Blob
     * 
     * @return IBlob Query Blob
     */
    public function getQuery(): IBlob
    {
        return $this->query;
    }

    /**
     * Retrieve HTTP request method
     * 
     * @return string HTTP request method
     */
    public function getRequestMethod(): string
    {
        return $this->server->get('REQUEST_METHOD');
    }

    /**
     * Retrieve Server Blob
     * 
     * @return IBlob Server Blob
     */
    public function getServer(): IBlob
    {
        return $this->server;
    }

    /**
     * Retrieve Session Blob
     * 
     * @return IBlob Session Blob
     */
    public function getSession(): IBlob
    {
        return $this->session;
    }

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
        return $this->getRequestMethod() === self::METHOD_DELETE;
    }

    /**
     * Is it a GET request?
     *
     * @return bool True if request method is GET
     */
    public function isGet(): bool
    {
        return $this->getRequestMethod() === self::METHOD_GET;
    }

    /**
     * Is it a HEAD request?
     *
     * @return bool True if request method is HEAD
     */
    public function isHead(): bool
    {
        return $this->getRequestMethod() === self::METHOD_HEAD;
    }

    /**
     * Is it an OPTIONS request?
     *
     * @return bool True if request method is OPTIONS.
     */
    public function isOptions(): bool
    {
        return $this->getRequestMethod() === self::METHOD_OPTIONS;
    }

    /**
     * Is it a PATCH request?
     *
     * @return bool True if request method is PATCH
     */
    public function isPatch(): bool
    {
        return $this->getRequestMethod() === self::METHOD_PATCH;
    }

    /**
     * Is it a POST request?
     *
     * @return bool True if request method is POST
     */
    public function isPost(): bool
    {
        return $this->getRequestMethod() === self::METHOD_POST;
    }

    /**
     * Is it a PUT request?
     *
     * @return bool True if request method is PUT
     */
    public function isPut(): bool
    {
        return $this->getRequestMethod() === self::METHOD_PUT;
    }

    /** Protected functions */

    /**
     * Set path_info based on QUERY_STRING, SCRIPT_NAME, and REQUEST_URI
     */
    protected function setPathInfo(): void
    {
        $script = $this->server->get('SCRIPT_NAME', '');

        $path = preg_replace(
            [
                '/\A' . preg_quote($script, '/') . '/u',
                '/\A' . preg_quote(dirname($script), '/') . '/u',
                '/\?' . preg_quote($this->server->get('QUERY_STRING', ''), '/') . '\z/u'
            ],
            '',
            $this->server->get('REQUEST_URI', '/'),
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
