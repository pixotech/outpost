<?php

namespace Outpost\Content\Patterns\People;

use Outpost\Content\Patterns\Collections\ItemInterface;

interface PersonInterface extends ItemInterface {

  /**
   * @return string
   */
  public function getFirstName();

  /**
   * @return string
   */
  public function getLastName();
}
