<?php

namespace Outpost\Web;

use Outpost\Web\ClientInterface;
use Outpost\Web\Requests\RequestInterface;

class MockClient implements ClientInterface {

  public $request;
  public $response;

  /**
   * @return \GuzzleHttp\ClientInterface
   */
  public function getClient() {
    // TODO: Implement getClient() method.
  }

  /**
   * @var RequestInterface $request
   * @return \GuzzleHttp\Message\RequestInterface
   */
  public function makeRequest(RequestInterface $request) {
    return $this->request;
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