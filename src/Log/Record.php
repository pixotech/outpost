<?php

namespace Outpost\Log;

use Monolog\Logger;

class Record implements RecordInterface
{
    /**
     * @var array
     */
    protected $record;

    /**
     * @param array $record
     */
    public function __construct(array $record)
    {
        $this->record = $record;
    }

    /**
     * @return array
     */
    public function getContext()
    {
        return $this->record['context'];
    }

    /**
     * @return int
     */
    public function getLevel()
    {
        return $this->record['level'];
    }

    public function getLevelColor()
    {
        switch ($this->getLevel()) {
            case Logger::WARNING:
                return 'yellow';
            case Logger::ERROR:
            case Logger::CRITICAL:
            case Logger::ALERT:
            case Logger::EMERGENCY:
                return 'red';
            case Logger::DEBUG:
            case Logger::INFO:
            case Logger::NOTICE:
            default:
                return 'white';
        }
    }

    public function getLevelName()
    {
        return Logger::getLevelName($this->getLevel());
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->record['message'];
    }

    public function getRequestLine()
    {
        if (!isset($this->record['extra']['http_method'])) return null;
        $method = $this->record['extra']['http_method'];
        $server = $this->record['extra']['server'];
        $url = $this->record['extra']['url'];
        return "{$method} {$server}{$url}";
    }

    public function getSystem()
    {
        return !empty($this->record['context']['outpost']) ? $this->record['context']['outpost'] : null;
    }

    /**
     * @return \DateTime
     */
    public function getTime()
    {
        return $this->record['datetime'];
    }

    /**
     * @return bool
     */
    public function hasContext()
    {
        return !empty($this->record['context']);
    }
}
