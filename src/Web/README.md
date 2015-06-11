# Web

Outpost sites provide an HTTP client with the following capabilities:

* Get a remote resource. By default, the Cache will be used to minimize duplicate requests. An alternative method is provided to bypass caching.
* Post data to a remote resource.

This class is a wrapper for a [Guzzl\Client][guzzl] instance, which is available via the `getClient()` method.

[guzzl]: http://guzzlephp.org/