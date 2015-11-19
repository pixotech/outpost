<?php

namespace Outpost\Assets\Events;

use Outpost\Assets\AssetInterface;
use Outpost\Events\Event;

class MarkerCreatedEvent extends Event {

  public function __construct(AssetInterface $asset) {
    parent::__construct();
    $this->asset = $asset;
  }

  public function getLogMessage() {
    return sprintf("Marker created for asset %s", $this->asset->getKey());
  }
}