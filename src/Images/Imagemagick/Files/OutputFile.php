<?php

/**
 * @package Outpost
 * @author Pixo <info@pixotech.com>
 * @copyright 2015, Pixo
 * @license http://opensource.org/licenses/NCSA NCSA
 */

namespace Outpost\Images\Imagemagick\Files;

class OutputFile implements OutputInterface {

  protected $path;
  protected $type;

  public function __construct($path, $type = null) {
    $this->path = $path;
    $this->type = $type;
  }

  public function __toString() {
    return $this->toString();
  }

  public function toString() {
    return $this->getPrefix() . $this->path;
  }

  public function getPrefix() {
    return isset($this->type) ? "{$this->type}:" : '';
  }
}