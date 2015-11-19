<?php

namespace Outpost\Assets\Storage;

class PublicStorage extends Storage {

  protected $url;

  public function __construct($path, $url) {
    parent::__construct($path);
    $this->url = $url;
  }

  /**
   * @return string
   */
  public function getUrl() {
    return $this->url;
  }

  /**
   * @param string $url
   */
  public function setUrl($url) {
    $this->url = $url;
  }

  protected function makeUrl($path) {
    $delimiter = '/';
    return rtrim($this->url, $delimiter) . $delimiter . $path;
  }
}