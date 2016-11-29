<?php

namespace Outpost\Content\Patterns\Locations;

interface CityInterface extends LocationInterface {

  /**
   * @return string
   */
  public function getName();

  /**
   * @return StateInterface
   */
  public function getState();
}
