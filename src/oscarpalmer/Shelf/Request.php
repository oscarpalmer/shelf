<?php

namespace oscarpalmer\Shelf;

/**
 * Request class.
 */
class Request
{
    /**
     * @var Blob Request parameters.
     */
    protected $data;

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
     * Creates a new Request object from arrays of server, query, and request parameters.
     *
     * @param array $server Server parameters.
     * @param array $query  Query parameters.
     * @param array $data   Request parameters.
     */
    public function __construct(
        array $server = array(),
        array $query = array(),
        array $data = array()
    ) {
        $this->data = new Blob($data);
        $this->query = new Blob($query);
        $this->server = new Blob($server);

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

        $this->path_info = $path;
    }

    /** Static functions. */

    /**
     * Creates a new Request object based on superglobals.
     *
     * @return Request A new Request object.
     */
    public static function fromGlobals()
    {
        return new static($_SERVER, $_GET, $_POST);
    }
}