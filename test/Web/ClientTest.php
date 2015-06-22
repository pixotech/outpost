<?php

/**
 * @package Outpost
 * @author Pixo <info@pixotech.com>
 * @copyright 2015, Pixo
 * @license http://opensource.org/licenses/NCSA NCSA
 */

namespace Outpost\Web;

use GuzzleHttp\Stream\NullStream;
use Outpost\Web\Requests\MockCacheableRequest;
use Outpost\Web\Requests\MockRequest;
use Stash\Pool;

class ClientTest extends \PHPUnit_Framework_TestCase {

  public function testCreateClient() {
    new Client($this->makeClient(), $this->makeCache());
  }

  public function testGetClient() {
    $client = $this->makeClient();
    $web = new Client($client, $this->makeCache());
    $this->assertSame($client, $web->getClient());
  }

  public function testGetCache() {
    $cache = $this->makeCache();
    $web = new Client($this->makeClient(), $cache);
    $this->assertSame($cache, $web->getCache());
  }

  public function testGetCachedResponse() {
    $request = new MockCacheableRequest();
    $request->cacheKey = 'this is the cache key';
    $cache = $this->makeCache();
    $item = $cache->getItem("http/{$request->cacheKey}");
    $data = 'this is the cache data';
    $item->set($data);
    $web = new Client($this->makeClient(), $cache);
    $this->assertEquals($data, $web->getCachedResponse($request));
  }

  public function testGetCacheNamespace() {
    $web = new Client($this->makeClient(), $this->makeCache());
    $this->assertSame('http', $web->getCacheNamespace());
  }

  public function testMakeClientRequest() {
    $web = new Client($this->makeClient(), $this->makeCache());
    $request = new MockRequest();
    $request->body = new NullStream();
    $request->headers = ['X-Header-Key' => 'header value'];
    $request->method = 'GET';
    $request->url = 'http://example.com/request.html';
    $clientRequest = $web->makeClientRequest($request);
    $this->assertInstanceOf("GuzzleHttp\\Message\\Request", $clientRequest);
    $this->assertEquals($request->body, $clientRequest->getBody());
    $this->assertEquals($request->headers['X-Header-Key'], $clientRequest->getHeader('X-Header-Key'));
    $this->assertEquals($request->method, $clientRequest->getMethod());
    $this->assertEquals($request->url, $clientRequest->getUrl());
  }

  public function testGetMakeRequestCacheKey() {
    $web = new Client($this->makeClient(), $this->makeCache());
    $request = new MockCacheableRequest();
    $request->cacheKey = 'this is the cache key';
    $this->assertSame("http/{$request->cacheKey}", $web->makeRequestCacheKey($request));
  }

  protected function makeCache() {
    $cache = new Pool();
    return $cache;
  }

  protected function makeClient() {
    $client = new \GuzzleHttp\Client();
    return $client;
  }
}