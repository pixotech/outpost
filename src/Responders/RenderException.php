<?php

namespace Outpost\Responders;

class RenderException extends \RuntimeException
{
    protected $context;

    protected $template;

    public function __construct($template, $context, \Exception $previous = null)
    {
        $this->template = $template;
        $this->context = $context;
        $message = $previous ? $previous->getMessage() : "Render error";
        parent::__construct($message, 0, $previous);
    }
}
