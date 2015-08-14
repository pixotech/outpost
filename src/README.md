# Outpost

An Outpost site will attempt to load data from two JSON files:

* `settings.json` should contain data that is safe to store in a repository. Data from this file is available via the `getSetting()` method.
* `secrets.json` should be used to store sensitive data, such as passwords and API keys. Data from this file is available via the `getSecret()` method.

## Components

* [Asset management](Assets)
* [Command line tools](Command)
* [Environments](Environments)
* [HTML generation](Html)
* [Image manipulation](Images)
* [Recovery](Recovery)
* [Responders](Responders)
* [Structures](Structures)
* [Web client](Web)

[guzzl client]: https://github.com/guzzle/guzzle/blob/master/src/Client.php
[stash pool]: https://github.com/tedious/Stash/blob/master/src/Stash/Pool.php
[logger]: https://github.com/Seldaek/monolog/blob/master/src/Monolog/Logger.php
