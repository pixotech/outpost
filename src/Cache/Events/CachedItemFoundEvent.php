<?php

namespace Outpost\Cache\Events;

use Outpost\Events\EventMessage;

class CachedItemFoundEvent extends CacheEvent {

  public function getColor() {
    return EventMessage::WHITE_ON_CYAN;
  }

  public function getLogMessage() {
    return sprintf("Found: %s", $this->getItemKey());
  }
}