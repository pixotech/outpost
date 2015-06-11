# Outpost

Site objects are constructed with Environments, and represent distinct Outpost installations. In almost every case it will be advantageous to extend the base `Site` class to the unique needs of the project.

The `Site` class contains one transitive method: `invoke()`. It receives a `Request` object and is expected to return a `Response` object, otherwise an `InvalidResponseException` is thrown.

The static `respond()` method accepts an Environment and an optional Request object. It attempts to:

1. Instantiate a Site within the Environment
2. Call the Site's `invoke()` method, with the provided Request or one supplied by the Environment object
3. Send the returned Response

An Outpost site contains these resources:

* The `getClient()` method returns the Web Client, an instance of [`Guzzl\Client`][guzzl client], for making HTTP requests to other sites.
* The `getCache()` method returns the site Cache, an instance of [`Stash\Pool`][stash pool]. The Web Client will attempt to store the results of `GET` requests in the Cache, unless otherwise configured.
* The `getLog()` method returns the site Log, a [`Monolog\Logger`][logger] instance. In development environments, the log is written to `log/outpost.log` by default.

An Outpost site will attempt to load data from two JSON files:

* `outpost.json` should contain data that is safe to store in a repository. Data from this file is available via the `getSetting()` method.
* `secrets.json` should be used to store sensitive data, such as passwords and API keys. Data from this file is available via the `getSecret()` method.

[guzzl client]: https://github.com/guzzle/guzzle/blob/master/src/Client.php
[stash pool]: https://github.com/tedious/Stash/blob/master/src/Stash/Pool.php
[logger]: https://github.com/Seldaek/monolog/blob/master/src/Monolog/Logger.php