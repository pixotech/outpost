<?php

/**
 * @package Outpost
 * @author Pixo <info@pixotech.com>
 * @copyright 2015, Pixo
 * @license http://opensource.org/licenses/NCSA NCSA
 */

namespace Outpost\Assets;

class ImageFile extends File implements ImageFileInterface {

  protected $alt = '';
  protected $info;

  public function __construct($path, $url, $alt = '') {
    parent::__construct($path, $url);
    $this->alt = $alt;
  }

  public function getAlt() {
    return $this->alt;
  }

  public function getHeight() {
    $info = $this->getInfo();
    return $info[1];
  }

  public function getMimeType() {
    $info = $this->getInfo();
    return $info['mime'];
  }

  public function getWidth() {
    $info = $this->getInfo();
    return $info[0];
  }

  protected function getInfo() {
    if (!isset($this->info)) {
      $this->info = getimagesize($this->getPath());
    }
    return $this->info;
  }
}