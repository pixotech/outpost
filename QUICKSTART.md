
# Creating an Outpost site


## Installation

Create source directory

```sh
mkdir quickstart
mkdir quickstart/src
```

Create quickstart/composer.json

```json
{
  "autoload": {
    "psr-4": {"Quickstart\\": "src/"}
  },
  "require": {
    "pixo/outpost": "dev-master"
  }
}
```

Run composer

```sh
composer up
```

### Create site object

Create quickstart/src/Site.php
```php
<?php
namespace Quickstart;
class Site extends \Outpost\Site {
}
```

## Create a public endpoint

Create public directory

```sh
mkdir quickstart/public
```

Create quickstart/public/index.php

```php
<?php
namespace Quickstart;
require_once "../vendor/autoload.php";
Site::respond();
```

Start a local webserver:

```sh
php -S 0.0.0.0:8080 -t quickstart/public
```

Visit the site in a browser:

[http://localhost:8080/](http://localhost:8080/)

Outpost says it can't find the site


## Add a configuration file

Create quickstart/outpost.json:

```json
{
}
```

Reload the site. Outpost says it doesn't recognize the request.



## Add a responder

Create a directory for responders:

```sh
mkdir quickstart/src/Responders
```

Create quickstart/src/Responders/Responder.php
```php
<?php
namespace Quickstart\Responders;
class Responder extends \Outpost\Responders\Responder {
  function invoke() {
    return "Hello.";
  }
}
```

Tell the site to use the new responder.

Change quickstart/src/Site.php

```php
class Site extends \Outpost\Site {
  protected function getResponders($request) {
    return new Responders\Responder($this, $request);
  }
}
```

Invalid response

Use the makeResponse() method to create a Response from text.

Change quickstart/src/Responders/Responder.php

```php
  function invoke() {
    return $this->makeResponse("Hello.");
  }
```

Reload the page. Hello!
