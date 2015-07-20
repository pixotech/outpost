<?php

namespace Outpost\Assets;

use Outpost\SiteInterface;

class MockAsset extends Asset implements AssetInterface {

  public $extension;
  public $key;

  public function __construct($key, $extension) {
    $this->key = $key;
    $this->extension = $extension;
  }

  public function generate(SiteInterface $site, \SplFileInfo $file) {
    // TODO: Implement generate() method.
  }

  public function getExtension() {
    return $this->extension;
  }

  public function getKey() {
    return $this->key;
  }
}