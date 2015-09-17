<?php

namespace Outpost\Events;

use Outpost\SiteInterface;
use Psr\Log\LoggerInterface;

class EventLogger {

  /**
   * @var LoggerInterface
   */
  protected $log;

  /**
   * @var SiteInterface
   */
  protected $site;

  public function __construct(SiteInterface $site, LoggerInterface $log) {
    $this->site = $site;
    $this->log = $log;
  }

  public function handleEvent(EventInterface $event) {
    $this->log->log($event->getLogLevel(), $event->getLogMessage());
  }
}