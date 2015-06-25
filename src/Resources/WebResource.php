<?php

namespace Outpost\Resources;

use Outpost\SiteInterface;

class WebResource implements WebResourceInterface {

  protected $method;
  protected $url;

  public function __construct($url, $method = 'GET') {
    $this->url = $url;
    $this->method = $method;
  }

  /**
   * @return \Outpost\Web\Requests\Request
   */
  public function getRequest() {
    return new \Outpost\Web\Requests\Request($this->url, $this->method);
  }

  public function invoke(SiteInterface $site) {
    return $site->getClient()->send($this->getRequest());
  }
}