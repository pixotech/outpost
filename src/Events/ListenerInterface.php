<?php

namespace Outpost\Events;

use Outpost\SiteInterface;

interface ListenerInterface {
  public function handleEvent(EventInterface $event, SiteInterface $site);
}