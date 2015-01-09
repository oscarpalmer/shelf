<?php

namespace oscarpalmer\Shelf;

/**
 * Response class.
 */
class Response
{
    /**
     * @var string Response body.
     */
    protected $body;

    /**
     * @var Blob Response headers.
     */
    protected $headers;

    /**
     * @var Request The Request object used by the response.
     */
    protected $request;

    /**
     * @var int Response status code.
     */
    protected $status;

    /**
     * @var bool Has the response finished?
     */
    protected $finished = false;

    /**
     * @var array Status codes for no-body responses.
     */
    protected $no_body = array(100, 101, 204, 205, 301, 302, 303, 304, 307);

    /**
     * @var array Response status messages.
     */
    protected $statuses = array(
        # Informational
        100 => "100 Continue",
        101 => "101 Switching Protocols",
        # Success!
        200 => "200 OK",
        201 => "201 Created",
        202 => "202 Accepted",
        203 => "203 Non-Authoritative Information",
        204 => "204 No Content",
        205 => "205 Reset Content",
        206 => "206 Partial Content",
        # Redirection.
        300 => "300 Multiple Choices",
        301 => "301 Moved Permanently",
        302 => "302 Found",
        303 => "303 See Other",
        304 => "304 Not Modified",
        305 => "305 Use Proxy",
        306 => "306 Unused",
        307 => "307 Temporary Redirect",
        # Client errors.
        400 => "400 Bad Request",
        401 => "401 Unauthorized",
        402 => "402 Payment Required",
        403 => "403 Forbidden",
        404 => "404 Not Found",
        405 => "405 Method Not Allowed",
        406 => "406 Not Acceptable",
        407 => "407 Proxy Authentication Required",
        408 => "408 Request Timeout",
        409 => "409 Conflict",
        410 => "410 Gone",
        411 => "411 Length Required",
        412 => "412 Precondition Required",
        413 => "413 Request Entry Too Large",
        414 => "414 Request-URI Too Long",
        415 => "415 Unsupported Media Type",
        416 => "416 Requested Range Not Satisfiable",
        417 => "417 Expectation Failed",
        # Server errors.
        500 => "500 Internal Server Error",
        501 => "501 Not Implemented",
        502 => "502 Bad Gateway",
        503 => "503 Service Unavailable",
        504 => "504 Gateway Timeout",
        505 => "505 HTTP Version Not Supported"
    );

    /**
     * Creates a new Response object;
     * defaults to a clean and successful HTML response.
     *
     * @param null|scalar $body    Response body.
     * @param int         $status  Response status code.
     * @param array       $headers Response headers.
     */
    public function __construct(
        $body = "",
        $status = 200,
        array $headers = array("content-type" => "text/html; charset=utf-8")
    ) {
        $this->setStatus($status);
        $this->setBody($body);

        $this->headers = new Blob($headers);
    }

    /** Public functions. */

    /**
     * Finish the response and return the object.
     *
     * @param  Request  Request object used create a nice response.
     * @return Response The Response object.
     */
    public function finish(Request $request)
    {
        if ($this->finished) {
            throw new \LogicException("The response has already finished.");
        }

        $this->request = $request;

        $this->headers->set("content-length", strlen($this->body));
        $this->setEmptyResponse();

        $this->writeHeaders();
        $this->writeBody();

        $this->finished = true;

        return $this;
    }

    /**
     * Get the current response body.
     *
     * @return string Response body.
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * Get a specific response header.
     *
     * @param  string $key Key for header.
     * @return mixed  Found header or null.
     */
    public function getHeader($key)
    {
        return $this->headers->get($key);
    }

    /**
     * Get all response headers.
     *
     * @return array Response headers.
     */
    public function getHeaders()
    {
        return $this->headers->all();
    }

    /**
     * Get current response status code.
     *
     * @return int Response status code.
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Get current response status message.
     *
     * @return string Response status message.
     */
    public function getStatusMessage()
    {
        return $this->statuses[$this->status];
    }

    /**
     * Set the response body.
     *
     * @param  null|scalar $body Scalar value to set.
     * @return Response    Response object for optional chaining.
     */
    public function setBody($body)
    {
        if (is_null($body) || is_scalar($body)) {
            $this->body = (string) $body;

            return $this;
        }

        $prefix = "Body must be null or scalar, ";
        $suffix = gettype($body) . " given.";

        throw new \InvalidArgumentException("{$prefix}{$suffix}");
    }

    /**
     * Set a response header.
     *
     * @param  string   $key   Key for header.
     * @param  string   $value Value for header.
     * @return Response Response object for optional chaining.
     */
    public function setHeader($key, $value)
    {
        if (is_string($key) && is_string($value)) {
            $this->headers->set($key, $value);

            return $this;
        }

        $name = is_string($key) ? "Value" : "Key";
        $type = gettype(is_string($key) ? $value : $key);

        throw new \InvalidArgumentException("{$name} must be string, {$type} given.");
    }

    /**
     * Set the response status.
     *
     * @param  int      $status Integer value to set.
     * @return Response Response object for optional chaining.
     */
    public function setStatus($status)
    {
        if (is_int($status) && in_array($status, $this->statuses)) {
            $this->status = $status;

            return $this;
        }

        if (is_int($status)) {
            throw new \LogicException("Status code must be a valid status code, but {$status} isn't.");
        }

        throw new \InvalidArgumentException("Status code must be integer, " . gettype($status) . " given.");
    }

    /**
     * Write (append) content to the response body.
     *
     * @param  null|scalar $appendix Content to append.
     * @return Response    Response object for optional chaining.
     */
    public function write($appendix)
    {
        if (is_null($appendix) || is_scalar($appendix)) {
            $this->body .= (string) $appendix;

            return $this;
        }

        $prefix = "Appended content must be null or scalar, ";
        $suffix = gettype($appendix) . " given.";

        throw new \InvalidArgumentException("{$prefix}{$suffix}");
    }

    /** Protected functions. */

    /**
     * Set the correct behaviour for empty and no-body responses.
     */
    protected function setEmptyResponse()
    {
        $empty = in_array($this->status, $this->no_body);

        if ($this->request->isHead() || $empty) {
            $this->body = null;
        }

        if ($empty) {
            $this->headers->delete("content-length");
            $this->headers->delete("content-type");
        }
    }

    /**
     * Echo the response body.
     */
    protected function writeBody()
    {
        echo((string) $this->body);
    }

    /**
     * Send response headers.
     */
    protected function writeHeaders()
    {
        if (headers_sent() === false) {
            header("{$this->request->protocol} {$this->statuses[$this->status]}");

            foreach ($this->headers->all() as $key => $value) {
                header("{$key}: {$value}", false);
            }
        }
    }
}
