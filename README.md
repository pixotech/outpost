```php
<?php

class Site extends Outpost\Site
{
  public function __construct()
  {
    $this->getRouter()->route('GET', '/index', new HomePageResponder());
  }
}
```

```php
<?php

class HomePageResponder
{
  public function __invoke($site, $request)
  {
    $context = ['page' => $site->get(new HomePageResource())];
    print $site->render('home.twig', $context);
  }
}
```