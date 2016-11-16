<?php

namespace Outpost\Generator\Files;

use Outpost\Generator\EventInterface;

class FileEvent implements EventInterface
{
    const DELETED = 'deleted';
    const SKIPPED = 'skipped';
    const UPDATED = 'updated';

    protected $action;
    protected $path;
    protected $startTime;
    protected $stopTime;

    public function __construct($path, $autostart = true)
    {
        $this->path = $path;
        if ($autostart) $this->start();
    }

    /**
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }

    public function getDuration()
    {
        return $this->stopTime - $this->startTime;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    public function start()
    {
        $this->startTime = microtime(true);
    }

    public function stop($action, $size = null)
    {
        $this->stopTime = microtime(true);
        $this->action = $action;
    }

    public function wasDeleted()
    {
        return $this->action == self::DELETED;
    }

    public function wasSkipped()
    {
        return $this->action == self::SKIPPED;
    }

    public function wasUpdated()
    {
        return $this->action == self::UPDATED;
    }
}
