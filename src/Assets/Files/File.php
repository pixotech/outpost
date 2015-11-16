<?php

/**
 * @package Outpost
 * @author Pixo <info@pixotech.com>
 * @copyright 2015, Pixo
 * @license http://opensource.org/licenses/NCSA NCSA
 */

namespace Outpost\Assets\Files;

class File implements FileInterface {

  public function __construct($url) {
    $this->url = $url;
  }

  public function getExtension() {
    return pathinfo($this->getUrl(), PATHINFO_EXTENSION);
  }

  public function getUrl() {
    return $this->url;
  }
}