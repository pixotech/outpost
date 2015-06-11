<?php

/**
 * @package Outpost
 * @author Pixo <info@pixotech.com>
 * @copyright 2015, Pixo
 * @license http://opensource.org/licenses/NCSA NCSA
 */

namespace Outpost\Exceptions;

use Outpost\SiteInterface;

class UnknownEnvironmentException extends \Exception {

  protected $pathsChecked;
  protected $site;

  public function __construct(SiteInterface $site, array $pathsChecked) {
    parent::__construct("Unknown environment");
    $this->site = $site;
    $this->pathsChecked = $pathsChecked;
  }
}