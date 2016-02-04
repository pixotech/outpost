<?php

/**
 * @package Outpost
 * @author Pixo <info@pixotech.com>
 * @copyright 2015, Pixo
 * @license http://opensource.org/licenses/NCSA NCSA
 */

namespace Outpost;

use GuzzleHttp\Client;
use Outpost\Cache\Cache;
use Outpost\Cache\CacheableInterface;
use Outpost\Events\EventInterface;
use Outpost\Events\RequestReceivedEvent;
use Outpost\Recovery\HelpResponse;
use Outpost\Resources\ResourceInterface;
use Outpost\Resources\SiteResourceInterface;
use Outpost\Resources\UnavailableResourceException;
use Outpost\Routing\Resolver;
use Outpost\Routing\ResponderResolver;
use Outpost\Routing\RouterInterface;
use Phroute\Phroute\Dispatcher;
use Phroute\Phroute\RouteCollector;
use Phroute\Phroute\RouteDataProviderInterface;
use Stash\Driver\Ephemeral;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Site implements SiteInterface, \ArrayAccess {

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
  protected $router;

  /**
   * Shorthand for Site::get()
   *
   * @param callable $resource
   * @return mixed
   * @throws UnavailableResourceException
   * @throws \Exception
   */
  public function __invoke(callable $resource) {
    return $this->get($resource);
  }

  /**
   * Get a site resource
   *
   * @param callable $resource
   * @return mixed
   * @throws UnavailableResourceException
   * @throws \Exception
   */
  public function get(callable $resource) {
    if ($resource instanceof SiteResourceInterface) {
      $resource = clone $resource;
      $resource->setSite($this);
    }
    try {
      if ($resource instanceof CacheableInterface) {
        $key = $resource->getCacheKey();
        $lifetime = $resource->getCacheLifetime();
        /** @var callable $resource */
        $result = $this->getCache()->get($key, $resource, $lifetime);
      }
      else {
        $result = call_user_func($resource, $this);
      }
      return $result;
    }
    catch (\Exception $exception) {
      if ($resource instanceof ResourceInterface) {
        throw new UnavailableResourceException($resource, $exception);
      }
      else {
        throw $exception;
      }
    }
  }

  /**
   * Get the site cache
   *
   * @return \Outpost\Cache\Cache
   * @see \Outpost\Cache\Cache Cache
   */
  public function getCache() {
    if (!isset($this->cache)) $this->cache = $this->makeCache();
    return $this->cache;
  }

  /**
   * @return \GuzzleHttp\ClientInterface
   */
  public function getClient() {
    if (!isset($this->client)) $this->client = $this->makeClient();
    return $this->client;
  }

  /**
   * @return RouteCollector
   */
  public function getRouter() {
    if (!isset($this->router)) $this->router = $this->makeRouter();
    return $this->router;
  }

  public function offsetExists($urlName) {
    return $this->getRouter()->offsetExists($urlName);
  }

  public function offsetGet($urlName) {
    return $this->getRouter()->offsetGet($urlName);
  }

  public function offsetSet($route, $responder) {
    $this->getRouter()->offsetSet($route, $responder);
  }

  public function offsetUnset($route) {
    $this->getRouter()->offsetUnset($route);
  }

  /**
   * @param EventInterface $event
   */
  public function report(EventInterface $event) {
    foreach ($this->listeners as $listener) call_user_func($listener, $event, $this);
  }

  /**
   * @param Request $request
   */
  public function respond(Request $request) {
    try {
      $this->report(new RequestReceivedEvent($request));
      $this->dispatch($request);
    }
    catch (\Exception $error) {
      $this->recover($error, $request);
    }
  }

  /**
   * @param string $method
   * @param string $path
   * @param callable $handler
   * @param array $filters
   */
  public function route($method, $path, callable $handler, array $filters = []) {
    $router = $this->getRouter();
    if ($router instanceof RouteDataProviderInterface) {
      $this->getRouter()->addRoute($method, $path, $handler, $filters);
      trigger_error("This method of adding routes is deprecated.", E_USER_DEPRECATED);
    }
    else {
      throw new \BadMethodCallException();
    }
  }

  /**
   * @param callable $listener
   */
  public function subscribe(callable $listener) {
    $this->listeners[] = $listener;
  }

  /**
   * @param Request $request
   */
  protected function dispatch(Request $request) {
    $router = $this->getRouter();
    if ($router instanceof RouterInterface) {
      call_user_func($router, new Resolver($this, $request));
      return;
    }
    if ($router instanceof RouteDataProviderInterface) {
      $this->makeDispatcher($router, $request)->dispatch($request->getMethod(), $request->getPathInfo());
      return;
    }
    throw new \UnexpectedValueException("Unrecognized router");
  }

  /**
   * @return \Stash\Interfaces\DriverInterface
   */
  protected function getCacheDriver() {
    return new Ephemeral();
  }

  /**
   * @return array
   */
  protected function getClientOptions() {
    return [];
  }

  /**
   * @return \Outpost\Cache\Cache
   */
  protected function makeCache() {
    $driver = $this->getCacheDriver();
    return new Cache($this, $driver);
  }

  /**
   * @return Client
   */
  protected function makeClient() {
    return new Client($this->getClientOptions());
  }

  /**
   * @param RouteDataProviderInterface $router
   * @param Request $request
   * @return Dispatcher
   */
  protected function makeDispatcher(RouteDataProviderInterface $router, Request $request) {
    return new Dispatcher($router->getData(), new ResponderResolver($this, $request));
  }

  /**
   * @param \Exception $error
   * @return Recovery\HelpResponse
   */
  protected function makeErrorResponse(\Exception $error) {
    return new HelpResponse($error);
  }

  /**
   * @return RouteCollector
   */
  protected function makeRouter() {
    return new RouteCollector();
  }

  /**
   * @param \Exception $error
   * @param Request $request
   * @return Recovery\HelpResponse
   */
  protected function recover(\Exception $error, Request $request) {
    try {
      $response = $this->makeErrorResponse($error);
    }
    catch (\Exception $e) {
      $response = new Response($e->getMessage(), 500);
    }
    $response->prepare($request);
    $response->send();
  }
}
