<?php

declare(strict_types=1);

namespace oscarpalmer\Shelf;

mb_internal_encoding('UTF-8');

use oscarpalmer\Shelf\Blob\IBlob;
use oscarpalmer\Shelf\Blob\Blob;

/**
 * Response class
 */
class Response
{
    /**
     * @var array Status codes for no-body responses
     */
    protected static array $no_body = [100, 101, 102, 103, 204, 304];

    /**
     * @var array Response status messages
     */
    protected static array $statuses = [
        # Informational
        100 => 'Continue',
        101 => 'Switching Protocols',
        102 => 'Processing',
        103 => 'Early Hints',

        # Success
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        203 => 'Non-Authoritative Information',
        204 => 'No Content',
        205 => 'Reset Content',
        206 => 'Partial Content',
        207 => 'Multi-Status',
        208 => 'Already Reported',
        226 => 'IM Used',

        # Redirection
        300 => 'Multiple Choices',
        301 => 'Moved Permanently',
        302 => 'Found',
        303 => 'See Other',
        304 => 'Not Modified',
        305 => 'Use Proxy',
        306 => '(Unused)',
        307 => 'Temporary Redirect',
        308 => 'Permanent Redirect',

        # Client errors
        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Timeout',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Required',
        413 => 'Request Entry Too Large',
        414 => 'Request-URI Too Long',
        415 => 'Unsupported Media Type',
        416 => 'Requested Range Not Satisfiable',
        417 => 'Expectation Failed',
        418 => "I'm a teapot",
        421 => 'Misdirected Request',
        422 => 'Unprocessable Entity',
        423 => 'Locked',
        424 => 'Failed Dependency',
        425 => 'Too Early',
        426 => 'Upgrade Required',
        428 => 'Precondition Required',
        429 => 'Too Many Requests',
        431 => 'Request Header Fields Too Large',
        451 => 'Unavailable for Legal Reasons',

        # Server errors
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Timeout',
        505 => 'HTTP Version Not Supported',
        506 => 'Variant Also Negotiates',
        507 => 'Insufficient Storage',
        508 => 'Loop Detected',
        509 => 'Bandwidth Limit Exceeded',
        510 => 'Not Extended',
        511 => 'Network Authentication Required'
    ];

    /**
     * @var string Response body
     */
    protected ?string $body;

    /**
     * @var IBlob Response headers
     */
    protected IBlob $headers;

    /**
     * @var Request The Request object used by the response.
     */
    protected Request $request;

    /**
     * @var int Response status code.
     */
    protected int $status;

    /**
     * @var bool Has the response finished?
     */
    protected bool $finished = false;

    /**
     * Creates a new Response object,
     * defaults to a clean and successful HTML response
     *
     * @param int|float|string|bool|null $body Response body
     * @param int $status Response status code
     * @param array $headers Response headers
     */
    public function __construct(
        int|float|string|bool|null $body = '',
        int $status = 200,
        array $headers = ['content-type' => 'text/html; charset=utf-8']
    ) {
        $this->setStatus($status);
        $this->setBody($body);

        $this->headers = new Blob($headers);
    }

    /** Public functions */

    /**
     * Finish the response and return the object
     *
     * @param Request Request object used create a nice response
     * @return Response The Response object
     */
    public function finish(Request $request): Response
    {
        if ($this->finished) {
            throw new \LogicException('The response has already finished.');
        }

        $this->request = $request;

        $this->headers->set('content-length', mb_strlen($this->getBody(), 'UTF-8'));
        $this->setEmptyResponse();

        $this->writeHeaders();
        $this->writeBody();

        $this->finished = true;

        return $this;
    }

    /**
     * Get the current response body
     *
     * @return string Response body
     */
    public function getBody(): string
    {
        return (string) $this->body;
    }

    /**
     * Get value for a specific response header
     *
     * @param string $key Key for header
     * @return int|float|string|bool|null Value for found header or null
     */
    public function getHeader(string $key): int|float|string|bool|null
    {
        return $this->headers->get($key);
    }

    /**
     * Get all response headers
     *
     * @return array Response headers
     */
    public function getHeaders(): array
    {
        return $this->headers->all();
    }

    /**
     * Get current response status code
     *
     * @return int Response status code
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * Get response status message for current or specified status code
     *
     * @param int Optional status code
     * @return string Response status message
     */
    public function getStatusMessage(int $code = null): string
    {
        if (is_null($code) === false && array_key_exists($code, self::$statuses) === false) {
            throw new \InvalidArgumentException("Status code must be a valid status code, but '{$code}' is not.");
        }

        $status = $code ?? $this->status;
        $message = self::$statuses[$status];

        return "$status $message";
    }

    /**
     * Has the response finished?
     * 
     * @return bool True if finished
     */
    public function isFinished(): bool
    {
        return $this->finished;
    }

    /**
     * Set the response body
     *
     * @param int|float|string|bool|null $body Scalar value to set
     * @return Response Response object for optional chaining
     */
    public function setBody(int|float|string|bool|null $body): Response
    {
        $this->body = is_null($body)
            ? ''
            : (string) $body;

        return $this;
    }

    /**
     * Set a response header
     *
     * @param string $key Key for header
     * @param int|float|string|bool|null $value Value for header
     * @return Response Response object for optional chaining
     */
    public function setHeader(string $key, int|float|string|bool|null $value): Response
    {
        if (is_null($value)) {
            $this->headers->delete($key);
        } else {
            $this->headers->set($key, $value);
        }

        return $this;
    }

    /**
     * Set multiple response headers
     *
     * @param array $headers Response headers
     * @return Response Response object for optional chaining
     */
    public function setHeaders(array $headers): Response
    {
        foreach ($headers as $key => $value) {
            $this->setHeader($key, $value);
        }

        return $this;
    }

    /**
     * Set the response status
     *
     * @param int $status Integer value to set
     * @return Response Response object for optional chaining
     */
    public function setStatus(int $status): Response
    {
        if (array_key_exists($status, self::$statuses) === false) {
            throw new \InvalidArgumentException("Status code must be a valid status code, but '{$status}' is not.");
        }

        $this->status = $status;

        return $this;
    }

    /**
     * Write (append) content to the response body
     *
     * @param int|float|string|bool|null $content Content to append
     * @return Response Response object for optional chaining
     */
    public function write(int|float|string|bool|null $content): Response
    {
        $this->body .= is_null($content)
            ? ''
            : (string) $content;

        return $this;
    }

    /** Protected functions */

    /**
     * Set the correct behaviour for empty and no-body responses
     */
    protected function setEmptyResponse()
    {
        $empty = in_array($this->status, self::$no_body);

        if ($this->request->isHead() === false && $empty === false) {
            return;
        }

        $this->body = null;

        if ($empty) {
            $this->headers->delete('content-length');
            $this->headers->delete('content-type');
        }
    }

    /**
     * Echo the response body
     */
    protected function writeBody()
    {
        echo (is_null($this->body) ? '' : (string) $this->body);
    }

    /**
     * Send response headers
     */
    protected function writeHeaders()
    {
        if (headers_sent()) {
            return;
        }

        $protocol = $this->request->getProtocol();

        header("{$protocol} {$this->getStatusMessage()}");

        foreach ($this->headers->all() as $key => $value) {
            header("{$key}: {$value}", false);
        }
    }
}
