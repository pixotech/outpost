<?php

/**
 * @package Outpost
 * @author Pixo <info@pixotech.com>
 * @copyright 2015, Pixo
 * @license http://opensource.org/licenses/NCSA NCSA
 */

namespace Outpost\Web;

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

  protected function makeCache() {
    $cache = new Pool();
    return $cache;
  }

  protected function makeClient() {
    $client = new \GuzzleHttp\Client();
    return $client;
  }
}