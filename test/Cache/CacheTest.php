<?php

namespace Outpost\Cache;

use Outpost\Mocks\Site;
use Stash\Driver\Ephemeral;

class CacheTest extends \PHPUnit_Framework_TestCase {

  public function testGet() {
    $key = "This is the cache key";
    $content = "This is the uncached content";
    $wasCalled = false;
    $callback = function () use (&$wasCalled, $content) { $wasCalled = true; return $content; };
    $cache = new Cache($this->makeSite(), $this->makeCacheDriver());
    $this->assertEquals($content, $cache->get($key, $callback));
    $this->assertTrue($wasCalled);
  }

  public function testGetCached() {
    $key = "This is the cache key";
    $cachedContent = "This is the old content";
    $wasCalled = false;
    $callback = function () use (&$wasCalled) { $wasCalled = true; };
    $cache = new Cache($this->makeSite(), $this->makeCacheDriver());
    $cached = $cache->getCache()->getItem($key);
    $cached->set($cachedContent);
    $this->assertEquals($cachedContent, $cache->get($key, $callback));
    $this->assertFalse($wasCalled);
  }

  public function testGetCache() {
    $cache = new Cache($this->makeSite(), $this->makeCacheDriver());
    $this->assertInstanceOf("Stash\\Pool", $cache->getCache());
  }

  protected function makeCacheDriver() {
    return new Ephemeral();
  }

  protected function makeSite() {
    return new Site();
  }
}