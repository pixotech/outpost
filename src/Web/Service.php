<?php

namespace Outpost\Web;

use Outpost\Web\Authentication\AuthenticationInterface;

class Service implements ServiceInterface {

  protected $authentication = [];
  protected $urlPrefix;

  public function __construct($urlPrefix, array $authentication = []) {
    $this->urlPrefix = $urlPrefix;
    foreach ($authentication as $auth) {
      if (!($auth instanceof AuthenticationInterface)) {
        throw new \UnexpectedValueException("Invalid authentication");
      }
      $this->authentication[] = $auth;
    }
  }

  public function getAuthentication() {
    return $this->authentication;
  }

  public function getRequestHeaders() {
    return [];
  }

  public function makeRequestUrl($path) {
    return $this->urlPrefix . $path;
  }
}