<?php

namespace Outpost\Resources;

use GuzzleHttp\Message\ResponseInterface;

interface WebResourceInterface extends ResourceInterface {

  /**
   * @return \GuzzleHttp\Message\RequestInterface
   */
  public function getRequest();
}