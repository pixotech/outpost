<?php

namespace Outpost\Routing;

use Phroute\Phroute\Dispatcher;
use Phroute\Phroute\RouteCollector;
use Symfony\Component\HttpFoundation\Request;

class Router implements RouterInterface
{
    /**
     * @var RouteCollector
     */
    protected $router;

    /**
     * @param RouteCollector|null $router
     */
    public function __construct(RouteCollector $router = null)
    {
        $this->router = $router ?: new RouteCollector();
    }

    /**
     * @param Request $request
     * @return callable
     */
    public function getResponder(Request $request)
    {
        $dispatcher = new Dispatcher($this->getRouter()->getData());
        /** @var Route $response */
        $response = $dispatcher->dispatch($request->getMethod(), $request->getPathInfo());
        $request->attributes->add($response->getParameters());
        return $response->getResponder();
    }

    /**
     * @return RouteCollector
     */
    public function getRouter()
    {
        return $this->router;
    }

    /**
     * @param string $method
     * @param string $path
     * @param callable $handler
     * @param string $name
     */
    public function route($method, $path, callable $handler, $name = null)
    {
        $route = $name ? [$path, $name] : $path;
        $this->getRouter()->addRoute($method, $route, new Route($handler));
    }
}
