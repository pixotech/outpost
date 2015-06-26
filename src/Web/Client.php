<?php

/**
 * @package Outpost
 * @author Pixo <info@pixotech.com>
 * @copyright 2015, Pixo
 * @license http://opensource.org/licenses/NCSA NCSA
 */

namespace Outpost\Web;

use GuzzleHttp\ClientInterface as GuzzleClientInterface;
use Outpost\Web\Requests\RequestInterface;

class Client implements ClientInterface {

  /**
   * @var \GuzzleHttp\ClientInterface
   */
  protected $client;

  /**
   * @param GuzzleClientInterface $client
   */
  public function __construct(GuzzleClientInterface $client) {
    $this->client = $client;
  }

  /**
   * @return GuzzleClientInterface
   */
  public function getClient() {
    return $this->client;
  }

  /**
   * @param RequestInterface $request
   * @return mixed
   */
  public function send(RequestInterface $request) {
    $response = $this->getClientResponse($request);
    $request->validateResponse($response);
    return $request->processResponse($response);
  }

  /**
   * @param RequestInterface $request
   * @return \GuzzleHttp\Message\ResponseInterface
   */
  protected function getClientResponse(RequestInterface $request) {
    return $this->getClient()->send($this->makeClientRequest($request));
  }

  /**
   * @param RequestInterface $request
   * @return \GuzzleHttp\Message\Request
   */
  protected function makeClientRequest(RequestInterface $request) {
    $method = $request->getRequestMethod();
    $url = $request->getRequestUrl();
    $headers = $request->getRequestHeaders();
    $body = $request->getRequestBody();
    $options = $request->getRequestOptions();
    return new \GuzzleHttp\Message\Request($method, $url, $headers, $body, $options);
  }
}