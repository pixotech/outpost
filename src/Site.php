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
use Outpost\Routing\RouterDataResource;
use Phroute\Phroute\Dispatcher;
use Phroute\Phroute\RouteCollector;
use Stash\Driver\Ephemeral;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

abstract class Site implements SiteInterface {

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
   * @param callable $listener
   */
  public function addListener(callable $listener) {
    $this->listeners[] = $listener;
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
        $result = call_user_func($resource);
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
   * @param Request $request
   */
  protected function dispatch(Request $request) {
    $this->makeDispatcher($request)->dispatch($request->getMethod(), $request->getPathInfo());
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
   * @param Request $request
   * @return Dispatcher
   * @throws UnavailableResourceException
   * @throws \Exception
   */
  protected function makeDispatcher(Request $request) {
    return new Dispatcher($this->get(new RouterDataResource()), new Resolver($this, $request));
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
