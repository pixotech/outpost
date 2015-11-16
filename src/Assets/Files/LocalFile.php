<?php

namespace Outpost\Assets\Files;

class LocalFile extends File implements LocalFileInterface {

  protected $path;

  public function __construct($path, $url) {
    parent::__construct($url);
    $this->path = $path;
  }

  public function exists() {
    return file_exists($this->path);
  }

  public function getPath() {
    return $this->path;
  }
}