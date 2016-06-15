<?php

/**
 * @package Outpost
 * @author Pixo <info@pixotech.com>
 * @copyright 2016, Pixo
 * @license http://opensource.org/licenses/NCSA NCSA
 */

namespace Outpost;

use GuzzleHttp\Client;
use Outpost\Cache\Cache;
use Outpost\Cache\CacheableInterface;
use Outpost\Recovery\HelpResponse;
use Outpost\Routing\Response;
use Phroute\Phroute\Dispatcher;
use Phroute\Phroute\Exception\HttpRouteNotFoundException;
use Phroute\Phroute\RouteCollector;
use Phroute\Phroute\RouteDataProviderInterface;
use Stash\Driver\Ephemeral;
use Symfony\Component\HttpFoundation\Request;

class Site implements SiteInterface, \ArrayAccess
{

    /**
     * @var \Outpost\Cache\Cache
     */
    protected $cache;

    /**
     * @var \GuzzleHttp\ClientInterface
     */
    protected $client;

    /**
     * @var callable[]
     */
    protected $listeners = [];

    /**
     * @var RouteCollector
     */
    private $router;

    /**
     * Shorthand for Site::get()
     *
     * @param callable $resource
     * @return mixed
     */
    public function __invoke(callable $resource)
    {
        return $this->get($resource);
    }

    /**
     * @param string $method
     * @param string $path
     * @param callable $handler
     * @param string $name
     */
    public function addRoute($method, $path, callable $handler, $name = null)
    {
        $route = $name ? [$path, $name] : $name;
        $this->getRouter()->addRoute($method, $route, new Response($handler));
    }

    public function route($method, $path, callable $handler)
    {
        trigger_error("Site::route() is deprecated; use Site::addRoute() instead.", E_USER_DEPRECATED);
        $this->addRoute($method, $path, $handler);
    }

    /**
     * Get a site resource
     *
     * @param callable $resource
     * @return mixed
     */
    public function get(callable $resource)
    {
        if ($resource instanceof CacheableInterface) {
            $key = $resource->getCacheKey();
            $lifetime = $resource->getCacheLifetime();
            /** @var callable $resource */
            $result = $this->getCache()->get($key, $resource, $lifetime);
        } else {
            $result = call_user_func($resource, $this);
        }
        return $result;
    }

    /**
     * Get the site cache
     *
     * @return \Outpost\Cache\Cache
     * @see \Outpost\Cache\Cache Cache
     */
    public function getCache()
    {
        if (!isset($this->cache)) $this->cache = $this->makeCache();
        return $this->cache;
    }

    /**
     * @return \GuzzleHttp\ClientInterface
     */
    public function getClient()
    {
        if (!isset($this->client)) $this->client = $this->makeClient();
        return $this->client;
    }

    /**
     * @return RouteCollector
     */
    public function getRouter()
    {
        if (!isset($this->router)) $this->router = $this->makeRouter();
        return $this->router;
    }

    public function offsetExists($urlName)
    {
        throw new \BadMethodCallException("Not supported");
    }

    public function offsetGet($urlName)
    {
        throw new \BadMethodCallException("Not supported");
    }

    public function offsetSet($path, $responder)
    {
        $name = null;
        if (is_array($responder)) {
            $name = $path;
            list($path, $responder) = each($responder);
        }
        if (!is_callable($responder)) {
            throw new \InvalidArgumentException();
        }
        if ($pos = strpos($path, ' ')) {
            $method = substr($path, 0, $pos);
            $path = ltrim(substr($path, $pos));
        } else {
            $method = 'GET';
        }
        $this->addRoute($method, $path, $responder, $name);
    }

    public function offsetUnset($route)
    {
        throw new \BadMethodCallException("Not supported");
    }

    /**
     * @param Request $request
     */
    public function respond(Request $request)
    {
        try {
            //$this->log("Request received: " . $request->getPathInfo());
            $this->dispatch($request);
        } catch (\Exception $error) {
            $this->recover($error, $request);
        }
    }

    /**
     * @param callable $listener
     */
    public function subscribe(callable $listener)
    {
        $this->listeners[] = $listener;
    }

    /**
     * @param string $name
     * @param array $parameters
     * @return string
     */
    public function getUrl($name, array $parameters = [])
    {
        return $this->getRouter()->route($name, $parameters);
    }

    /**
     * @param Request $request
     */
    protected function dispatch(Request $request)
    {
        $router = $this->getRouter();
        $dispatcher = new Dispatcher($router->getData());
        $response = $dispatcher->dispatch($request->getMethod(), $request->getPathInfo());
        call_user_func($response->getResponder(), $this, $request, $response->getParameters());
    }

    /**
     * @return \Stash\Interfaces\DriverInterface
     */
    protected function getCacheDriver()
    {
        return new Ephemeral();
    }

    /**
     * @return array
     */
    protected function getClientOptions()
    {
        return [];
    }

    /**
     * @return \Outpost\Cache\Cache
     */
    protected function makeCache()
    {
        $driver = $this->getCacheDriver();
        return new Cache($this, $driver);
    }

    /**
     * @return Client
     */
    protected function makeClient()
    {
        return new Client($this->getClientOptions());
    }

    /**
     * @param \Exception $error
     * @return Recovery\HelpResponse
     */
    protected function makeErrorResponse(\Exception $error)
    {
        return new HelpResponse($error);
    }

    /**
     * @return RouteCollector
     */
    protected function makeRouter()
    {
        return new RouteCollector();
    }

    /**
     * @param \Exception $error
     * @param Request $request
     * @return Recovery\HelpResponse
     */
    protected function recover(\Exception $error, Request $request)
    {
        try {
            $response = $this->makeErrorResponse($error);
        } catch (\Exception $e) {
            $response = new Response($e->getMessage(), 500);
        }
        $response->prepare($request);
        $response->send();
    }
}
