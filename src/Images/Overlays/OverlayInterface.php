<?php

/**
 * @package Outpost
 * @author Pixo <info@pixotech.com>
 * @copyright 2015, Pixo
 * @license http://opensource.org/licenses/NCSA NCSA
 */

namespace Outpost\Images\Overlays;

use Outpost\Assets\FileInterface;

interface OverlayInterface {
  public function generate(FileInterface $file, $width, $height);
  public function getExtension();
  public function getKey($width, $height);
}