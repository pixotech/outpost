
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

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Site extends \Outpost\Site {

  public function getResponse(Request $request) {
    return new Response("Hello");
  }
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

use Outpost\Environments\DevelopmentEnvironment;
use Symfony\Component\HttpFoundation\Request;

require_once __DIR__ . '/../../outpost/vendor/autoload.php';
require_once __DIR__ . '/../vendor/autoload.php';

$request = Request::createFromGlobals();
$site = new Site(new DevelopmentEnvironment(__DIR__ . '/..'));
$site->respond($request);
```

Start a local webserver:

```sh
php -S 0.0.0.0:8080 -t quickstart/public
```

Visit the site in a browser:

[http://localhost:8080/](http://localhost:8080/)


You should see:

```
Hello
```