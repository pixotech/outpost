<?php

namespace Outpost\Assets;

use Outpost\Events\Event;
use Outpost\Events\EventMessage;

class AssetGeneratedEvent extends Event {

  public function __construct(AssetInterface $asset) {
    parent::__construct();
    $this->asset = $asset;
  }

  public function getColor() {
    return EventMessage::WHITE_ON_GREEN;
  }

  public function getLocation() {
    return "Assets";
  }

  public function getLogMessage() {
    return sprintf("Asset generated: %s", $this->asset->getKey());
  }
}