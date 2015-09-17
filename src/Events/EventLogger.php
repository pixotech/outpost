<?php

namespace Outpost\Events;

use Outpost\SiteInterface;
use Psr\Log\LoggerInterface;

class EventLogger implements ListenerInterface {

  /**
   * @var LoggerInterface
   */
  protected $log;

  /**
   * @var SiteInterface
   */
  protected $site;

  public function __construct(LoggerInterface $log) {
    $this->log = $log;
  }

  public function handleEvent(EventInterface $event, SiteInterface $site) {
    $this->log->log($event->getLogLevel(), $event->getLogMessage());
  }
}