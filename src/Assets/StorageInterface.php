<?php

/**
 * @package Outpost
 * @author Pixo <info@pixotech.com>
 * @copyright 2015, Pixo
 * @license http://opensource.org/licenses/NCSA NCSA
 */

namespace Outpost\Assets;

interface StorageInterface {

  /**
   * @param AssetInterface $asset
   * @return FileInterface
   */
  public function getFile(AssetInterface $asset);
}