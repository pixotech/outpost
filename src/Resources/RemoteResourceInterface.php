<?php

namespace Outpost\Resources;

interface RemoteResourceInterface extends ResourceInterface {

  /**
   * @return \GuzzleHttp\Message\RequestInterface
   */
  public function getRequest();
}