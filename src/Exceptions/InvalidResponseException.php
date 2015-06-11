<?php

/**
 * @package Outpost
 * @author Pixo <info@pixotech.com>
 * @copyright 2015, Pixo
 * @license http://opensource.org/licenses/NCSA NCSA
 */

namespace Outpost\Exceptions;

use Outpost\Recovery\HasDescriptionInterface;
use Outpost\Recovery\HasRepairInterface;
use Outpost\SiteInterface;
use Symfony\Component\HttpFoundation\Request;

class InvalidResponseException extends \UnexpectedValueException implements HasDescriptionInterface, HasRepairInterface {

  public function __construct(SiteInterface $site, Request $request, $response) {
    $this->site = $site;
    $this->request = $request;
    $this->response = $response;
    parent::__construct("Invalid response");
  }

  public function getDescription() {
    return <<<DIAGNOSIS

  <p>A responder returned an invalid response</p>

DIAGNOSIS;
  }

  public function getRepair() {
    $specifics = '';
    if (is_string($this->response)) {
      $specifics = "<p>Use the <code>makeResponse()</code> method to create a Response from a string.</p>";
    }
    return <<<INSTRUCTIONS

  <p>Return a Response object</p>

  $specifics

INSTRUCTIONS;
  }
}