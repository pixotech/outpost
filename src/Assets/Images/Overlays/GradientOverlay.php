<?php

/**
 * @package Outpost
 * @author Pixo <info@pixotech.com>
 * @copyright 2015, Pixo
 * @license http://opensource.org/licenses/NCSA NCSA
 */

namespace Outpost\Assets\Images\Overlays;

use Outpost\Assets\Images\Imagemagick\Files\OutputFile;
use Outpost\Assets\Images\Imagemagick\Geometry\Dimensions;

abstract class GradientOverlay extends Overlay {

  public function generate(\SplFileInfo $file, $width, $height) {
    $size = new Dimensions($width, $height);
    $gradient = $this->getGradient();
    $output = new OutputFile($file->getPathname(), 'jpeg');
    $command = "convert -size $size $gradient $output";
    exec($command);
  }

  abstract public function getGradient();

  public function getExtension() {
    return 'jpg';
  }
}

