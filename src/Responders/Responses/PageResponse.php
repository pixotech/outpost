<?php

namespace Outpost\Responders\Responses;

use Outpost\Html\Document;

class PageResponse implements PageResponseInterface {

  protected $document;

  public function __construct($body, $title) {
    $this->document = new Document($body, $title);
  }

  public function getContent() {
    return (string)$this->document;
  }

  public function getDocument() {
    return $this->document;
  }

  public function getHeaders() {
    return [];
  }

  public function getStatusCode() {
    return 200;
  }
}