<?php

/**
 * @package Outpost
 * @author Pixo <info@pixotech.com>
 * @copyright 2015, Pixo
 * @license http://opensource.org/licenses/NCSA NCSA
 */

namespace Outpost\Assets;

use Outpost\Assets\Files\File;

class FileTest extends \PHPUnit_Framework_TestCase {

  public function testGetPath() {
    $path = 'path';
    $file = new File($path, null);
    $this->assertEquals($path, $file->getPath());
  }

  public function testGetUrl() {
    $url = 'url';
    $file = new File(null, $url);
    $this->assertEquals($url, $file->getUrl());
  }
}