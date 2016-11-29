<?php

namespace Outpost\Content\Patterns\People\Directory\Sorting;

use Outpost\Content\Patterns\People\PersonInterface;

interface AlphabeticalComparisonInterface {
  public function getSortableName(PersonInterface $person);
}
