<?php

namespace Outpost\Resources;

use Outpost\MockSite;

class WebResourceTest extends \PHPUnit_Framework_TestCase {

  public function testGetRequest() {
    $url = "http://example.com/";
    $resource = new WebResource($url);
    $this->assertInstanceOf("Outpost\\Web\\Requests\\RequestInterface", $resource->getRequest());
  }

  public function testGetRequestUrl() {
    $url = "http://example.com/";
    $resource = new WebResource($url);
    $this->assertEquals($url, $resource->getRequest()->getRequestUrl());
  }

  public function testGetRequestMethod() {
    $url = "http://example.com/";
    $method = 'POST';
    $resource = new WebResource($url, $method);
    $this->assertEquals($method, $resource->getRequest()->getRequestMethod());
  }

  public function testInvoke() {
    $body = "This is the response body";
    $site = $this->makeSite();
    $site->client->response = $body;
    $url = "http://example.com/";
    $method = 'POST';
    $resource = new WebResource($url, $method);
    $this->assertEquals($body, $resource->invoke($site));
    $this->assertEquals($site->client->request, $resource->getRequest());
  }

  protected function makeSite() {
    $site = new MockSite();
    return $site;
  }
}