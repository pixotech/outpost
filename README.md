Outpost is a lightweight web framework, designed for [decoupled][decoupled] websites. PHP 5.5+ is required.

### What Outpost Does

**Routing.** Outpost uses [Phroute][Phroute] to route an incoming request to the correct responder.

**Caching.** Each Outpost site has a [Stash][Stash] instance for storing resources between requests.

**Logging.** Outpost uses [Monolog][Monolog] to send status messages to lots of types of logs.

### What Outpost Doesn't Do

**HTTP.** Outpost doesn't provide a client for fetching external resources.

## Quickstart

Create a new directory for your Outpost installation. From inside this directory, use [Composer][Composer] to install Outpost:

```
composer require pixo/outpost
```

You should now have `composer.json` and `composer.lock` files, and a `vendor` directory containing Outpost and its dependencies.

Create a new directory called `public`, and in that directory make a new file called `index.php` with the following contents:

```php
<?php

# Get the Composer autoloader
require_once __DIR__ . '/../vendor/autoload.php';

# Get the incoming request
$request = Symfony\Component\HttpFoundation\Request::createFromGlobals();

# Create a Site object
$site = new Outpost\Site();

# Add an example route
$site->addRoute('GET', '/', function () { print "Hello."; });

# Send a response
$site->respond($request);
```

Start a local [PHP development server](http://php.net/manual/en/features.commandline.webserver.php) and point it at the `public` directory:

```
php -S 0.0.0.0:8080 -t public
```

Once the server is running, you should be able to visit [http://localhost:8080/](http://localhost:8080/) and see the following:

```
Hello.
```

### What Just Happened?

Outpost received a request for the home page, and it routed the request to a function that printed "Hello."

When you visited [http://localhost:8080/](http://localhost:8080/), the server directed you to the `index.php` script. It included the Composer autoloader, then made a new [Request](http://symfony.com/doc/current/components/http_foundation/introduction.html#request) object, using information from the server environment.

The script next created an Outpost [Site](#sites) object, and added one routing instruction: _when a visitor asks for the home page, run this function_. Functions or other [callables](http://php.net/manual/en/language.types.callable.php) used as the targets of routes are called [Responders](#responders).

Finally, the new Site's `respond()` method was called. The router used the Request object to find the right Responder: a function that printed "Hello."

## Sites

Site objects have two primary purposes:

* They route each incoming request to the appropriate Responder.
* They provide Resources needed to create responses.

## Responders

Responders act as router callbacks, and are expected to output a response when invoked.

Responder routes can be created using the site's `addRoute()` method:

```php
$site->addRoute('GET', '/news', new NewsPageResponder());
$site->addRoute('GET', '/news/article/{id}', new ArticlePageResponder());
```

Responders receive 3 parameters when invoked:

1. The Site object responding to the request
2. The Request object
3. Any parameters extracted from the URL

```php
class ArticlePageResponder
{
  public function __invoke($site, $request, array $params)
  {
    $articleId = list($params);
    print $this->render('about-page.tpl', $this->getArticle($articleId));
  }
}
```


## Resources

An Outpost installation may define any number of Resource classes. Resources are retrieved using the Site's `get()` method, and they receive the Site object when invoked. The simplest resource is just a callable:

```php
$resource = function ($site) { return 1; }
print $site->get($resource);
```

The `get()` method invokes the callable and returns the result, so the output would be:

```
1
```

## Caching

Resources that implement `CacheableInterface` can be stored in the site cache, and are only invoked when the cached resource is missing or stale. Cacheable resources have a unique key, and specify the number of seconds they may be cached before a refresh is required.

```php
class ExampleExpensiveResource implements \Outpost\Cache\CacheableInterface {

  public function __invoke($site) {
    # Something that takes a long time, then...
    return $this;
  }
  
  public function getCacheKey() {
    return 'examples/expensive';
  }
  
  public function getCacheLifetime() {
    return 3600; # 1 hour
  }
}
```

The first time this resource is requested, it is invoked, and the return value is stored in the site cache. For subsequent requests, the cached copy is returned, until the copy is older than the value of `getCacheLifetime()`.

```php
# Nothing in the cache for this call, so Outpost invokes the Resource
# and caches the return value.
$fresh = $site->get(new ExampleExpensiveResource());

# This time the Resource is in the cache, so Outpost returns the cached Resource.
$cached = $site->get(new ExampleExpensiveResource());

# An hour passes...

# Now the cached copy is stale, so Outpost will invoke the Resource again,
# and replace the cached copy.
$fresh = $site->get(new ExampleExpensiveResource());
```

The `Site::getCache()` method provides access to the underlying [Stash][Stash] cache object.

```php
# Clear a specific key
$site->getCache()->clear('cache/key');

# Clear a range of keys
$site->getCache()->clear('things/id/*');

# Clear the whole cache
$site->getCache()->clear();

# Flush the cache
$site->getCache()->flush();
```

[Composer]: https://getcomposer.org/
[decoupled]: http://www.pixotech.com/decoupling-drupal/
[HttpFoundation]: http://symfony.com/doc/current/components/http_foundation/index.html
[Monolog]: https://github.com/Seldaek/monolog
[Phroute]: https://github.com/mrjgreen/phroute
[Pixo]: http://www.pixotech.com/
[Stash]: http://www.stashphp.com/
