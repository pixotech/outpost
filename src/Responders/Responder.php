<?php

namespace Outpost\Responders;

use Outpost\SiteInterface;
use Symfony\Component\HttpFoundation\Request;

class Responder implements ResponderInterface
{
    protected $tasks = [];

    public function __invoke(SiteInterface $site, Request $request, $response = null)
    {
        foreach ($this->tasks as $task) {
            $response = call_user_func($task, $site, $request, $response);
        }
        return $response;
    }

    public function get(callable $resource)
    {
        $this->tasks[] = new ResourceTask($resource);
        return $this;
    }

    public function render($template)
    {
        $this->tasks[] = new RenderTask($template);
        return $this;
    }
}