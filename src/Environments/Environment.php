<?php

/**
 * @package Outpost
 * @author Pixo <info@pixotech.com>
 * @copyright 2015, Pixo
 * @license http://opensource.org/licenses/NCSA NCSA
 */

namespace Outpost\Environments;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Outpost\Log\LogFormatter;
use Stash\Driver\FileSystem;
use Symfony\Component\HttpFoundation\Request;

class Environment implements EnvironmentInterface {

  /**
   * @var string
   */
  protected $rootDirectory;

  /**
   * @var array
   */
  protected $secrets;

  /**
   * @var array
   */
  protected $settings;

  /**
   * @var string
   */
  protected $timezone = 'America/Chicago';

  public function __construct($root, Request $request = null) {
    if (!is_dir($root)) {
      throw new Exceptions\InvalidPathException($root);
    }
    $this->rootDirectory = realpath($root);
    $this->request = $request ?: Request::createFromGlobals();
    $this->loadSettings();
    $this->loadSecrets();
    $this->adjust();
  }

  public function getAssetCacheDirectory($ensure = true) {
    $dir = $this->getRootDirectory() . '/cache/assets';
    if ($ensure) $this->ensureDirectory($dir);
    return $dir;
  }

  public function getCacheDriver() {
    return null;
  }

  public function getGeneratedAssetsDirectory($ensure = true) {
    $dir = $this->getPublicDirectory() . '/_assets';
    if ($ensure) $this->ensureDirectory($dir);
    return $dir;
  }

  public function getLogHandlers() {
    return [];
  }

  public function getPublicDirectory($ensure = true) {
    $dir = $this->getRootDirectory() . '/docroot';
    if ($ensure) $this->ensureDirectory($dir);
    return $dir;
  }

  public function getRequest() {
    return $this->request;
  }

  public function getRootDirectory() {
    return $this->rootDirectory;
  }

  /**
   * @param string $key
   * @return mixed
   */
  public function getSecret($key) {
    return $this->getSecrets()[$key];
  }

  /**
   * @return array
   */
  public function getSecrets() {
    return $this->secrets;
  }

  /**
   * @param string $key
   * @return mixed
   */
  public function getSetting($key) {
    return $this->getSettings()[$key];
  }

  /**
   * @return array
   */
  public function getSettings() {
    return $this->settings;
  }

  public function getTwigLoader() {
    return null;
  }

  public function getTwigOptions() {
    return [];
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

  protected function adjust() {
    $this->configureErrorReporting();
    $this->configureTimezone();
  }

  protected function configureErrorReporting() {
    $this->hideErrors();
  }

  protected function configureTimezone() {
    date_default_timezone_set($this->timezone);
  }

  protected function ensureDirectory($dir) {
    if (!is_dir($dir)) {
      $this->ensureDirectory(dirname($dir));
      mkdir($dir);
    }
  }

  protected function hideErrors() {
    ini_set('display_errors', 0);
    error_reporting(0);
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
   * @param string $name The name of the configuration file
   * @return string The path to the configuration file
   */
  protected function makeConfigurationPath($name) {
    return $this->getRootDirectory() . "/{$name}.json";
  }

  protected function makeLocalCacheDriver($localPath) {
    $cachePath = $this->rootDirectory . '/' . $localPath;
    $this->ensureDirectory($cachePath);
    $driver = new FileSystem();
    $driver->setOptions(['dirSplit' => 2, 'path' => $cachePath]);
    return $driver;
  }

  protected function makeLocalLogHandler($localPath, $level = Logger::DEBUG) {
    $logPath = $this->rootDirectory . '/' . $localPath;
    $this->ensureDirectory(dirname($logPath));
    $handler = new StreamHandler($logPath, $level);
    $handler->setFormatter(new LogFormatter());
    return $handler;
  }

  /**
   * @param string $config The content of the configuration file
   * @return array
   */
  protected function parseConfiguration($config) {
    return json_decode($config, true) ?: [];
  }

  protected function showErrors($level = E_ALL) {
    ini_set('display_errors', 1);
    error_reporting($level);
  }
}