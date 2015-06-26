<?php

namespace Outpost\Resources;

use Outpost\SiteInterface;
use Outpost\Web\Requests\Request;

class WebResource implements WebResourceInterface {

  protected $method;
  protected $url;

  public function __construct($url, $method = 'GET') {
    $this->url = $url;
    $this->method = $method;
  }

  public function getRequestMethod() {
    return $this->method;
  }

  /**
   * @return \Outpost\Web\Requests\Request
   */
  public function getRequest() {
    return new Request($this->getRequestUrl(), $this->getRequestMethod());
  }

  public function getRequestUrl() {
    return $this->url;
  }

  public function invoke(SiteInterface $site) {
    return $site->getClient()->send($this->getRequest());
  }
}