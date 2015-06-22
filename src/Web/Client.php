<?php

/**
 * @package Outpost
 * @author Pixo <info@pixotech.com>
 * @copyright 2015, Pixo
 * @license http://opensource.org/licenses/NCSA NCSA
 */

namespace Outpost\Web;

use GuzzleHttp\ClientInterface as GuzzleClientInterface;
use Outpost\Web\Requests\CacheableRequestInterface;
use Outpost\Web\Requests\RequestInterface;
use Stash\Interfaces\PoolInterface;

class Client implements ClientInterface {

  /**
   * @var \Stash\Interfaces\PoolInterface
   */
  protected $cache;

  /**
   * @var \GuzzleHttp\ClientInterface
   */
  protected $client;

  /**
   * @param GuzzleClientInterface $client
   * @param PoolInterface $cache
   */
  public function __construct(GuzzleClientInterface $client, PoolInterface $cache) {
    $this->client = $client;
    $this->cache = $cache;
  }

  /**
   * @return PoolInterface
   */
  public function getCache() {
    return $this->cache;
  }

  /**
   * @param CacheableRequestInterface $request
   * @return mixed
   */
  public function getCachedResponse(CacheableRequestInterface $request) {
    $cached = $this->getCache()->getItem($this->makeRequestCacheKey($request));
    $response = $cached->get();
    if ($cached->isMiss()) {
      $cached->lock();
      $response = $this->getResponse($request);
      $cached->set($response, $request->getCacheLifetime());
    }
    return $response;
  }

  /**
   * @return string
   */
  public function getCacheNamespace() {
    return 'http';
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
  public function getResponse(RequestInterface $request) {
    $response = $this->getClient()->send($this->makeClientRequest($request));
    $request->validateResponse($response);
    return $request->processResponse($response);
  }

  /**
   * @param RequestInterface $request
   * @return bool
   */
  public function isCacheableRequest(RequestInterface $request) {
    return $request instanceof CacheableRequestInterface;
  }

  /**
   * @param RequestInterface $request
   * @return \GuzzleHttp\Message\Request
   */
  public function makeClientRequest(RequestInterface $request) {
    $method = $request->getRequestMethod();
    $url = $request->getRequestUrl();
    $headers = $request->getRequestHeaders();
    $body = $request->getRequestBody();
    $options = $request->getRequestOptions();
    return new \GuzzleHttp\Message\Request($method, $url, $headers, $body, $options);
  }

  /**
   * @param CacheableRequestInterface $request
   * @return string
   */
  public function makeRequestCacheKey(CacheableRequestInterface $request) {
    return $this->getCacheNamespace() . '/' . $request->getCacheKey();
  }

  /**
   * @param RequestInterface $request
   * @return mixed
   */
  public function send(RequestInterface $request) {
    return $this->isCacheableRequest($request) ? $this->getCachedResponse($request) : $this->getResponse($request);
  }
}