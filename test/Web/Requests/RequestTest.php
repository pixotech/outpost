<?php

namespace Outpost\Web\Requests;

class RequestTest extends \PHPUnit_Framework_TestCase {

  public function testGetRequestUrl() {
    $url = "http://example.com/";
    $request = new Request($url);
    $this->assertEquals($url, $request->getRequestUrl());
  }

  public function testGetRequestMethod() {
    $method = 'POST';
    $request = new Request(null, $method);
    $this->assertEquals($method, $request->getRequestMethod());
  }
}