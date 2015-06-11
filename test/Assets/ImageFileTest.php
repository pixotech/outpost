<?php

namespace Outpost\Assets;

class ImageFileTest extends \PHPUnit_Framework_TestCase {

  public function testGetAlt() {
    $alt = 'alt';
    $file = new ImageFile(null, null, $alt);
    $this->assertEquals($alt, $file->getAlt());
  }
}