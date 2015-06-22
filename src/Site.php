<?php

/**
 * @package Outpost
 * @author Pixo <info@pixotech.com>
 * @copyright 2015, Pixo
 * @license http://opensource.org/licenses/NCSA NCSA
 */

namespace Outpost;

use Outpost\Environments\EnvironmentInterface;
use Outpost\Responders\ResponderInterface;
use Outpost\Web\Requests\Request as WebRequest;
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
   * @var array
   */
  protected $secrets;

  /**
   * @var array
   */
  protected $settings;

  /**
   * @param EnvironmentInterface $environment
   * @param Request $request
   */
  public static function respond(EnvironmentInterface $environment, Request $request = null) {
    try {
      /** @var Site $site */
      $site = new static($environment);
      if (!isset($request)) $request = Request::createFromGlobals();
      $response = $site->invoke($request);
      $response->prepare($request);
      $response->send();
    }
    catch (\Exception $e) {
      print new Recovery\HelpPage($e);
    }
  }

  /**
   * @param EnvironmentInterface $environment
   */
  public function __construct(EnvironmentInterface $environment) {
    $this->environment = $environment;
    $this->loadSettings();
    $this->loadSecrets();
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
   * @return \Stash\Interfaces\PoolInterface
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
   * @param string $key
   * @return mixed
   */
  public function getSecret($key) {
    return $this->getSecrets()[$key];
  }

  /**
   * @param string $key
   * @return mixed
   */
  public function getSetting($key) {
    return $this->getSettings()[$key];
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
      $this->getLog()->error($message, [$file, $line]);
      if ($level & (E_ERROR | E_RECOVERABLE_ERROR | E_USER_ERROR)) {
        throw new \ErrorException($message, 0, $level, $file, $line);
      }
    }
  }

  /**
   * @param string $name
   * @return bool
   */
  public function hasSetting($name) {
    return array_key_exists($name, $this->getSettings());
  }

  /**
   * @param string $name
   * @return bool
   */
  public function hasSecret($name) {
    return array_key_exists($name, $this->getSecrets());
  }

  /**
   * @param Request $request
   * @return Response
   * @throws \Exception
   */
  public function invoke(Request $request = null) {
    if (!isset($request)) $request = $this->getEnvironment()->getRequest();
    $this->enableErrorHandling();
    try {
      $response = $this->invokeResponders($request);
      if (!($response instanceof Response)) throw new Exceptions\InvalidResponseException($this, $request, $response);
    }
    catch (\Exception $e) {
      $response = $this->handleRequestException($e);
    }
    $this->disbleErrorHandling();
    return $response;
  }

  public function request(WebRequest $request) {
    return $this->getClient()->send($request);
  }

  /**
   *
   */
  protected function disbleErrorHandling() {
    restore_error_handler();
  }

  /**
   *
   */
  protected function enableErrorHandling() {
    set_error_handler([$this, 'handleError']);
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
    return [new \Monolog\Processor\WebProcessor()];
  }

  /**
   * @return ResponderInterface[]
   */
  protected function getResponders($request) {
    return [];
  }

  /**
   * @return array
   */
  protected function getSecrets() {
    return $this->secrets;
  }

  /**
   * @return array
   */
  protected function getSettings() {
    return $this->settings;
  }

  /**
   * @param \Exception $exception
   * @return Recovery\HelpResponse
   */
  protected function handleRequestException(\Exception $exception) {
    $this->logException($exception);
    return new Recovery\HelpResponse($exception);
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
        return $responder->invoke();
      }
      catch (Responders\Exceptions\UnrecognizedRequestException $e) {
        continue;
      }
    }
    throw new Exceptions\UnrecognizedRequestException($this, $request);
  }

  /**
   * @param $name
   * @return array
   */
  protected function loadConfiguration($name) {
    $path = $this->makeConfigurationPath($name);
    if (!is_file($path) || !is_readable($path)) return [];
    return $this->parseConfiguration(file_get_contents($path));
  }

  /**
   *
   */
  protected function loadSecrets() {
    $this->secrets = $this->loadConfiguration('secrets');
  }

  /**
   *
   */
  protected function loadSettings() {
    $this->settings = $this->loadConfiguration('settings');
  }

  /**
   * @param \Exception $e
   */
  protected function logException(\Exception $e) {
    $message = $e->getMessage() ?: get_class($e);
    $this->getLog()->critical($message, [$e->getFile(), $e->getLine()]);
  }

  /**
   * @param null|string $ns Namespace
   * @return \Stash\Pool
   */
  protected function makeCache($ns = null) {
    $driver = $this->getEnvironment()->getCacheDriver();
    $cache = new \Stash\Pool($driver);
    if (isset($ns)) $cache->setNamespace($ns);
    return $cache;
  }

  /**
   * @return \Outpost\Web\Client
   */
  protected function makeClient() {
    $client = new \GuzzleHttp\Client();
    $cache = $this->makeCache('http');
    return new Web\Client($client, $cache);
  }

  /**
   * @param string $name The name of the configuration file
   * @return string The path to the configuration file
   */
  protected function makeConfigurationPath($name) {
    return $this->getEnvironment()->getRootDirectory() . "/{$name}.json";
  }

  /**
   * @return \Monolog\Logger
   */
  protected function makeLog() {
    return new \Monolog\Logger('outpost', $this->getLogHandlers(), $this->getLogProcessors());
  }

  /**
   * @param string $config The content of the configuration file
   * @return array
   */
  protected function parseConfiguration($config) {
    return json_decode($config, true) ?: [];
  }
}
