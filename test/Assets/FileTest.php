<?php

namespace Outpost\Assets;

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