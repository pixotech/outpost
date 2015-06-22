# Web

Outpost contains an web client, powered by [Guzzle][guzzle]. It features extensible request classes and response caching.

## Requests

Outpost provides three Request classes by default:

  * The base Request class is constructed with a URL and method (defaults to GET) and returns the body of the response.

    ```php
    $resource = $client->send(new Request("http://example.com/resource.html");

    $result = $client->send(new Request("http://example.com/resource.php", "POST");
    ```

  * The JSON Request class returns the response body as decoded JSON.

    ```php
    $json = $client->send(new JsonRequest("http://example.com/resource.json");
    ```

  * The File Request class is meant for fetching remote files. It returns a [stream][stream] object:

    ```php
    $stream = $client->send(new FileRequest("http://example.com/resource.jpg");
    ```

## Custom requests

The `Client::send()` method accepts any object which implements `RequestInterface`. The following methods are used to construct a [Guzzle request][guzzle request]:

  * `getRequestBody()`
  * `getRequestHeaders()`
  * `getRequestMethod()`
  * `getRequestOptions()`
  * `getRequestUrl()`

Request objects also implement the following methods used in handling responses from the Guzzle client:

  * `validateResponse()` is used to ensure that the response meets the criteria for a successful response, such as having a valid status code (see "Exceptions" below)
  * `processResponse()` can be used to alter the format of the response before it is returned

## Caching

Requests that implement `CacheableRequestInterface` can have their responses cached to prevent duplicate requests. Cacheable requests must implement the following methods:

  * `getCacheKey()` provides the [key][stash key] used to store the item in the site cache. The key will be prefixed with `Client::getCacheNamespace()`.
  * `getCacheLifetime()` provides the expiration information of the cache item

## Exceptions

The web client will throw exceptions if the response's status code begins with a '4' (Client Error) or '5' (Server Error). These exceptions provide the following methods:

  * `getRequest()` returns the original request object
  * `getResponse()` returns the [response object][guzzle response] received from Guzzle

## Guzzle

Access to the underlying Guzzle client is provided via the `getClient()` method.

```php
$guzzle = $client->getClient();
$guzzle->get("http://example.com/resource.html");
```

**N.B.** At this time, Outpost uses version 5.3 of the Guzzle library, to provide compatibility with PHP 5.4. This support will be dropped in upcoming versions, and Outpost will switch to using Guzzle version 6.0 and higher.

[guzzle]: http://guzzle.readthedocs.org/en/5.3/index.html
[stream]: http://guzzle.readthedocs.org/en/5.3/streams.html
[guzzle request]: http://guzzle.readthedocs.org/en/5.3/http-messages.html#requests
[guzzle response]: http://guzzle.readthedocs.org/en/5.3/http-messages.html#responses
[stash key]: http://www.stashphp.com/Basics.html#keys