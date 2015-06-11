<?php

namespace Outpost\Images;

class ResizedImageTest extends \PHPUnit_Framework_TestCase {

  public function testKey() {
    $image = new MockImage();
    $width = 200;
    $height = 200;
    $resized1 = new ResizedImage($image, $width, $height);
    $resized2 = new ResizedImage($image, $width, $height);
    $this->assertEquals($resized1->getKey(), $resized2->getKey());
  }
}