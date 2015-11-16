<?php

namespace Outpost\Mocks\Assets;

use Outpost\Assets\AssetInterface;
use Outpost\Assets\AssetManagerInterface;
use Outpost\SiteInterface;

class Asset extends \Outpost\Assets\Asset implements AssetInterface {

  public $extension;
  public $key;

  public function __construct($key, $extension) {
    $this->key = $key;
    $this->extension = $extension;
  }

  public function generate(\SplFileInfo $file, AssetManagerInterface $assets) {
    // TODO: Implement generate() method.
  }

  public function getExtension() {
    return $this->extension;
  }

  public function getKey() {
    return $this->key;
  }
}