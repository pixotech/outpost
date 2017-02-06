<?php

namespace Outpost\Routing;

use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use Symfony\Component\HttpFoundation\Request;

class Router implements RouterInterface
{
    /**
     * @var Dispatcher
     */
    protected $dispatcher;

    /**
     * @var callable[]
     */
    protected $routes = [];

    /**
     * @param array $routes
     */
    public function __construct(array $routes = [])
    {
        foreach ($routes as $path => $responder) {
            $this->route($path, $responder);
        }
    }

    /**
     * @param Request $request
     * @return callable
     */
    public function getResponder(Request $request)
    {
        $route = $this->getDispatcher()->dispatch($request->getMethod(), $request->getPathInfo());
        switch ($route[0]) {

            case Dispatcher::FOUND:
                list(, $responder, $attributes) = $route;
                $request->attributes->add($attributes);
                break;

            case Dispatcher::METHOD_NOT_ALLOWED;
                throw new UnavailableMethodException($request, $route[1]);

            case Dispatcher::NOT_FOUND:
            default:
                throw new UnrecognizedRouteException($request);
        }
        return $responder;
    }

    /**
     * @param string $path
     * @param callable $responder
     */
    public function route($path, callable $responder)
    {
        $this->routes[$path] = $responder;
        $this->destroyDispatcher();
    }

    protected function destroyDispatcher()
    {
        $this->dispatcher = null;
    }

    /**
     * @return Dispatcher
     */
    protected function getDispatcher()
    {
        if (!isset($this->dispatcher)) {
            $this->dispatcher = $this->makeDispatcher();
        }
        return $this->dispatcher;
    }

    /**
     * @return Dispatcher
     */
    protected function makeDispatcher()
    {
        return \FastRoute\simpleDispatcher(function (RouteCollector $r) {
            foreach ($this->routes as $path => $responder) {
                $r->addRoute('GET', $path, $responder);
            }
        });
    }
}
