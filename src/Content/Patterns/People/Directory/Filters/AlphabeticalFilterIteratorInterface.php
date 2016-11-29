<?php

namespace Outpost\Content\Patterns\People\Directory\Filters;

interface AlphabeticalFilterIteratorInterface extends DirectoryFilterIteratorInterface{
  public function getLetter();
}
