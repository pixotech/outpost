<?php

/**
 * @package Outpost
 * @author Pixo <info@pixotech.com>
 * @copyright 2015, Pixo
 * @license http://opensource.org/licenses/NCSA NCSA
 */

namespace Outpost\Images;

use Outpost\Assets\StorageInterface;
use Outpost\Assets\FileInterface;

class MockImage implements ImageInterface {

  public $alt = '';
  public $key;

  public function generate(FileInterface $file, StorageInterface $storage) {

  }

  public function getAlt() {
    return $this->alt;
  }

  public function getKey() {
    return $this->key;
  }

  public function getExtension() {
    // TODO: Implement getExtension() method.
  }
}