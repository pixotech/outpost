<?php

namespace Outpost\Content\Patterns\People\Directory;

use Outpost\Content\Patterns\People\PersonInterface;

interface DirectoryInterface extends \IteratorAggregate {
  public function add(PersonInterface $person);
  public function count();
}
