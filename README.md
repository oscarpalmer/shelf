# Shelf

[![PHP version](https://badge.fury.io/ph/oscarpalmer%2Fshelf.svg)](http://badge.fury.io/ph/oscarpalmer%2Fshelf) [![Build Status](https://travis-ci.com/oscarpalmer/shelf.png?branch=master)](https://travis-ci.com/oscarpalmer/shelf) [![Coverage](https://codecov.io/gh/oscarpalmer/shelf/branch/master/graph/badge.svg)](https://codecov.io/gh/oscarpalmer/shelf)

Shelf is a [Rack](//rack.github.io)-like interface for PHP `>=8`.

## Getting started

### Installation

Shelf is available via [Composer & Packagist](//packagist.org/packages/oscarpalmer/shelf).

```json
{
  "require": {
    "oscarpalmer/shelf": "3.0.*"
  }
}
```

### Basic usage

Here are two small examples to get you up and running in ~ten seconds. Please consult [the API reference](#api) if you get stuck or want to learn more.

#### Request

```php
use oscarpalmer\Shelf\Request;

$request = new Request($server); # Or new Request::fromGlobals();

echo $request->path_info;
```

#### Response

```php
use oscarpalmer\Shelf\Response;

$response = new Response(
    'Hello, world!',
    200,
    ['Content-Type' => 'text/plain']
);

$response->finish($request);
```

## API

### Shelf

```php
# Shelf version
Shelf::VERSION
```

### Request

```php
# Constructor
# Takes an array of server variables and a session variable;
# the session variable can be either boolean (to enable/disable sessions),
# or a string (to enable a session with a unique name)
$request = new Shelf\Request($server, $session);

# Check if HTTP request matches an expected type
$request->isDelete();
$request->isGet();
$request->isHead();
$request->isOptions();
$request->isPatch();
$request->isPost();
$request->isPut();

# Check if HTTP request was made via AJAX
$request->isAjax();

# Getters for Blobs (described below) for accessing HTTP request information
$request->cookies;  # $_COOKIES
$request->data;     # $_POST
$request->files;    # $_FILES
$request->query;    # $_GET
$request->server;   # $_SERVER or custom server variables
$request->session;  # $_SESSION

# Alternative to using the constructor; automatically uses the $_SERVER-variables
# The session variable still works the same :)
Shelf\Request::fromGlobals($session);
```

### Response

```php
# Constructor
# Takes a scalar body, an HTTP status code, and an array of HTTP headers
$response = new Shelf\Response($body, $status, $headers);

# Retrieves the response body as a string
$response->getBody();

# Retrieves the value for a header
$response->getHeader();

# Retrieves all headers
$response->getHeaders();

# Retrieves the status code 
$response->getStatus();

# Retrieves a status message for the response, e.g. '200 OK'
$response->getStatusMessage();

# Set a scalar value as the response body
$response->setBody($body);

# Set a response header
$response->setHeader($key, $value);

# Set multiple respons headers
$response->setHeaders($headers);

# Set response status
$response->setStatus($status);

# Append scalar value to the response body
$response->write($content);
```

### Blob

Blobs are containers used to store any kind of iterable data. In the `Request`-class, Blobs are used to manage `$_COOKIES`, `$_FILES`, `$_GET`, `$_POST`, `$_SERVER` (or custom server variables), and `$_SESSION`-information. In the `Response`-class, a Blob is used to manage HTTP-headers.

```php
# Retrieve all Blob values as an array
$blob->all();

# Delete a value by key
$blob->delete($key);

# Retrieve a value by key with an optional default value
$blob->get($key, $default);

# Check if Blob has key
$blob->has($key);

# Set value by key
$blob->set($key, $value);
```

## License

MIT Licensed; see [the LICENSE file](LICENSE) for more info.
