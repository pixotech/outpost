<?php

namespace Outpost\Events;

class ExceptionEvent extends Event
{
    /**
     * @var \Exception
     */
    protected $exception;

    public function __construct(\Exception $exception)
    {
        parent::__construct();
        $this->exception = $exception;
    }

    public function getLocation()
    {
        return "Exception";
    }

    public function getLogMessage()
    {
        if ($this->exception->getFile()) {
            return sprintf("%s (%s, line %s)", $this->exception->getMessage(), $this->exception->getFile(), $this->exception->getLine());
        }
        return $this->exception->getMessage();
    }
}
