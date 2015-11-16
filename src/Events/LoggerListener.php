<?php

namespace Outpost\Events;

use Psr\Log\LoggerInterface;

class LoggerListener implements ListenerInterface {

  /**
   * @var LoggerInterface
   */
  protected $log;

  public function __construct(LoggerInterface $log) {
    $this->log = $log;
  }

  public function __invoke(EventInterface $event) {
    $this->log->log($event->getLogLevel(), $event->getLogMessage());
  }
}