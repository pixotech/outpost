<?php

/**
 * @package Outpost
 * @author Pixo <info@pixotech.com>
 * @copyright 2015, Pixo
 * @license http://opensource.org/licenses/NCSA NCSA
 */

namespace Outpost\Mocks\Environments;

use Monolog\Handler\TestHandler;
use Outpost\Environments\EnvironmentInterface;
use Stash\Driver\Ephemeral;
use Symfony\Component\HttpFoundation\Request;

class Environment implements EnvironmentInterface {

  public $assetCacheDirectory;
  public $cacheDriver;
  public $logHandlers = [];
  public $request;
  public $root;
  public $secrets = [];
  public $settings = [];
  public $twigLoader;
  public $twigOptions = [];

  public static function makeTestEnvironment(Request $request = null) {
    $environment = new Environment();
    $environment->cacheDriver = new Ephemeral();
    $environment->logHandlers = [new TestHandler()];
    $environment->request = $request;
    return $environment;
  }

  /**
   * @return string
   */
  public function getAssetCacheDirectory() {
    return $this->assetCacheDirectory;
  }

  /**
   * @return null|\Stash\Interfaces\DriverInterface
   */
  public function getCacheDriver() {
    return $this->cacheDriver;
  }

  /**
   * @return string
   */
  public function getGeneratedAssetsDirectory() {
    // TODO: Implement getGeneratedAssetsDirectory() method.
  }

  /**
   * @return \Monolog\Handler\HandlerInterface[]
   */
  public function getLogHandlers() {
    return $this->logHandlers;
  }

  /**
   * @return string
   */
  public function getPublicDirectory() {
    // TODO: Implement getPublicDirectory() method.
  }

  /**
   * @return Request
   */
  public function getRequest() {
    // TODO: Implement getRequest() method.
  }

  /**
   * @return string
   */
  public function getRootDirectory() {
    // TODO: Implement getRootDirectory() method.
  }

  /**
   * @param string $name
   * @return mixed
   */
  public function getSetting($name) {
    return $this->settings[$name];
  }

  /**
   * @return array
   */
  public function getSettings() {
    return $this->settings;
  }

  /**
   * @param string $name
   * @return mixed
   */
  public function getSecret($name) {
    return $this->secrets[$name];
  }

  /**
   * @return array
   */
  public function getSecrets() {
    return $this->secrets;
  }

  /**
   * @return \Twig_LoaderInterface
   */
  public function getTwigLoader() {
    // TODO: Implement getTwigLoader() method.
  }

  /**
   * @return array
   */
  public function getTwigOptions() {
    // TODO: Implement getTwigOptions() method.
  }

  /**
   * @param string $name
   * @return bool
   */
  public function hasSetting($name) {
    return isset($this->settings[$name]);
  }

  /**
   * @param string $name
   * @return bool
   */
  public function hasSecret($name) {
    return isset($this->secrets[$name]);
  }

  /**
   * @return string
   */
  public function getAssetBaseUrl() {
    // TODO: Implement getAssetBaseUrl() method.
  }
}