<?php

namespace Outpost\Content\Patterns\People\Directory\Filters;

use Outpost\Content\Patterns\People\Directory\DirectoryInterface;

class AlphabeticalFilterIterator extends DirectoryFilterIterator implements AlphabeticalFilterIteratorInterface {

  protected $letter;

  public function __construct(DirectoryInterface $directory, $letter) {
    parent::__construct($directory);
    $this->letter = strtoupper($letter);
  }

  public function accept() {
    $person = $this->current();
    return strtoupper(substr($person->getLastName(), 0, 1)) == $this->getLetter();
  }

  public function getLetter() {
    return $this->letter;
  }
}
