<?php

/**
 * @package Outpost
 * @author Pixo <info@pixotech.com>
 * @copyright 2015, Pixo
 * @license http://opensource.org/licenses/NCSA NCSA
 */

namespace Outpost\Images\Overlays;

interface OverlayInterface {
  public function generate(\SplFileInfo $file, $width, $height);
  public function getExtension();
  public function getKey($width, $height);
}