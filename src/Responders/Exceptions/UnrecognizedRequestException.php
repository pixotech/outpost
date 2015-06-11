<?php

/**
 * @package Outpost
 * @author Pixo <info@pixotech.com>
 * @copyright 2015, Pixo
 * @license http://opensource.org/licenses/NCSA NCSA
 */

namespace Outpost\Responders\Exceptions;

use Outpost\Recovery\HasDescriptionInterface;
use Outpost\Recovery\HasRepairInterface;

class UnrecognizedRequestException extends \Exception implements HasDescriptionInterface, HasRepairInterface {

  public function getDescription() {
    return <<<DIAGNOSIS

  <p>Could not recognize the request</p>

DIAGNOSIS;
  }

  public function getRepair() {
    return <<<INSTRUCTIONS

  <p>Add a new responder</p>

INSTRUCTIONS;
  }
}