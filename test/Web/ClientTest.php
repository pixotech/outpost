<?php

/**
 * @package Outpost
 * @author Pixo <info@pixotech.com>
 * @copyright 2015, Pixo
 * @license http://opensource.org/licenses/NCSA NCSA
 */

namespace Outpost\Web;

use GuzzleHttp\Stream\NullStream;
use Outpost\Web\Requests\MockRequest;

class ClientTest extends \PHPUnit_Framework_TestCase {

  public function testCreateClient() {
    new Client($this->makeClient());
  }

  public function testGetClient() {
    $client = $this->makeClient();
    $web = new Client($client);
    $this->assertSame($client, $web->getClient());
  }

  protected function makeClient() {
    $client = new \GuzzleHttp\Client();
    return $client;
  }
}