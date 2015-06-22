<?php

namespace Outpost\Web\Requests;

class FileRequest extends Request {

  protected $localPath;

  public function getRequestOptions() {
    return ['stream' => true];
  }

  public function getRequestUrl() {
    return $this->url;
  }
}