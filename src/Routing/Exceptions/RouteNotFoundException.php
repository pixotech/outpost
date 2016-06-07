<?php

namespace Outpost\Routing\Exceptions;

use Outpost\Exceptions\Exception;
use Symfony\Component\HttpFoundation\Request;

class RouteNotFoundException extends Exception
{
    protected $request;

    public function __construct(Request $request, \Exception $e)
    {
        $this->request = $request;
        parent::__construct("No route found for {$request->getPathInfo()}");
    }

    public function getHelp()
    {
        return <<<HTML

<pre><b>{$this->getRequestMethod()}</b> {$this->getRequestPath()}</pre>

<p>No route is available to handle this request.</p>

HTML;
    }

    public function getRequestMethod()
    {
        return $this->request->getMethod();
    }

    public function getRequestPath()
    {
        return $this->request->getPathInfo();
    }
}