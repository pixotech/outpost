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
    $environment = new MockEnvironment();
    new Site($environment);
  }

  public function testGetSiteCache() {
    $environment = new MockEnvironment();
    $environment->cacheDriver = new Ephemeral();
    $site = new Site($environment);
    $this->assertInstanceOf("Stash\\Pool", $site->getCache());
    $this->assertSame($environment->cacheDriver, $site->getCache()->getDriver());
  }

  public function testGetSiteClient() {
    $environment = new MockEnvironment();
    $site = new Site($environment);
    $this->assertInstanceOf("Outpost\\Web\\Client", $site->getClient());
  }

  public function testGetSiteLog() {
    $environment = new MockEnvironment();
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
}