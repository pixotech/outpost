<?php

namespace Outpost\Content\Patterns\Time\Dates;

interface MonthInterface {

  /**
   * @param int $number
   * @return DateInterface
   */
  public function getDay($number);

  /**
   * @param string $format
   * @return string
   */
  public function getName($format = 'F');

  /**
   * @return int
   */
  public function getNumber();

  /**
   * @return int
   */
  public function getNumberOfDays();

  /**
   * @return YearInterface
   */
  public function getYear();
}
