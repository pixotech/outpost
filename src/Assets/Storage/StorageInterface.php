<?php

/**
 * @package Outpost
 * @author Pixo <info@pixotech.com>
 * @copyright 2015, Pixo
 * @license http://opensource.org/licenses/NCSA NCSA
 */

namespace Outpost\Assets\Storage;

use Outpost\Assets\AssetInterface;
use Outpost\Assets\Files\FileInterface;

interface StorageInterface {

  /**
   * @param AssetInterface $asset
   * @return FileInterface
   */
  public function getFile(AssetInterface $asset);
}