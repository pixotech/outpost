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
use Outpost\Assets\AssetGeneratedEvent;
use Outpost\Assets\AssetInterface;
use Outpost\Assets\MarkerCreatedEvent;
use Outpost\Cache\Cache;
use Outpost\Environments\EnvironmentInterface;
use Outpost\Cache\CacheableInterface;
use Outpost\Events\ErrorEvent;
use Outpost\Events\EventInterface;
use Outpost\Events\ExceptionEvent;
use Outpost\Events\RequestReceivedEvent;
use Outpost\Events\ResponseCompleteEvent;
use Outpost\Recovery\HelpResponse;
use Outpost\Responders\ResponderInterface;
use Outpost\Responders\Responses\RenderableResponseInterface;
use Outpost\Responders\Responses\ResponseInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Site implements SiteInterface {

  /**
   * @var \Stash\Pool
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
   * @param Request $request
   */
  public static function respond(EnvironmentInterface $environment, Request $request = null) {
    if (!isset($request)) $request = Request::createFromGlobals();
    try {
      /** @var Site $site */
      $site = new static($environment);
      $response = $site->invoke($request);
    }
    catch (\Exception $e) {
      $response = new Response('Internal Server Error', 500);
    }
    $response->prepare($request);
    $response->send();
  }

  /**
   * @param EnvironmentInterface $environment
   */
  public function __construct(EnvironmentInterface $environment) {
    $this->environment = $environment;
    $this->log = $this->makeLog();
    $this->cache = $this->makeCache();
    $this->client = $this->makeClient();
  }

  /**
   * @param null|Request $request
   * @return Response
   */
  public function __invoke(Request $request = null) {
    return $this->invoke($request);
  }

  /**
   * @param string $key
   */
  public function clearAssetMarker($key) {
    unlink($this->getAssetMarkerPath($key));
  }

  /**
   * @param AssetInterface $asset
   */
  public function createAssetMarker(AssetInterface $asset) {
    $key = $asset->getKey();
    file_put_contents($this->getAssetMarkerPath($key), serialize($asset));
    $this->handleEvent(new MarkerCreatedEvent($asset));
  }

  /**
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
   * @param AssetInterface $asset
   * @return \SplFileInfo
   */
  public function getAssetFile(AssetInterface $asset) {
    if (!$this->hasLocalAsset($asset)) $this->generateAsset($asset);
    return new \SplFileInfo($this->getLocalAssetPath($asset));
  }

  /**
   * @param string $key
   * @return AssetInterface
   * @throws \OutOfBoundsException
   */
  public function getAssetMarker($key) {
    $marker = $this->getAssetMarkerPath($key);
    if (!file_exists($marker)) throw new \OutOfBoundsException("Unknown asset: $key");
    return unserialize(file_get_contents($marker));
  }

  /**
   * @param string $key
   * @return string
   */
  public function getAssetMarkerPath($key) {
    return $this->getEnvironment()->getAssetCacheDirectory() . '/' . $key;
  }

  /**
   * @param AssetInterface $asset
   * @return string
   */
  public function getAssetUrl(AssetInterface $asset) {
    return "/_assets/" . $asset->getKey() . '.' . $asset->getExtension();
  }

  /**
   * @return \Outpost\Cache\Cache
   */
  public function getCache() {
    return $this->cache;
  }

  /**
   * @return \GuzzleHttp\ClientInterface
   */
  public function getClient() {
    return $this->client;
  }

  /**
   * @return EnvironmentInterface
   */
  public function getEnvironment() {
    return $this->environment;
  }

  /**
   * @return \Psr\Log\LoggerInterface
   */
  public function getLog() {
    return $this->log;
  }

  /**
   * @return string
   */
  public function getPublicDirectory() {
    return $this->getEnvironment()->getPublicDirectory();
  }

  /**
   * @param string $key
   * @return mixed
   */
  public function getSecret($key) {
    return $this->getEnvironment()->getSecret($key);
  }

  /**
   * @param string $key
   * @return mixed
   */
  public function getSetting($key) {
    return $this->getEnvironment()->getSetting($key);
  }

  /**
   * @return \Twig_Environment
   */
  public function getTwig() {
    if (!isset($this->twig)) $this->twig = $this->makeTwig();
    return $this->twig;
  }

  /**
   * @param $level
   * @param $message
   * @param $file
   * @param $line
   * @throws \ErrorException
   */
  public function handleError($level, $message, $file, $line) {
    if ($level & error_reporting()) {
      if ($level & (E_ERROR | E_RECOVERABLE_ERROR | E_USER_ERROR)) {
        throw new \ErrorException($message, 0, $level, $file, $line);
      }
      else {
        $this->handleEvent(new ErrorEvent($level, $message, $file, $line));
      }
    }
  }

  public function handleEvent(EventInterface $event) {
    $this->getLog()->log($event->getLogLevel(), $event->getLogMessage(), ['event' => $event]);
  }

  /**
   * @param string $key
   * @return bool
   */
  public function hasAssetMarker($key) {
    return file_exists($this->getAssetMarkerPath($key));
  }

  /**
   * @param AssetInterface $asset
   * @return bool
   */
  public function hasLocalAsset(AssetInterface $asset) {
    return file_exists($this->getLocalAssetPath($asset));
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
   * @throws \Exception
   */
  public function invoke(Request $request = null) {
    $this->handleEvent(new RequestReceivedEvent($request));
    if (!isset($request)) $request = $this->getEnvironment()->getRequest();
    $this->enableErrorHandling();
    try {
      $response = $this->invokeResponders($request);
      if (!($response instanceof Response)) throw new Exceptions\InvalidResponseException($this, $request, $response);
      $this->handleEvent(new ResponseCompleteEvent($response, $request));
    }
    catch (\Exception $e) {
      $response = $this->handleRequestException($e, $request);
    }
    $this->disableErrorHandling();
    return $response;
  }

  /**
   *
   */
  protected function disableErrorHandling() {
    restore_error_handler();
  }

  /**
   *
   */
  protected function enableErrorHandling() {
    set_error_handler([$this, 'handleError']);
  }

  /**
   * @param AssetInterface $asset
   * @throws \Exception
   */
  protected function generateAsset(AssetInterface $asset) {
    $file = new \SplFileInfo($this->getLocalAssetPath($asset));
    $asset->generate($this, $file);
    $this->handleEvent(new AssetGeneratedEvent($asset));
  }

  /**
   * @param AssetInterface $asset
   * @return string
   */
  protected function getLocalAssetPath(AssetInterface $asset) {
    return $this->getEnvironment()->getGeneratedAssetsDirectory() . '/' . $asset->getKey() . '.' . $asset->getExtension();
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

  /**
   * @var mixed $request
   * @return ResponderInterface[]
   */
  protected function getResponders($request) {
    return [];
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
  protected function handleRequestException(\Exception $exception, Request $request) {
    $this->handleEvent(new ExceptionEvent($exception));
    return $this->makeErrorResponse($exception, $request);
  }

  /**
   * @param Request $request
   * @return Response
   * @throws \Exception
   */
  protected function invokeResponders(Request $request) {
    /** @var Responders\ResponderInterface[] $responders */
    $responders = $this->getResponders($request);
    if (!is_array($responders)) $responders = [$responders];
    foreach ($responders as $responder) {
      try {
        $response = $this->processResponse($responder->invoke());
        return $response;
      }
      catch (Responders\Exceptions\UnrecognizedRequestException $e) {
        continue;
      }
    }
    throw new Exceptions\UnrecognizedRequestException($this, $request);
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
   * @param ResponseInterface $response
   * @return Response
   */
  protected function makeResponse(ResponseInterface $response) {
    return new Response($response->getContent(), $response->getStatusCode(), $response->getHeaders());
  }

  /**
   * @return \Twig_Environment
   */
  protected function makeTwig() {
    return new \Twig_Environment($this->getTwigLoader(), $this->getTwigOptions());
  }

  protected function processResponse($response) {
    if ($response instanceof RenderableResponseInterface) $response = $response->render($this->getTwig());
    if ($response instanceof ResponseInterface) $response = $this->makeResponse($response);
    if (is_string($response)) $response = new Response($response);
    return $response;
  }
}
