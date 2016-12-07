<?php

namespace Outpost\Templates;

use Outpost\Events\Event;

class RenderEvent extends Event
{
    protected $template;

    protected $variables = [];

    public function __construct($template, array $variables = [])
    {
        parent::__construct();
        $this->template = $template;
        $this->variables = $variables;
    }

    public function getLocation()
    {
        return 'Render';
    }

    public function getLogMessage()
    {
        return sprintf("%s (%s)", $this->template, implode(', ', array_keys($this->variables)));
    }
}
