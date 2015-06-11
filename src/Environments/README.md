# Environments

Environment objects are designed to account for differences among instances of an Outpost site. Sites could expect to have separate Environments for development, production, testing, and command-line usage.

The default Environment classes are constructed with the root path of the Outpost installation:

```php
new DevelopmentEnvironment("/path/to/outpost");
```