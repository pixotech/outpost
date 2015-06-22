<?php

/**
 * @package Outpost
 * @author Pixo <info@pixotech.com>
 * @copyright 2015, Pixo
 * @license http://opensource.org/licenses/NCSA NCSA
 */

namespace Outpost\Images;

use Outpost\Web\MockClient;

class RemoteImageTest extends \PHPUnit_Framework_TestCase {

  public function testKey() {
    $client = new MockClient();
    $image1 = new RemoteImage($client, 'http://example.com/not-an-image.jpg');
    $image2 = new RemoteImage($client, 'http://example.com/not-an-image.jpg');
    $this->assertEquals($image1->getKey(), $image2->getKey());
  }

  public function testDifferentKeysWithDifferentUrls() {
    $client = new MockClient();
    $image1 = new RemoteImage($client, 'http://example.com/not-an-image.jpg');
    $image2 = new RemoteImage($client, 'http://example.net/still-not-an-image.jpg');
    $this->assertNotEquals($image1->getKey(), $image2->getKey());
  }
}