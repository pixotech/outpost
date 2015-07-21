<?php

namespace Outpost\Assets;

use Outpost\Events\Event;
use Outpost\Events\EventMessage;

class MarkerCreatedEvent extends Event {

  public function __construct(AssetInterface $asset) {
    parent::__construct();
    $this->asset = $asset;
  }

  public function getColor() {
    return EventMessage::WHITE_ON_YELLOW;
  }

  public function getLocation() {
    return "Assets";
  }

  public function getLogMessage() {
    return sprintf("Marker created for asset %s", $this->asset->getKey());
  }
}