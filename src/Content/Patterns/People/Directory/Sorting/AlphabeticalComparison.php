<?php

namespace Outpost\Content\Patterns\People\Directory\Sorting;

use Outpost\Content\Patterns\People\PersonInterface;

class AlphabeticalComparison implements AlphabeticalComparisonInterface {

  public function __invoke(PersonInterface $a, PersonInterface $b) {
    return strnatcasecmp($this->getSortableName($a), $this->getSortableName($b));
  }

  public function getSortableName(PersonInterface $person) {
    return implode(' ', [$person->getLastName(), $person->getFirstName()]);
  }
}
