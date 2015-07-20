<?php

namespace Outpost\Web;

interface ServiceInterface {

  /**
   * @return \Outpost\Web\Authentication\AuthenticationInterface[]
   */
  public function getAuthentication();

  /**
   * @return array
   */
  public function getRequestHeaders();

  /**
   * @param string $path
   * @return string
   */
  public function makeRequestUrl($path);
}