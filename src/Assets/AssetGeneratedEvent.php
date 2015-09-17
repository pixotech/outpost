<?php

namespace Outpost\Assets;

use Outpost\Events\Event;

class AssetGeneratedEvent extends Event {

  public function __construct(AssetInterface $asset) {
    parent::__construct();
    $this->asset = $asset;
  }

  public function getLogMessage() {
    return sprintf("Asset generated: %s", $this->asset->getKey());
  }
}