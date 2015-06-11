<?php

/**
 * @package Outpost
 * @author Pixo <info@pixotech.com>
 * @copyright 2015, Pixo
 * @license http://opensource.org/licenses/NCSA NCSA
 */

namespace Outpost\Environments\Exceptions;

class InvalidPathException extends \Exception {

  protected $path;

  public function __construct($path) {
    $this->path = $path;
    parent::__construct("Invalid path: $path");
  }
}