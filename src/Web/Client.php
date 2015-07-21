<?php

/**
 * @package Outpost
 * @author Pixo <info@pixotech.com>
 * @copyright 2015, Pixo
 * @license http://opensource.org/licenses/NCSA NCSA
 */

namespace Outpost\Web;

use GuzzleHttp\ClientInterface as GuzzleClientInterface;
use Outpost\SiteInterface;
use Outpost\Web\Requests\RequestInterface;

class Client implements ClientInterface {

  /**
   * @var \GuzzleHttp\ClientInterface
   */
  protected $client;

  protected $site;

  /**
   * @param GuzzleClientInterface $client
   */
  public function __construct(SiteInterface $site, GuzzleClientInterface $client) {
    $this->site = $site;
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
   * @return \GuzzleHttp\Message\Request
   */
  public function makeRequest(RequestInterface $request) {
    $method = $request->getRequestMethod();
    $url = $request->getRequestUrl();
    $headers = $request->getRequestHeaders();
    $body = $request->getRequestBody();
    $options = $request->getRequestOptions();
    return new \GuzzleHttp\Message\Request($method, $url, $headers, $body, $options);
  }

  /**
   * @param RequestInterface $request
   * @return mixed
   */
  public function send(RequestInterface $request) {
    $this->site->handleEvent(new NewRequestEvent($request));
    $response = $this->getClientResponse($request);
    $request->validateResponse($response);
    $response = $request->processResponse($response);
    $this->site->handleEvent(new ResponseReceivedEvent($response, $request));
  }

  /**
   * @param RequestInterface $request
   * @return \GuzzleHttp\Message\ResponseInterface
   */
  protected function getClientResponse(RequestInterface $request) {
    return $this->getClient()->send($this->makeRequest($request));
  }
}