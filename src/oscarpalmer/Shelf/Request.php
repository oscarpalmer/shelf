<?php

declare(strict_types = 1);

namespace oscarpalmer\Shelf;

/**
 * Request class.
 */
class Request
{
    /**
     * @var array Array of acceptable request methods.
     */
    const REQUEST_METHODS = ['DELETE', 'GET', 'HEAD', 'POST', 'PUT'];

    /**
     * @var Blob Cookie parameters.
     */
    protected $cookies = null;

    /**
     * @var Blob Request parameters.
     */
    protected $data = null;

    /**
     * @var Blob Files parameters.
     */
    protected $files = null;

    /**
     * @var string Current path.
     */
    protected $path_info = null;

    /**
     * @var string Current protocol.
     */
    protected $protocol = null;

    /**
     * @var Blob Query parameters.
     */
    protected $query = null;

    /**
     * @var string Current request method.
     */
    protected $request_method = null;

    /**
     * @var Blob Server parameters.
     */
    protected $server = null;

    /**
     * @var Session Session class.
     */
    protected $session = null;

    /**
     * Creates a new Request object from two parameters,
     * one array of server information and one optional session variable.
     *
     * @param array       $server  Server parameters.
     * @param bool|string $session True to start session; string for named session.
     */
    public function __construct(array $server, $session = true)
    {
        $this->cookies = new Cookies();
        $this->data = new Blob($_POST);
        $this->files = new Blob($_FILES);
        $this->query = new Blob($_GET);
        $this->server = new Blob($server);
        $this->session = new Session($session);

        $this->protocol = $this->server->get('SERVER_PROTOCOL', 'HTTP/1.1');
        $this->request_method = $this->server->get('REQUEST_METHOD', 'GET');

        $this->setPathInfo();
    }

    /**
     * Get Request parameter.
     *
     * @param  string $key Key for parameter.
     * @return mixed  Value for parameter.
     */
    public function __get(string $key)
    {
        # Prioritise parameters created by Shelf.
        if (isset($this->$key)) {
            return $this->$key;
        }

        return $this->server->get(strtoupper($key));
    }

    /** Public functions. */

    /**
     * Is it an AJAX request?
     *
     * @return bool True if requested via AJAX.
     */
    public function isAjax() : bool
    {
        return $this->server->get('HTTP_X_REQUESTED_WITH') === 'XMLHttpRequest';
    }

    /**
     * Is it a DELETE request?
     *
     * @return bool True if request method is DELETE.
     */
    public function isDelete() : bool
    {
        return $this->request_method === static::REQUEST_METHODS[0];
    }

    /**
     * Is it a GET request?
     *
     * @return bool True if request method is GET.
     */
    public function isGet() : bool
    {
        return $this->request_method === static::REQUEST_METHODS[1];
    }

    /**
     * Is it a HEAD request?
     *
     * @return bool True if request method is HEAD.
     */
    public function isHead() : bool
    {
        return $this->request_method === static::REQUEST_METHODS[2];
    }

    /**
     * Is it a POST request?
     *
     * @return bool True if request method is POST.
     */
    public function isPost() : bool
    {
        return $this->request_method === static::REQUEST_METHODS[3];
    }

    /**
     * Is it a PUT request?
     *
     * @return bool True if request method is PUT.
     */
    public function isPut() : bool
    {
        return $this->request_method === static::REQUEST_METHODS[4];
    }

    /** Protected functions. */

    /**
     * Set path_info based on QUERY_STRING, SCRIPT_NAME, and REQUEST_URI.
     */
    protected function setPathInfo()
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

    /** Static functions. */

    /**
     * Creates a new Request object based on superglobals.
     *
     * @param  bool|string $session True to start session; string for named session.
     * @return Request     A new Request object.
     */
    public static function fromGlobals($session = true) : Request
    {
        return new static($_SERVER, $session);
    }
}
