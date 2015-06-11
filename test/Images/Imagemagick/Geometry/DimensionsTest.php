<?php

namespace Outpost\Images\Imagemagick\Geometry;

class DimensionsTest extends \PHPUnit_Framework_TestCase {

  public function testString() {
    $dimensions = new Dimensions(100, 200);
    $this->assertEquals("100x200", $dimensions->toString());
  }
}