<?php

namespace Outpost\Content\Patterns\Locations;

interface StateInterface extends LocationInterface {

  /**
   * @return string
   */
  public function getAbbreviation();

  /**
   * @return string
   */
  public function getName();
}
