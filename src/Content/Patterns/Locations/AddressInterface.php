<?php

namespace Outpost\Content\Patterns\Locations;

interface AddressInterface extends LocationInterface {

  /**
   * @return CityInterface
   */
  public function getCity();

  /**
   * @return string
   */
  public function getStreet();
}
