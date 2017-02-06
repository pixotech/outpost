<?php

namespace Outpost\Routing;

use FastRoute\RouteCollector;
use Symfony\Component\HttpFoundation\Request;

class RouterTest extends \PHPUnit_Framework_TestCase
{
    public function testGetResponder()
    {
        $responder = function () { return "Welcome"; };
        $path = '/index';
        $router = new Router();
        $router->route($path, $responder);
        $this->assertSame($responder, $router->getResponder(Request::create($path)));
    }

    /**
     * @expectedException \Outpost\Routing\UnrecognizedRouteException
     */
    public function testGetResponderNotFound()
    {
        $responder = function () { return "Welcome"; };
        $path = '/index';
        $router = new Router();
        $this->assertSame($responder, $router->getResponder(Request::create($path)));
    }

    /**
     * @expectedException \Outpost\Routing\UnavailableMethodException
     */
    public function testMethodNotAllowed()
    {
        $responder = function () { return "Welcome"; };
        $path = '/index';
        $router = new Router();
        $router->route($path, $responder);
        $this->assertSame($responder, $router->getResponder(Request::create($path, 'POST')));
    }

    public function testDispatch()
    {
        $method = 'GET';
        $uri = '/index';
        $handler = 'CALLBACK';
        $routes = function (RouteCollector $r) use ($method, $uri, $handler) {
            $r->addRoute($method, $uri, $handler);
        };
        $dispatcher = \FastRoute\simpleDispatcher($routes);
        $route = $dispatcher->dispatch($method, $uri);
        $this->assertEquals($handler, $route[1]);
    }

    public function testDispatchReturnsHandlerReference()
    {
        $method = 'GET';
        $uri = '/index';
        $handler = new \stdClass();
        $routes = function (RouteCollector $r) use ($method, $uri, $handler) {
            $r->addRoute($method, $uri, $handler);
        };
        $dispatcher = \FastRoute\simpleDispatcher($routes);
        $route = $dispatcher->dispatch($method, $uri);
        $handler->someProperty = 'some value';
        $this->assertEquals($handler->someProperty, $route[1]->someProperty);
    }
}