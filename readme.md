# Shelf

[![Build Status](https://travis-ci.org/oscarpalmer/shelf.png?branch=master)](https://travis-ci.org/oscarpalmer/shelf) [![Coverage Status](https://coveralls.io/repos/oscarpalmer/shelf/badge.png?branch=master)](https://coveralls.io/r/oscarpalmer/shelf?branch=master)

Shelf is a [Rack](//rack.github.io)-like interface for PHP.

## Getting started

### Installation

Shelf is available via Composer.

```json
{
  "require": {
    "oscarpalmer/shelf": "dev-master"
  }
}
```

### Basic usage

Here are two small examples to get you up and running in ~ten seconds. Please consult the [API reference](#API) if you get stuck or want to learn more.

#### Request

```php
$request = new Request($server);

echo $request->path_info;
```

#### Response

```php
$response = new Response(
    200,
    "Hello, world!",
    array("Content-Type" => "text/plain")
);

$response->finish(); # Sets headers and echoes the body.
```

## API

### Request

```php
# Constructor
$request = new Request($server); # The parameter is optional, but useful for testing; defaults to $_SERVER.

# Variables
$request->path_info;             # Current path.
$request->protocol;              # Current protocol.
$request->request_method;        # Current request method.

# You can also access other server variables, e.g.:
$request->server_admin           # you@your-email.com

# Methods
$request->isDelete();            # Is it a delete request?
$request->isGet();               # Is it a get request?
$request->isHead();              # Is it a head request?
$request->isPost();              # Is it a post request?
$request->isPut();               # Is it a put request?
```

### Response

```php
# Constructor
$response = new Response(
    $status,                     # Response status code; must be integer.
    $body,                       # Response body; must be scalar.
    $headers                     # Response headers; must be an array.
);

# Methods
$response->finish();             # Sets the response headers and echoes the response body.

# Getters
$response->getBody();            # Returns the current response body.
$response->getHeader($name);     # Returns a response header by name.
$response->getHeaders();         # Returns all response headers.
$response->getStatus();          # Returns the current response status code.
$response->getStatusMessage();   # Returns the current response status message.

# Setters
$response->setBody($body);       # Set the response body; must be scalar.
$response->setHeader($k, $v);    # Set a new response header; both key and value must be strings.
$response->setStatus($status);   # Set the response status code; must be integer.
$response->write($appendix);     # Append content to the response body; must be scalar.
```

## Todo

- More and better documentation.
- Access to `$_GET` and `$_POST`-parameters.
- Helper methods for both `Request` and `Response`.
- Properly handle `204`, `205`, and `304`-responses.
- `$_COOKIES`, `$_FILES`, and `$_SESSION`.

## Licence

Copyright (c) 2014 Oscar Palmér. MIT Licensed.