<?php

/**
 * @package Outpost
 * @author Pixo <info@pixotech.com>
 * @copyright 2015, Pixo
 * @license http://opensource.org/licenses/NCSA NCSA
 */

namespace Outpost\Exceptions;

use Outpost\SiteInterface;
use Symfony\Component\HttpFoundation\Request;

class UnrecognizedRequestException extends \Exception {

  protected $request;
  protected $site;

  public function __construct(SiteInterface $site, Request $request) {
    parent::__construct("Unrecognized request: " . $request->getPathInfo());
    $this->site = $site;
    $this->request = $request;
  }
}