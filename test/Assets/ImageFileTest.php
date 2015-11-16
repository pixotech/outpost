<?php

/**
 * @package Outpost
 * @author Pixo <info@pixotech.com>
 * @copyright 2015, Pixo
 * @license http://opensource.org/licenses/NCSA NCSA
 */

namespace Outpost\Assets;

use Outpost\Assets\Files\ImageFile;

class ImageFileTest extends \PHPUnit_Framework_TestCase {

  public function testGetAlt() {
    $alt = 'alt';
    $file = new ImageFile(null, null, $alt);
    $this->assertEquals($alt, $file->getAlt());
  }
}