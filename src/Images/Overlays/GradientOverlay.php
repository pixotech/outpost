<?php

/**
 * @package Outpost
 * @author Pixo <info@pixotech.com>
 * @copyright 2015, Pixo
 * @license http://opensource.org/licenses/NCSA NCSA
 */

namespace Outpost\Images\Overlays;

use Outpost\Assets\FileInterface;
use Outpost\Images\Imagemagick\Files\OutputFile;
use Outpost\Images\Imagemagick\Geometry\Dimensions;

abstract class GradientOverlay extends Overlay {

  public function generate(FileInterface $file, $width, $height) {
    $size = new Dimensions($width, $height);
    $gradient = $this->getGradient();
    $output = new OutputFile($file->getPath(), 'jpeg');
    $command = "convert -size $size $gradient $output";
    exec($command);
  }

  abstract public function getGradient();

  public function getExtension() {
    return 'jpg';
  }
}

