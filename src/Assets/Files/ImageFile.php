<?php

/**
 * @package Outpost
 * @author Pixo <info@pixotech.com>
 * @copyright 2015, Pixo
 * @license http://opensource.org/licenses/NCSA NCSA
 */

namespace Outpost\Assets\Files;

class ImageFile extends LocalFile implements ImageFileInterface {

  protected $alt = '';
  protected $info;

  public function __construct($path, $url, $alt = '') {
    parent::__construct($path, $url);
    $this->alt = $alt;
    $this->info = getimagesize($this->getPath());
  }

  public function getAlt() {
    return $this->alt;
  }

  public function getHeight() {
    return $this->info[1];
  }

  public function getMimeType() {
    return $this->info['mime'];
  }

  public function getWidth() {
    return $this->info[0];
  }

  protected function getInfo() {
    return $this->info;
  }
}