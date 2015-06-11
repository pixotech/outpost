<?php

/**
 * @package Outpost
 * @author Pixo <info@pixotech.com>
 * @copyright 2015, Pixo
 * @license http://opensource.org/licenses/NCSA NCSA
 */

namespace Outpost\Images\Overlays;

use Outpost\Assets\FileInterface;
use Outpost\Images\Image;
use Outpost\Images\Imagemagick\Files\OutputFile;
use Outpost\Images\Imagemagick\Geometry\Dimensions;

class SolidOverlay extends Overlay {

  protected $color;

  public function __construct($color) {
    $this->color = ltrim($color, '#');
  }

  public function generate(FileInterface $file, $width, $height) {
    $size = new Dimensions($width, $height);
    $color = $this->getColor();
    $output = new OutputFile($file->getPath(), 'jpeg');
    exec("convert -size $size canvas:$color $output");
  }

  public function getKey($width, $height) {
    return Image::makeKey($this->color, $width, $height);
  }

  public function getColor() {
    return "#{$this->color}";
  }
}

