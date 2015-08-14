<?php

/**
 * @package Outpost
 * @author Pixo <info@pixotech.com>
 * @copyright 2015, Pixo
 * @license http://opensource.org/licenses/NCSA NCSA
 */

namespace Outpost;

use Monolog\Handler\TestHandler;
use Outpost\Environments\MockEnvironment;
use Stash\Driver\Ephemeral;

class SiteTest extends \PHPUnit_Framework_TestCase {

  public function testGetSiteCache() {
    $environment = $this->makeEnvironment();
    $site = new TestSite($environment);
    $this->assertInstanceOf("Outpost\\Cache\\Cache", $site->getCache());
  }

  public function testGetSiteCacheCache() {
    $environment = $this->makeEnvironment();
    $site = new TestSite($environment);
    $this->assertInstanceOf("Stash\\Pool", $site->getCache()->getCache());
  }

  public function testGetSiteCacheDriver() {
    $environment = $this->makeEnvironment();
    $site = new TestSite($environment);
    $this->assertSame($environment->cacheDriver, $site->getCache()->getCache()->getDriver());
  }

  public function testGetSiteClient() {
    $environment = $this->makeEnvironment();
    $site = new TestSite($environment);
    $this->assertInstanceOf("Outpost\\Web\\Client", $site->getClient());
  }

  public function testGetSiteLog() {
    $environment = $this->makeEnvironment();
    $site = new TestSite($environment);
    $this->assertInstanceOf("Monolog\\Logger", $site->getLog());
    /** @var \Monolog\Logger $log */
    $log = $site->getLog();
    $this->assertEquals($environment->logHandlers, $log->getHandlers());
  }

  public function testGetResource() {
    $environment = $this->makeEnvironment();
    $site = new TestSite($environment);
    $resourceSite = null;
    $result = "This is the resource result";
    $resource = function ($site) use ($result, &$resourceSite) { $resourceSite = $site; return $result; };
    $this->assertEquals($result, $site->get($resource));
    $this->assertEquals($site, $resourceSite);
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