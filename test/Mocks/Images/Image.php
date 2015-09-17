<?php

/**
 * @package Outpost
 * @author Pixo <info@pixotech.com>
 * @copyright 2015, Pixo
 * @license http://opensource.org/licenses/NCSA NCSA
 */

namespace Outpost\Mocks\Images;

use Outpost\Images\ImageInterface;
use Outpost\SiteInterface;

class Image implements ImageInterface {

  public $alt = '';
  public $key;

  public function generate(SiteInterface $site, \SplFileInfo $file) {

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