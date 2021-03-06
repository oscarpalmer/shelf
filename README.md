# Shelf

[![PHP version](https://badge.fury.io/ph/oscarpalmer%2Fshelf.svg)](http://badge.fury.io/ph/oscarpalmer%2Fshelf) [![Build Status](https://travis-ci.org/oscarpalmer/shelf.png?branch=master)](https://travis-ci.org/oscarpalmer/shelf) [![Coverage](https://codecov.io/gh/oscarpalmer/shelf/branch/master/graph/badge.svg)](https://codecov.io/gh/oscarpalmer/shelf)

Shelf is a [Rack](//rack.github.io)-like interface for PHP `>=7`.

## Getting started

### Installation

Shelf is available via [Composer & Packagist](//packagist.org/packages/oscarpalmer/shelf).

```json
{
  "require": {
    "oscarpalmer/shelf": "2.3.*"
  }
}
```

### Basic usage

Here are two small examples to get you up and running in ~ten seconds. Please consult the [API reference](#api) if you get stuck or want to learn more.

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
# Constants
Shelf::VERSION;                  #  Current Shelf version number
```

### Request

```php
# Constructor
$request = new Request(
    $server,                     #  Server parameters, like $_SERVER
    $session                     #  True to start a session, or name of session to start
);
Request::fromGlobals($session);  #  Static constructor for a superglobal Request object

# Constants
Request::REQUEST_METHODS         #  Array of acceptable request methods

# Blobs
# Check below for info on how to use Blobs
$request->data;                  #  A Blob of request (~$_POST) parameters
$request->files;                 #  A Blob of files (~$_FILES) parameters
$request->query;                 #  A Blob of query (~$_GET) parameters
$request->server;                #  A Blob of server (~$_SERVER) parameters

# Variables
$request->path_info;             #  Current path
$request->protocol;              #  Current protocol
$request->request_method;        #  Current request method

# You can also access other server variables, e.g.:
$request->server_admin           #  you@your-email.com

# Methods
$request->isAjax();              #  Is it an AJAX request?
$request->isDelete();            #  Is it a delete request?
$request->isGet();               #  Is it a get request?
$request->isHead();              #  Is it a head request?
$request->isPost();              #  Is it a post request?
$request->isPut();               #  Is it a put request?
```

### Response

```php
# Constructor
$response = new Response(
    $body,                       #  Response body; must be scalar or null
    $status,                     #  Response status code; must be integer
    $headers                     #  Response headers; must be an array
);

# Constants
Response::NO_BODY_STATUSES       #  Status codes for when to drop the response's body
Response::RESPONSE_STATUSES      #  Acceptable status codes, and their messages

# Methods
$response->finish($request);     #  Sets the response headers and echoes the response body
                                 #  The parameter must be a Shelf Request object

# Getters
$response->getBody();            #  Returns the current response body
$response->getHeader($name);     #  Returns a response header by name
$response->getHeaders();         #  Returns all response headers
$response->getStatus();          #  Returns the current response status code
$response->getStatusMessage();   #  Returns the current response status message

# Setters
$response->setBody($body);       #  Set the response body; must be scalar or null
$response->setHeader($k, $v);    #  Set a new response header; both key and value must be strings
$response->setStatus($status);   #  Set the response status code; must be integer
$response->write($appendix);     #  Append content to the response body; must be scalar or null
```

### Blob, Cookies, and Session

Blob is a container class for parameter storage. Both the Cookies and Session class shares the same interface.

```php
# Constructor
$item = new Blob($array);        #  The parameter is optional and defaults to an empty array
$cookies = new Cookies($array);  #  Standalone Blob-like object for handling cookies
$session = new Session($var);    #  True to start a session, or name of session to start;
                                 #  Request starts sessions, so you don't have to worry about
                                 #  this constructor as it'll abort if $_SESSION alredy exists

# Methods
$item->all();                    #  Get the actual array of data
$item->delete('key');            #  Delete item; returns object for chaining
$item->get('key', 'default');    #  Get data parameter by key with an optional default value
$item->has('key');               #  Check if key exists in data
$item->set('key', 'value');      #  Set value for key; returns object for chaining
```

## License

MIT Licensed; see [the LICENSE file](LICENSE) for more info.
