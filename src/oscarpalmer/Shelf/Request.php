<?php

namespace oscarpalmer\Shelf;

/**
 * Request class.
 */
class Request
{
    /**
     * @var Blob Cookie parameters.
     */
    protected $cookies;

    /**
     * @var Blob Request parameters.
     */
    protected $data;

    /**
     * @var Blob Files parameters.
     */
    protected $files;

    /**
     * @var string Current path.
     */
    protected $path_info;

    /**
     * @var string Current protocol.
     */
    protected $protocol;

    /**
     * @var Blob Query parameters.
     */
    protected $query;

    /**
     * @var string Current request method.
     */
    protected $request_method;

    /**
     * @var Blob Server parameters.
     */
    protected $server;

    /**
     * @var Session Session class.
     */
    protected $session;

    /**
     * Creates a new Request object from arrays of server, query, and request parameters.
     *
     * @param array       $server  Server parameters.
     * @param array       $query   Query parameters.
     * @param array       $data    Request parameters.
     * @param array       $cookies Cookie parameters.
     * @param array       $files   Files parameters.
     * @param bool|string $session True to start session; string for named session.
     */
    public function __construct(
        array $server = array(),
        array $query = array(),
        array $data = array(),
        array $cookies = array(),
        array $files = array(),
        $session = true
    ) {
        $this->cookies = new Blob($cookies);
        $this->data = new Blob($data);
        $this->files = new Blob($files);
        $this->query = new Blob($query);
        $this->server = new Blob($server);
        $this->session = new Session($session);

        $this->protocol = $this->server->get("SERVER_PROTOCOL", "HTTP/1.1");
        $this->request_method = $this->server->get("REQUEST_METHOD", "GET");

        $this->setPathInfo();
    }

    /**
     * Get Request parameter.
     *
     * @param  string $key Key for parameter.
     * @return mixed  Value for parameter.
     */
    public function __get($key)
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
    public function isAjax()
    {
        return $this->server->get("HTTP_X_REQUESTED_WITH") === "XMLHttpRequest";
    }

    /**
     * Is it a DELETE request?
     *
     * @return bool True if request method is DELETE.
     */
    public function isDelete()
    {
        return $this->request_method === "DELETE";
    }

    /**
     * Is it a GET request?
     *
     * @return bool True if request method is GET.
     */
    public function isGet()
    {
        return $this->request_method === "GET";
    }

    /**
     * Is it a HEAD request?
     *
     * @return bool True if request method is HEAD.
     */
    public function isHead()
    {
        return $this->request_method === "HEAD";
    }

    /**
     * Is it a POST request?
     *
     * @return bool True if request method is POST.
     */
    public function isPost()
    {
        return $this->request_method === "POST";
    }

    /**
     * Is it a PUT request?
     *
     * @return bool True if request method is PUT.
     */
    public function isPut()
    {
        return $this->request_method === "PUT";
    }

    /** Protected functions. */

    /**
     * Set path_info based on QUERY_STRING, SCRIPT_NAME, and REQUEST_URI.
     */
    protected function setPathInfo()
    {
        $query = $this->server->get("QUERY_STRING", "");
        $script = $this->server->get("SCRIPT_NAME", "");
        $uri = $this->server->get("REQUEST_URI", "/");

        $path = preg_replace(
            array(
                "/\A" . preg_quote($script, "/") . "/",
                "/\A" . preg_quote(dirname($script), "/") . "/",
                "/\?" . preg_quote($query, "/") . "\z/",
            ),
            "",
            $uri
        );

        $this->path_info = "/" . ltrim($path, "/");
    }

    /** Static functions. */

    /**
     * Creates a new Request object based on superglobals.
     *
     * @param  bool|string $session True to start session; string for named session.
     * @return Request     A new Request object.
     */
    public static function fromGlobals($session = true)
    {
        return new static($_SERVER, $_GET, $_POST, $_COOKIE, $_FILES, $session);
    }
}
