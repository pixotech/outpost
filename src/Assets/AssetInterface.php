<?php

/**
 * @package Outpost
 * @author Pixo <info@pixotech.com>
 * @copyright 2015, Pixo
 * @license http://opensource.org/licenses/NCSA NCSA
 */

namespace Outpost\Assets;

interface AssetInterface {
  public function generate(FileInterface $file, StorageInterface $storage);
  public function getExtension();
  public function getKey();
}