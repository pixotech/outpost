<?php

/**
 * @package Outpost
 * @author Pixo <info@pixotech.com>
 * @copyright 2015, Pixo
 * @license http://opensource.org/licenses/NCSA NCSA
 */

namespace Outpost\Assets;

class File implements FileInterface {

  public function __construct($path, $url) {
    $this->path = $path;
    $this->url = $url;
  }

  public function exists() {
    return file_exists($this->path);
  }

  public function getPath() {
    return $this->path;
  }

  public function getUrl() {
    return $this->url;
  }
}