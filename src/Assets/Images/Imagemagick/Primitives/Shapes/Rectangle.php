<?php

/**
 * @package Outpost
 * @author Pixo <info@pixotech.com>
 * @copyright 2015, Pixo
 * @license http://opensource.org/licenses/NCSA NCSA
 */

namespace Outpost\Assets\Images\Imagemagick\Primitives\Shapes;

class Rectangle implements ShapeInterface {

  protected $height;
  protected $width;

  public function __construct($width, $height) {
    $this->width = $width;
    $this->height = $height;
  }

  public function __toString() {
    return $this->toString();
  }

  public function toString() {
    return sprintf("rectangle %s, %s, %s, %s", 0, 0, $this->width, $this->height);
  }
}