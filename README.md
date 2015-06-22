# A PHP Framework for Decoupled Websites

## Features

* A caching web client for fetching and storing remote resources

* Remote image fetching and local transformations, including resizing, cropping, and compositing

* Development log and debugging tools

Outpost has native support for these libraries:

* **[Stash](https://github.com/tedious/stash).** Configurable by environment. Use Memcache in production site, use a filesystem cache or no cache in development.

* **[Guzzle](https://github.com/guzzle/guzzle).** The Outpost web client wraps a Guzzle client, and caches most requests by default. Used to fetch remote assets.

* **[Monolog](https://github.com/Seldaek/monolog).** Configurable by environment. Verbose logging in development.

* **[ImageMagick](http://www.imagemagick.org/).** Runs ImageMagick from the shell. Support for resizing, limited support for composing.
