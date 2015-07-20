<?php

namespace Outpost\Assets;

class LocalAsset {

  protected $asset;
  protected $url;

  public function __construct(AssetInterface $asset, $url) {
    $this->asset = $asset;
    $this->url = $url;
  }

  public function getAsset() {
    return $this->asset;
  }

  public function getUrl() {
    return $this->url;
  }
}