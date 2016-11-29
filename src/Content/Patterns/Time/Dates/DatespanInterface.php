<?php

namespace Outpost\Content\Patterns\Time\Dates;

use Outpost\Content\Patterns\Time\DurationInterface;

interface DatespanInterface extends DurationInterface {

  /**
   * @return DateInterface
   */
  public function getEnd();

  /**
   * @return DateInterface
   */
  public function getStart();
}
