<?php

namespace Outpost\Content\Patterns\People\Names;

use Outpost\Content\Patterns\People\Directory\Sorting\AlphabeticalComparison;

class Name implements NameInterface {

  /**
   * @var string
   */
  protected $first;

  /**
   * @var string
   */
  protected $last;

  /**
   * @return callable
   */
  public static function cmp() {
    return new AlphabeticalComparison();
  }

  /**
   * @param array $name
   */
  public function __construct(array $name) {
    $this->first = $name['first'];
    $this->last = $name['last'];
  }

  public function __toString() {
    return $this->getFull();
  }

  /**
   * @return string
   */
  public function getFirst() {
    return $this->first;
  }

  /**
   * @return string
   */
  public function getFull() {
    return implode(' ', [$this->first, $this->last]);
  }

  /**
   * @return string
   */
  public function getLast() {
    return $this->last;
  }
}
