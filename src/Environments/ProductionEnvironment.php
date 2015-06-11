<?php

/**
 * @package Outpost
 * @author Pixo <info@pixotech.com>
 * @copyright 2015, Pixo
 * @license http://opensource.org/licenses/NCSA NCSA
 */

namespace Outpost\Environments;

class ProductionEnvironment extends Environment {

  protected function ensureDirectory($dir) {
    if (!is_dir($dir)) throw new \Exception("Not a directory: $dir");
  }
}