<?php

/**
 * @package Outpost
 * @author Pixo <info@pixotech.com>
 * @copyright 2015, Pixo
 * @license http://opensource.org/licenses/NCSA NCSA
 */

namespace Outpost;

use Monolog\Logger;
use Monolog\Processor\WebProcessor;
use Outpost\Assets\AssetManager;
use Outpost\Cache\Cache;
use Outpost\Environments\EnvironmentInterface;
use Outpost\Cache\CacheableInterface;
use Outpost\Events\EventInterface;
use Outpost\Events\ExceptionEvent;
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
   * @var \Outpost\Web\Client
   */
  protected $client;

  /**
   * @var Environments\EnvironmentInterface
   */
  protected $environment;

  /**
   * @var \Monolog\Logger
   */
  protected $log;

  /**
   * @var \Twig_Environment
   */
  protected $twig;

  /**
   * @param EnvironmentInterface $environment
   */
  public function __construct(EnvironmentInterface $environment) {
    $this->environment = $environment;
    $this->log = $this->makeLog();
    $this->cache = $this->makeCache();
    $this->client = $this->makeClient();
    $this->assetManager = $this->makeAssetManager();
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
   * Get the site web client
   *
   * @return \GuzzleHttp\ClientInterface
   * @see \Outpost\Web\Client Client
   */
  public function getClient() {
    return $this->client;
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
   * Get the site log
   *
   * @return \Psr\Log\LoggerInterface
   */
  public function getLog() {
    return $this->log;
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
   * Get the site Twig parser
   *
   * @return \Twig_Environment
   */
  public function getTwig() {
    if (!isset($this->twig)) $this->twig = $this->makeTwig();
    return $this->twig;
  }

  /**
   * @param EventInterface $event
   */
  public function handleEvent(EventInterface $event) {
    $this->getLog()->log($event->getLogLevel(), $event->getLogMessage(), ['event' => $event]);
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
   * @param string $template
   * @param array $variables
   * @return string
   */
  public function render($template, array $variables = []) {
    if ($template instanceof RenderableInterface) {
      $variables += $template->getTemplateVariables();
      $template = $template->getTemplate();
    }
    return $this->getTwig()->render($template, $variables);
  }

  /**
   * @param Request $request
   */
  public function respond(Request $request) {
    $this->handleEvent(new RequestReceivedEvent($request));
    try {
      if ($this->assetManager->isAssetRequest($request)) {
        $response = $this->assetManager->getResponse($request);
      }
      else {
        $response = $this->getResponse($request);
      }
      if (!($response instanceof Response)) {
        throw new InvalidResponseException($this, $request, $response);
      }
      $this->handleEvent(new ResponseCompleteEvent($response, $request));
    }
    catch (\Exception $e) {
      $response = $this->handleResponseException($e, $request);
    }
    $response->prepare($request);
    $response->send();
  }

  /**
   * @return \Monolog\Handler\HandlerInterface[]
   */
  protected function getLogHandlers() {
    return $this->getEnvironment()->getLogHandlers();
  }

  /**
   * @return \Monolog\Handler\HandlerInterface[]
   */
  protected function getLogProcessors() {
    return [new WebProcessor()];
  }

  protected function getTwigLoader() {
    return $this->getEnvironment()->getTwigLoader();
  }

  protected function getTwigOptions() {
    return $this->getEnvironment()->getTwigOptions();
  }

  /**
   * @param \Exception $exception
   * @param Request $request
   * @return Recovery\HelpResponse
   */
  protected function handleResponseException(\Exception $exception, Request $request) {
    $this->handleEvent(new ExceptionEvent($exception));
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
   * @return \Outpost\Web\Client
   */
  protected function makeClient() {
    $client = new \GuzzleHttp\Client();
    return new Web\Client($this, $client);
  }

  /**
   * @param \Exception $error
   * @param Request $request
   * @return Recovery\HelpResponse
   */
  protected function makeErrorResponse(\Exception $error, Request $request) {
    return new HelpResponse($error);
  }

  /**
   * @return \Monolog\Logger
   */
  protected function makeLog() {
    return new Logger('outpost', $this->getLogHandlers(), $this->getLogProcessors());
  }

  /**
   * @return \Twig_Environment
   */
  protected function makeTwig() {
    return new \Twig_Environment($this->getTwigLoader(), $this->getTwigOptions());
  }
}
