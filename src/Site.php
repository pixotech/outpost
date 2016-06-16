<?php

/**
 * @package Outpost
 * @author Pixo <info@pixotech.com>
 * @copyright 2016, Pixo
 * @license http://opensource.org/licenses/NCSA NCSA
 */

namespace Outpost;

use Monolog\Logger;
use Outpost\Resources\CacheableInterface;
use Outpost\Recovery\HelpPage;
use Outpost\Routing\Response;
use Phroute\Phroute\Dispatcher;
use Phroute\Phroute\RouteCollector;
use Psr\Log\LogLevel;
use Stash\Driver\Ephemeral;
use Stash\Pool;
use Symfony\Component\HttpFoundation\Request;

class Site implements SiteInterface, \ArrayAccess
{
    /**
     * @var Pool
     */
    protected $cache;

    /**
     * @var Logger
     */
    protected $log;

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
            $cached = $this->getCache()->getItem($key);
            $result = $cached->get();
            if ($cached->isMiss()) {
                $this->log(sprintf("Not found: %s", $key), LogLevel::NOTICE);
                $cached->lock();
                $result = call_user_func($resource, $this);
                $cached->set($result, $lifetime);
            }
            else {
                $this->log(sprintf("Found: %s", $key));
            }
        } else {
            $result = call_user_func($resource, $this);
        }
        return $result;
    }

    /**
     * @return Pool
     */
    public function getCache()
    {
        if (!isset($this->cache)) $this->cache = $this->makeCache();
        return $this->cache;
    }

    public function getLog()
    {
        if (!isset($this->log)) $this->log = $this->makeLog();
        return $this->log;
    }

    /**
     * @return RouteCollector
     */
    public function getRouter()
    {
        if (!isset($this->router)) $this->router = $this->makeRouter();
        return $this->router;
    }

    public function log($message, $level = null)
    {
        if (!isset($level)) $level = LogLevel::INFO;
        $this->getLog()->log($level, $message);
    }

    public function offsetExists($urlName)
    {
        throw new \BadMethodCallException("Not supported");
    }

    public function offsetGet($urlName)
    {
        return null;
    }

    public function offsetSet($path, $responder)
    {
        $name = null;
        if (is_array($responder) && !is_callable($responder)) {
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
     * @param \Exception $error
     */
    public function recover(\Exception $error)
    {
        try {
            print new HelpPage($error);
        } catch (\Exception $e) {
            header("HTTP/1.1 500 Internal Server Error");
            print $e->getMessage();
        }
    }

    /**
     * @param Request $request
     */
    public function respond(Request $request)
    {
        try {
            $this->log("Request received: " . $request->getPathInfo());
            $this->dispatch($request);
        } catch (\Exception $error) {
            $this->recover($error);
        }
    }

    /**
     * @param string $name
     * @param array $parameters
     * @return string
     */
    public function getUrl($name, array $parameters = [])
    {
        return '/' . $this->getRouter()->route($name, $parameters);
    }

    /**
     * @param Request $request
     */
    protected function dispatch(Request $request)
    {
        $dispatcher = new Dispatcher($this->getRouter()->getData());
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
     * @return Pool
     */
    protected function makeCache()
    {
        return new Pool($this->getCacheDriver());
    }

    /**
     * @return Logger
     */
    protected function makeLog()
    {
        return new Logger('outpost');
    }

    /**
     * @return RouteCollector
     */
    protected function makeRouter()
    {
        return new RouteCollector();
    }
}
