<?php

/**
 * @package Outpost
 * @author Pixo <info@pixotech.com>
 * @copyright 2015, Pixo
 * @license http://opensource.org/licenses/NCSA NCSA
 */

namespace Outpost\Assets\Images\Overlays;

use Outpost\Assets\Images\Image;
use Outpost\Assets\Images\Imagemagick\Files\Pseudoimages\Gradient;

class VerticalGradientOverlay extends GradientOverlay {

  protected $color1;
  protected $color2;

  public function __construct($topColor, $bottomColor) {
    $this->color1 = ltrim($topColor, '#');
    $this->color2 = ltrim($bottomColor, '#');
  }

  public function getKey($width, $height) {
    return Image::makeKey($this->color1, $this->color2, $width, $height);
  }

  public function getGradient() {
    return new Gradient($this->color1, $this->color2);
  }
}

