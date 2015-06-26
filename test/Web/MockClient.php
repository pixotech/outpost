<?php

namespace Outpost\Web;

use Outpost\Web\ClientInterface;
use Outpost\Web\Requests\RequestInterface;

class MockClient implements ClientInterface {

  public $request;
  public $response;

  /**
   * @return \Stash\Interfaces\PoolInterface
   */
  public function getCache() {
    // TODO: Implement getCache() method.
  }

  /**
   * @return \GuzzleHttp\ClientInterface
   */
  public function getClient() {
    // TODO: Implement getClient() method.
  }

  /**
   * @var RequestInterface $request
   * @return mixed
   */
  public function send(RequestInterface $request) {
    $this->request = $request;
    return $this->response;
  }
}