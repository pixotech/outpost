<?php

namespace Outpost\Content\Patterns\People\Directory\Filters;

interface DirectoryFilterIteratorInterface {

  /**
   * @return \Outpost\Content\Patterns\People\Directory\DirectoryInterface
   */
  public function getDirectory();
}
