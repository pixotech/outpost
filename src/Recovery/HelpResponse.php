<?php

/**
 * @package Outpost
 * @author Pixo <info@pixotech.com>
 * @copyright 2015, Pixo
 * @license http://opensource.org/licenses/NCSA NCSA
 */

namespace Outpost\Recovery;

use Symfony\Component\HttpFoundation\Response;

class HelpResponse extends Response {

  public function __construct(\Exception $e, $status = 500, $headers = array()) {
    $page = new HelpPage($e);
    parent::__construct((string)$page, $status, $headers);
  }
}