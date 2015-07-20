<?php

namespace Outpost\Events;

interface EventInterface {

  /**
   * @return string
   */
  public function getLogLevel();

  /**
   * @return string
   */
  public function getLogMessage();
}