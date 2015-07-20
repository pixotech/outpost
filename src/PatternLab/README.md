# Using Outpost with Pattern Lab

Adding `PatternLabTrait` to an Outpost site gives it these capabilities:

* The `renderPattern($pattern, $vars)` method renders the pattern named `$pattern` using `$vars` as variables. [Shorthand and default syntax](http://patternlab.io/docs/pattern-including.html) are accepted for pattern names.

* The `getPatternLab()` method returns an object representation of the Pattern Lab installation. By default, Outpost will attempt to cache this object between requests.

* The `getTwig()` method returns the Twig parser being used to parse Pattern Lab templates.

## Installation

Add the `outpost-patternlab` package to your Composer file:

```json
"pixo/outpost-patternlab": "dev-master"
```

Use the `PatternLabTrait` in your Site file:

```php
class YourSite {

  use \Outpost\PatternLab\PatternLabTrait;

}
```
## Components

* [Twig loader](src/Twig)

* [Asset responder](src/Assets)
