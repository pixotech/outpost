<?php

/**
 * @package Outpost
 * @author Pixo <info@pixotech.com>
 * @copyright 2015, Pixo
 * @license http://opensource.org/licenses/NCSA NCSA
 */

namespace Outpost\Assets\Images\Imagemagick\Geometry;

class DimensionsTest extends \PHPUnit_Framework_TestCase {

  public function testString() {
    $dimensions = new Dimensions(100, 200);
    $this->assertEquals("100x200", $dimensions->toString());
  }
}