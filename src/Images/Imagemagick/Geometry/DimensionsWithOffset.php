<?php

/**
 * @package Outpost
 * @author Pixo <info@pixotech.com>
 * @copyright 2015, Pixo
 * @license http://opensource.org/licenses/NCSA NCSA
 */

namespace Outpost\Images\Imagemagick\Geometry;

class DimensionsWithOffset extends Dimensions {

  protected $offsetX;
  protected $offsetY;

  public function __construct($width, $height, $offsetX = 0, $offsetY = 0) {
    parent::__construct($width, $height);
    $this->offsetX = $offsetX;
    $this->offsetY = $offsetY;
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
    $offsetX = $this->getSigned($this->offsetX);
    $offsetY = $this->getSigned($this->offsetY);
    return parent::toString() . $offsetX . $offsetY;
  }

  protected function getSigned($num) {
    return ($num < 0 ? '-' : '+') . $num;
  }
}