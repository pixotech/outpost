<?php

namespace Outpost\Assets;

use Outpost\ResourceInterface;
use Outpost\SiteInterface;

abstract class Asset implements AssetInterface, ResourceInterface {

  public function __invoke(SiteInterface $site) {
    $assets = $site->getAssetManager();
    if (!$assets->hasLocalAsset($this)) $assets->createAssetMarker($this);
    return new LocalAsset($this, $assets->getAssetUrl($this));
  }
}