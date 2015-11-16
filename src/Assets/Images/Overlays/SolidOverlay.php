<?php

/**
 * @package Outpost
 * @author Pixo <info@pixotech.com>
 * @copyright 2015, Pixo
 * @license http://opensource.org/licenses/NCSA NCSA
 */

namespace Outpost\Assets\Images\Overlays;

use Outpost\Assets\Images\Image;
use Outpost\Assets\Images\Imagemagick\Files\OutputFile;
use Outpost\Assets\Images\Imagemagick\Geometry\Dimensions;

class SolidOverlay extends Overlay {

  protected $color;

  public function __construct($color) {
    $this->color = ltrim($color, '#');
  }

  public function generate(\SplFileInfo $file, $width, $height) {
    $size = new Dimensions($width, $height);
    $color = $this->getColor();
    $output = new OutputFile($file->getPathname(), 'jpeg');
    exec("convert -size $size canvas:$color $output");
  }

  public function getColor() {
    return "#{$this->color}";
  }

  public function getExtension() {
    return 'jpg';
  }

  public function getKey($width, $height) {
    return Image::makeKey($this->color, $width, $height);
  }
}

