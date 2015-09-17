<?php

/**
 * @package Outpost
 * @author Pixo <info@pixotech.com>
 * @copyright 2015, Pixo
 * @license http://opensource.org/licenses/NCSA NCSA
 */

namespace Outpost;

use Outpost\Assets\AssetManager;
use Outpost\Cache\Cache;
use Outpost\Environments\EnvironmentInterface;
use Outpost\Cache\CacheableInterface;
use Outpost\Events\EventInterface;
use Outpost\Events\ExceptionEvent;
use Outpost\Events\ListenerInterface;
use Outpost\Events\RequestReceivedEvent;
use Outpost\Events\ResponseCompleteEvent;
use Outpost\Exceptions\InvalidResponseException;
use Outpost\Recovery\HelpResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

abstract class Site implements SiteInterface {

  /**
   * @var \Outpost\Assets\AssetManagerInterface
   */
  protected $assetManager;

  /**
   * @var \Outpost\Cache\Cache
   */
  protected $cache;

  /**
   * @var Environments\EnvironmentInterface
   */
  protected $environment;

  /**
   * @var Events\ListenerInterface[]
   */
  protected $listeners = [];

  /**
   * @param EnvironmentInterface $environment
   */
  public function __construct(EnvironmentInterface $environment) {
    $this->environment = $environment;
    $this->cache = $this->makeCache();
    $this->assetManager = $this->makeAssetManager();
  }

  public function addListener(ListenerInterface $listener) {
    $this->listeners[] = $listener;
  }

  /**
   * Get a site resource
   *
   * @param callable $resource
   * @return mixed
   */
  public function get(callable $resource) {
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

  /**
   * @return \Outpost\Assets\AssetManagerInterface
   */
  public function getAssetManager() {
    return $this->assetManager;
  }

  /**
   * Get the site cache
   *
   * @return \Outpost\Cache\Cache
   * @see \Outpost\Cache\Cache Cache
   */
  public function getCache() {
    return $this->cache;
  }

  /**
   * Get the local environment
   *
   * @return EnvironmentInterface
   * @see \Outpost\Environment\EnvironmentInterface EnvironmentInterface
   */
  public function getEnvironment() {
    return $this->environment;
  }

  /**
   * @param string $key
   * @return mixed
   */
  public function getSecret($key) {
    return $this->getEnvironment()->getSecret($key);
  }

  /**
   * Get the value of a site setting
   *
   * @param string $key
   * @return mixed
   */
  public function getSetting($key) {
    return $this->getEnvironment()->getSetting($key);
  }

  /**
   * @param string $key
   * @return bool
   */
  public function hasSetting($key) {
    return $this->getEnvironment()->hasSetting($key);
  }

  /**
   * @param string $key
   * @return bool
   */
  public function hasSecret($key) {
    return $this->getEnvironment()->hasSecret($key);
  }

  /**
   * @param Request $request
   * @return Response
   */
  public function makeResponse(Request $request) {
    $this->report(new RequestReceivedEvent($request));
    try {
      if ($this->assetManager->isAssetRequest($request)) {
        $response = $this->assetManager->getResponse($request);
      }
      else {
        $response = $this->respond($request);
      }
      if (!($response instanceof Response)) {
        throw new InvalidResponseException($this, $request, $response);
      }
      $this->report(new ResponseCompleteEvent($response, $request));
    }
    catch (\Exception $e) {
      $response = $this->handleResponseException($e, $request);
    }
    return $response;
  }

  /**
   * @param EventInterface $event
   */
  public function report(EventInterface $event) {
    foreach ($this->listeners as $listener) $listener->handleEvent($event, $this);
  }

  /**
   * @param Request $request
   * @return Response
   */
  abstract public function respond(Request $request);

  /**
   * @param \Exception $exception
   * @param Request $request
   * @return Recovery\HelpResponse
   */
  protected function handleResponseException(\Exception $exception, Request $request) {
    $this->report(new ExceptionEvent($exception));
    return $this->makeErrorResponse($exception, $request);
  }

  /**
   * @return AssetManager
   */
  protected function makeAssetManager() {
    return new AssetManager($this);
  }

  /**
   * @param null|string $ns Namespace
   * @return \Outpost\Cache\Cache
   */
  protected function makeCache($ns = null) {
    $driver = $this->getEnvironment()->getCacheDriver();
    return new Cache($this, $driver, $ns);
  }

  /**
   * @param \Exception $error
   * @param Request $request
   * @return Recovery\HelpResponse
   */
  protected function makeErrorResponse(\Exception $error, Request $request) {
    return new HelpResponse($error);
  }
}
