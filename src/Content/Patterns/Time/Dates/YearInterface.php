<?php

namespace Outpost\Content\Patterns\Time\Dates;

interface YearInterface {

  /**
   * @return bool
   */
  public function isLeapYear();

  /**
   * @return int
   */
  public function getNumber();
}
