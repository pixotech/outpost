<?php

/**
 * @package Outpost
 * @author Pixo <info@pixotech.com>
 * @copyright 2015, Pixo
 * @license http://opensource.org/licenses/NCSA NCSA
 */

namespace Outpost\Web;

use Outpost\MockSite;

class ClientTest extends \PHPUnit_Framework_TestCase {

  public function testCreateClient() {
    new Client($this->makeSite(), $this->makeClient());
  }

  public function testGetClient() {
    $client = $this->makeClient();
    $web = new Client($this->makeSite(), $client);
    $this->assertSame($client, $web->getClient());
  }

  protected function makeClient() {
    $client = new \GuzzleHttp\Client();
    return $client;
  }

  protected function makeSite() {
    $site = new MockSite();
    return $site;
  }
}