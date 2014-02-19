<?php

namespace oscarpalmer\Shelf;

/**
 * Main Shelf class.
 */
class Shelf
{
    /**
     * @var string Current version number.
     */
    const VERSION = "0.1.0";

    /** Static functions. */

    /**
     * Creates a new Request object from array of server parameters or superglobals.
     *
     * @param  array   $server Server parameters. Optional; defaults to $_SERVER.
     * @return Request A new Request object.
     */
    public static function Request(array $server = null)
    {
        return new Request($server);
    }

    /**
     * Creates a new Response object;
     * defaults to a clean and successful HTML response.
     *
     * @param  int      $status  Response status code.
     * @param  scalar   $body    Response body.
     * @param  array    $headers Response headers.
     * @return Response A new Response object.
     */
    public static function Response(
        $status = 200,
        $body = "",
        array $headers = array("Content-Type" => "text/html")
    ) {
        return new Response($status, $body, $headers);
    }
}
