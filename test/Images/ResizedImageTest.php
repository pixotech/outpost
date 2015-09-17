<?php

/**
 * @package Outpost
 * @author Pixo <info@pixotech.com>
 * @copyright 2015, Pixo
 * @license http://opensource.org/licenses/NCSA NCSA
 */

namespace Outpost\Images;

use Outpost\Mocks\Images\Image;

class ResizedImageTest extends \PHPUnit_Framework_TestCase {

  public function testKey() {
    $image = new Image();
    $width = 200;
    $height = 200;
    $resized1 = new ResizedImage($image, $width, $height);
    $resized2 = new ResizedImage($image, $width, $height);
    $this->assertEquals($resized1->getKey(), $resized2->getKey());
  }
}