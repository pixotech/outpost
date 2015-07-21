<?php

namespace Outpost\Cache\Events;

use Outpost\Events\EventMessage;

class ItemMissingEvent extends CacheEvent {

  public function getColor() {
    return EventMessage::WHITE_ON_YELLOW;
  }

  public function getLogMessage() {
    return sprintf("Not found: %s", $this->getItemKey());
  }
}