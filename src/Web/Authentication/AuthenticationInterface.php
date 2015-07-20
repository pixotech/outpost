<?php

namespace Outpost\Web\Authentication;

interface AuthenticationInterface {
  public function getHeaders();
  public function getQueryVariables();
}