<?php

namespace Outpost\Content\Patterns\Time\Dates;

use Outpost\Content\Patterns\Time\InstanceInterface;

interface DateInterface extends InstanceInterface {

  /**
   * @return int
   */
  public function getDayOfMonth();

  /**
   * @return MonthInterface
   */
  public function getMonth();
}
