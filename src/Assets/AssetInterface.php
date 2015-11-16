<?php

/**
 * @package Outpost
 * @author Pixo <info@pixotech.com>
 * @copyright 2015, Pixo
 * @license http://opensource.org/licenses/NCSA NCSA
 */

namespace Outpost\Assets;

use Outpost\SiteInterface;

interface AssetInterface {
  public function generate(\SplFileInfo $file, AssetManagerInterface $assets);
  public function getExtension();
  public function getKey();
}