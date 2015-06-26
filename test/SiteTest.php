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

  public function testCreateSite() {
    $environment = $this->makeEnvironment();
    new Site($environment);
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
    $environment->logHandlers = [new TestHandler()];
    $site = new Site($environment);
    $this->assertInstanceOf("Monolog\\Logger", $site->getLog());
    /** @var \Monolog\Logger $log */
    $log = $site->getLog();
    $this->assertEquals($environment->logHandlers, $log->getHandlers());
  }

  protected function makeTestSiteDirectory() {

  }

  protected function makeTestSiteDirectoryName() {
    return uniqid('outpost-testsite-');
  }

  /**
   * @return MockEnvironment
   */
  protected function makeEnvironment() {
    $environment = new MockEnvironment();
    $environment->cacheDriver = new Ephemeral();
    return $environment;
  }
}