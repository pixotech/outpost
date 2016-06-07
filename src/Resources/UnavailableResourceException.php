<?php

namespace Outpost\Resources;

use Outpost\Exceptions\Exception;

class UnavailableResourceException extends Exception
{

    /**
     * @var callable
     */
    protected $resource;

    /**
     * @param callable $resource
     * @param \Exception $exception
     * @param null $message
     */
    public function __construct(callable $resource, \Exception $exception = null, $message = null)
    {
        if (empty($message)) {
            $message = $exception->getMessage() ?: "Resource unavailable";
        }
        parent::__construct($message, 0, $exception);
        $this->resource = $resource;
    }

    public function getHelp()
    {
        return <<<HTML

<pre>{$this->getResourceClassname()}</pre>

<p>This resource was unavailable.</p>

<p>It was requested on line {$this->getLine()} of <code>{$this->getFile()}</code>.</p>

HTML;
    }

    /**
     * @return callable
     */
    public function getResource()
    {
        return $this->resource;
    }

    public function getResourceClassname()
    {
        return get_class($this->getResource());
    }
}