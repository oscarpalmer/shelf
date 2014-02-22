# Shelf

[![Build Status](https://travis-ci.org/oscarpalmer/shelf.png?branch=master)](https://travis-ci.org/oscarpalmer/shelf) [![Coverage Status](https://coveralls.io/repos/oscarpalmer/shelf/badge.png?branch=master)](https://coveralls.io/r/oscarpalmer/shelf?branch=master)

Shelf is a [Rack](//rack.github.io)-like interface for PHP `>=5.3`.

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

Here are two small examples to get you up and running in ~ten seconds. Please consult the [API reference](#api) if you get stuck or want to learn more.

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

$response->finish();
```

## API

### Shelf

```php
# Constants
Shelf::VERSION;                  # Current Shelf version number.
```

### Request

```php
# Constructor
$request = new Request($s, $q, $d); # Server, query, and data parameters.
Request::fromGlobals();             # Static constructor for a superglobal Request object.

# Blobs
# Check below for info on how to use Blobs
$request->data;                  # A Blob of request (~$_POST) parameters.
$request->query;                 # A Blob of query (~$_GET) parameters.
$request->server;                # A Blob of server (~$_SERVER) parameters.

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

### Blob

Blob is a container class for parameter storage.

```php
# Constructor
$blob = new Blob($array);        # The parameter is optional and defaults to an empty array.

# Methods
$blob->all();                    # Get the actual Blob array.
$blob->delete("key");            # Delete item; returns Blob object for chaining.
$blob->get("key", "default");    # Get Blob parameter by key with an optional default value.
$blob->has("key");               # Check if key exists in Blob.
$blob->set("key", "value");      # Set value for key; returns Blob object for chaining.
```

## Todo

- More and better documentation.
- Helper methods for both `Request` and `Response`.
- Properly handle `204`, `205`, and `304`-responses.
- `$_COOKIES`, `$_FILES`, and `$_SESSION`.

## Licence

Copyright (c) 2014 Oscar Palmér. MIT Licensed.