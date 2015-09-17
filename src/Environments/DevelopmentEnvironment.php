<?php

/**
 * @package Outpost
 * @author Pixo <info@pixotech.com>
 * @copyright 2015, Pixo
 * @license http://opensource.org/licenses/NCSA NCSA
 */

namespace Outpost\Environments;

class DevelopmentEnvironment extends Environment {

  public function configureErrorReporting() {
    $this->showErrors();
  }

  public function getCacheDriver() {
    return $this->makeLocalCacheDriver("cache/outpost");
  }
}