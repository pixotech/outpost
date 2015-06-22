
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

## Create a site object

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
$environment = new \Outpost\Environments\DevelopmentEnvironment(__DIR__ . "/..");
Site::respond($environment);
```

Start a local webserver:

```sh
php -S 0.0.0.0:8080 -t quickstart/public
```

Visit the site in a browser:

[http://localhost:8080/](http://localhost:8080/)

Outpost says it doesn't recognize the request.

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
    return $this->makeResponse("Hello");
  }
}
```

Tell the site to use the new responder.

Change quickstart/src/Site.php

```php
class Site extends \Outpost\Site {
  protected function getResponders($request) {
    return [new Responders\Responder($this, $request)];
  }
}
```

Reload the page. You should see:

```
Hello
```
