<?php

namespace Outpost\Web\Authentication;

class BasicAuthentication implements AuthenticationInterface {

  protected $password;
  protected $username;

  public function __construct($username, $password) {
    $this->username = $username;
    $this->password = $password;
  }

  public function getHeaders() {
    return ['Authorization' => $this->getHeaderValue()];
  }

  public function getQueryVariables() {
    return [];
  }

  protected function getHeaderValue() {
    return 'Basic ' . base64_encode($this->username . ':' . $this->password);
  }
}