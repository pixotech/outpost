<?php

namespace Outpost\Assets;

use Outpost\Resources\ResourceInterface;
use Outpost\SiteInterface;

abstract class Asset implements AssetInterface, ResourceInterface {

  public function __invoke(SiteInterface $site) {
    if (!$site->hasLocalAsset($this)) $site->createAssetMarker($this);
    return new LocalAsset($this, $site->getAssetUrl($this));
  }
}