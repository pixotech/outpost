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

class InvalidResponseException extends \UnexpectedValueException implements HasDescriptionInterface {

  public function __construct(SiteInterface $site, Request $request, $response) {
    $this->site = $site;
    $this->request = $request;
    $this->response = $response;
    parent::__construct("Invalid response");
  }

  public function getDescription() {
    return <<<ERROR

  <h1>Invalid response</h1>

  <p>Outpost expects the <code>respond</code> method to return a <a href="http://symfony.com/doc/current/components/http_foundation/introduction.html#response">Response</a> object.</p>

  <pre>
  return new \Symfony\Component\HttpFoundation\Response(\$responseBody);
  </pre>

ERROR;
  }
}