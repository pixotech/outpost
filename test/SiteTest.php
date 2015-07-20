<?php

/**
 * @package Outpost
 * @author Pixo <info@pixotech.com>
 * @copyright 2015, Pixo
 * @license http://opensource.org/licenses/NCSA NCSA
 */

namespace Outpost;

use Monolog\Handler\TestHandler;
use Outpost\Assets\MockAsset;
use Outpost\Environments\MockEnvironment;
use Outpost\Resources\MockCacheableResource;
use Stash\Driver\Ephemeral;

class SiteTest extends \PHPUnit_Framework_TestCase {

  public function testGetAssetMarkerPath() {
    $path = "assetPath";
    $key = "assetKey";
    $environment = $this->makeEnvironment();
    $environment->assetCacheDirectory = $path;
    $site = new Site($environment);
    $this->assertEquals("$path/$key", $site->getAssetMarkerPath($key));
  }

  public function testGetAssetUrl() {
    $key = "assetKey";
    $ext = "ext";
    $environment = $this->makeEnvironment();
    $site = new Site($environment);
    $asset = new MockAsset($key, $ext);
    $this->assertEquals("/_assets/$key.$ext", $site->getAssetUrl($asset));
  }

  public function testGetSiteCache() {
    $environment = $this->makeEnvironment();
    $site = new Site($environment);
    $this->assertInstanceOf("Outpost\\Cache\\Cache", $site->getCache());
  }

  public function testGetSiteCacheCache() {
    $environment = $this->makeEnvironment();
    $site = new Site($environment);
    $this->assertInstanceOf("Stash\\Pool", $site->getCache()->getCache());
  }

  public function testGetSiteCacheDriver() {
    $environment = $this->makeEnvironment();
    $site = new Site($environment);
    $this->assertSame($environment->cacheDriver, $site->getCache()->getCache()->getDriver());
  }

  public function testGetSiteClient() {
    $environment = $this->makeEnvironment();
    $site = new Site($environment);
    $this->assertInstanceOf("Outpost\\Web\\Client", $site->getClient());
  }

  public function testGetSiteLog() {
    $environment = $this->makeEnvironment();
    $site = new Site($environment);
    $this->assertInstanceOf("Monolog\\Logger", $site->getLog());
    /** @var \Monolog\Logger $log */
    $log = $site->getLog();
    $this->assertEquals($environment->logHandlers, $log->getHandlers());
  }

  public function testGetResource() {
    $environment = $this->makeEnvironment();
    $site = new Site($environment);
    $resourceSite = null;
    $result = "This is the resource result";
    $resource = function ($site) use ($result, &$resourceSite) { $resourceSite = $site; return $result; };
    $this->assertEquals($result, $site->get($resource));
    $this->assertEquals($site, $resourceSite);
  }

  public function testGetCacheableResource() {
    $environment = $this->makeEnvironment();
    $site = new Site($environment);
    $result = "This is the resource result";
    $key = "test/key";
    $resource = new MockCacheableResource($result, $key);
    $this->assertEquals($result, $site->get($resource));
  }

  public function testGetCachedResource() {
    $environment = $this->makeEnvironment();
    $site = new Site($environment);
    $result = "This is the uncached result";
    $cached = "This is the cached result";
    $key = "test/key";
    $lifetime = 600;
    $resource = new MockCacheableResource($result, $key, $lifetime);
    $item = $site->getCache()->getCache()->getItem($key);
    $item->set($cached, $lifetime);
    $this->assertEquals($cached, $site->get($resource));
  }

  /**
   * @return MockEnvironment
   */
  protected function makeEnvironment() {
    $environment = new MockEnvironment();
    $environment->cacheDriver = new Ephemeral();
    $environment->logHandlers = [new TestHandler()];
    return $environment;
  }
}