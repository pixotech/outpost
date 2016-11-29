<?php

namespace Outpost\Content\Patterns\People\Directory;

use Outpost\Content\Patterns\People\Directory\Sorting\AlphabeticalComparison;
use Outpost\Content\Patterns\People\PersonInterface;

class Directory implements \Countable, \JsonSerializable, DirectoryInterface {

  protected $people;

  public function add(PersonInterface $person) {
    $this->people[] = $person;
    $this->sort();
  }

  public function count() {
    return count($this->people);
  }

  /**
   * @return \ArrayIterator
   */
  public function getIterator() {
    return new \ArrayIterator($this->people);
  }

  public function jsonSerialize() {
    return $this->people;
  }

  protected function sort() {
    usort($this->people, new AlphabeticalComparison());
  }
}
