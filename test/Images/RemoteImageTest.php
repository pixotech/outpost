<?php

namespace Outpost\Images;

class RemoteImageTest extends \PHPUnit_Framework_TestCase {

  public function testKey() {
    $image1 = new RemoteImage('http://example.com/not-an-image.jpg');
    $image2 = new RemoteImage('http://example.com/not-an-image.jpg');
    $this->assertEquals($image1->getKey(), $image2->getKey());
  }

  public function testDifferentKeysWithDifferentUrls() {
    $image1 = new RemoteImage('http://example.com/not-an-image.jpg');
    $image2 = new RemoteImage('http://example.net/still-not-an-image.jpg');
    $this->assertNotEquals($image1->getKey(), $image2->getKey());
  }
}