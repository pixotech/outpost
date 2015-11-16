<?php

/**
 * @package Outpost
 * @author Pixo <info@pixotech.com>
 * @copyright 2015, Pixo
 * @license http://opensource.org/licenses/NCSA NCSA
 */

namespace Outpost\Assets\Images\Imagemagick\Geometry;

class Dimensions implements GeometryInterface {

  protected $height;
  protected $width;

  public function __construct($width, $height, $suffix = '') {
    $this->width = $width;
    $this->height = $height;
    $this->suffix = $suffix;
  }

  public function __toString() {
    return $this->toString();
  }

  public function getHeight() {
    return $this->height;
  }

  public function getWidth() {
    return $this->width;
  }

  public function toString() {
    return $this->getWidth() . 'x' . $this->getHeight() . $this->suffix;
  }
}