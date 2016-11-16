<?php

namespace Outpost\Generator;

use Outpost\Generator\Files\FileEvent;

class Report implements \IteratorAggregate
{
    protected $events = [];
    protected $startTime;
    protected $stopTime;

    public function __construct($autostart = true)
    {
        if ($autostart) $this->start();
    }

    public function __toString()
    {
        return $this->summary();
    }

    public function addEvent(EventInterface $event)
    {
        $this->events[] = $event;
    }

    public function getDuration()
    {
        return $this->stopTime - $this->startTime;
    }

    public function getIterator()
    {
        return $this->events;
    }

    public function getDeletedCount()
    {
        return $this->getFilteredEventCount(function (FileEvent $e) {
            return $e->wasDeleted();
        });
    }

    public function getSkippedCount()
    {
        return $this->getFilteredEventCount(function (FileEvent $e) {
            return $e->wasSkipped();
        });
    }

    public function getUpdatedCount()
    {
        return $this->getFilteredEventCount(function (FileEvent $e) {
            return $e->wasUpdated();
        });
    }

    public function start()
    {
        $this->startTime = microtime(true);
    }

    public function stop()
    {
        $this->stopTime = microtime(true);
    }

    public function summary()
    {
        $updated = $this->getUpdatedCount();
        $skipped = $this->getSkippedCount();
        $deleted = $this->getDeletedCount();
        $str = sprintf("%d files updated, %d skipped, %d removed\n", $updated, $skipped, $deleted);
        $str .= sprintf("Generated in %f seconds\n", $this->getDuration());
        return $str;
    }

    public function verbose()
    {
        $str = '';
        foreach ($this->events as $event) {
            if ($event instanceof FileEvent) {
                $str .= $event->getPath() . "\n";
                switch (true) {
                    case $event->wasDeleted():
                        $str .= "  Removed\n";
                        break;
                    case $event->wasSkipped():
                        $str .= "  Skipped\n";
                        break;
                    case $event->wasUpdated():
                        $ms = $event->getDuration() * 1000;
                        $str .= sprintf("  Updated (%s ms)\n", number_format($ms, 2));
                        break;
                }
            }
        }
        if (!empty($str)) $str .= "\n";
        $str .= $this->summary();
        return $str;
    }

    protected function getFilteredEventCount(callable $filter)
    {
        return count(array_filter($this->events, $filter));
    }
}
