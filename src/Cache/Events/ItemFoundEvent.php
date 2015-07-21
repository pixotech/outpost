<?php

namespace Outpost\Cache\Events;

use Outpost\Events\EventMessage;

class ItemFoundEvent extends CacheEvent {

  public function getColor() {
    return EventMessage::WHITE_ON_GREEN;
  }

  public function getLogMessage() {
    return sprintf("Found: %s", $this->getItemKey());
  }
}