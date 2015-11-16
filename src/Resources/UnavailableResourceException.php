<?php

namespace Outpost\Resources;

class UnavailableResourceException extends \Exception {

  /**
   * @var callable
   */
  protected $resource;

  /**
   * @param callable $resource
   * @param \Exception $exception
   * @param null $message
   */
  public function __construct(callable $resource, \Exception $exception = null, $message = null) {
    if (empty($message)) $message = $exception->getMessage() ?: "Resource unavailable";
    parent::__construct($message, 0, $exception);
    $this->resource = $resource;
  }

  /**
   * @return callable
   */
  public function getResource() {
    return $this->resource;
  }
}