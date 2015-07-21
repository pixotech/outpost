<?php

namespace Outpost\Events;

interface EventInterface {

  public function getColor();

  /**
   * @return string
   */
  public function getLocation();

  /**
   * @return string
   */
  public function getLogLevel();

  /**
   * @return string
   */
  public function getLogMessage();

  /**
   * @return \DateTime
   */
  public function getTime();
}