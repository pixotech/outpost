<?php

namespace Outpost\Mocks;

use Outpost\Assets\AssetInterface;
use Outpost\Assets\AssetManagerInterface;
use Outpost\Environments\EnvironmentInterface;
use Outpost\Events\EventInterface;
use Outpost\SiteInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Site implements SiteInterface {

  /**
   * @param string $key
   */
  public function clearAssetMarker($key) {
    // TODO: Implement clearAssetMarker() method.
  }

  /**
   * @param AssetInterface $asset
   */
  public function createAssetMarker(AssetInterface $asset) {
    // TODO: Implement createAssetMarker() method.
  }

  /**
   * @param callable $resource
   * @return mixed
   */
  public function get(callable $resource) {
    // TODO: Implement get() method.
  }

  /**
   * @param AssetInterface $asset
   * @return \SplFileInfo
   */
  public function getAssetFile(AssetInterface $asset) {
    // TODO: Implement getAssetFile() method.
  }

  /**
   * @param string $key
   * @return AssetInterface
   */
  public function getAssetMarker($key) {
    // TODO: Implement getAssetMarker() method.
  }

  /**
   * @param string $key
   * @return string
   */
  public function getAssetMarkerPath($key) {
    // TODO: Implement getAssetMarkerPath() method.
  }

  /**
   * @param AssetInterface $asset
   * @return string
   */
  public function getAssetUrl(AssetInterface $asset) {
    // TODO: Implement getAssetUrl() method.
  }

  /**
   * @return \Outpost\Cache\CacheInterface
   */
  public function getCache() {
    // TODO: Implement getCache() method.
  }

  /**
   * @return \Outpost\Web\ClientInterface
   */
  public function getClient() {
    return $this->client;
  }

  /**
   * @return EnvironmentInterface
   */
  public function getEnvironment() {
    // TODO: Implement getEnvironment() method.
  }

  /**
   * @return \Psr\Log\LoggerInterface
   */
  public function getLog() {
    // TODO: Implement getLog() method.
  }

  /**
   * @return string
   */
  public function getPublicDirectory() {
    // TODO: Implement getPublicRoot() method.
  }

  /**
   * @param string $name
   * @return mixed
   */
  public function getSetting($name) {
    // TODO: Implement getSetting() method.
  }

  /**
   * @param string $name
   * @return mixed
   */
  public function getSecret($name) {
    // TODO: Implement getSecret() method.
  }

  /**
   * @return \Twig_Environment
   */
  public function getTwig() {
    // TODO: Implement getTwig() method.
  }

  /**
   * @param EventInterface $event
   */
  public function handleEvent(EventInterface $event) {
    // TODO: Implement handleEvent() method.
  }

  /**
   * @param string $key
   * @return bool
   */
  public function hasAssetMarker($key) {
    // TODO: Implement hasAssetMarker() method.
  }

  /**
   * @param AssetInterface $asset
   * @return bool
   */
  public function hasLocalAsset(AssetInterface $asset) {
    // TODO: Implement hasLocalAsset() method.
  }

  /**
   * @param string $name
   * @return bool
   */
  public function hasSetting($name) {
    // TODO: Implement hasSetting() method.
  }

  /**
   * @param string $name
   * @return bool
   */
  public function hasSecret($name) {
    // TODO: Implement hasSecret() method.
  }

  /**
   * @param null|Request $request
   * @return \Symfony\Component\HttpFoundation\Response
   */
  public function invoke(Request $request = NULL) {
    // TODO: Implement invoke() method.
  }

  /**
   * @return AssetManagerInterface
   */
  public function getAssetManager() {
    // TODO: Implement getAssetManager() method.
  }

  /**
   * @param Request $request
   * @return Response
   */
  public function getResponse(Request $request) {
    // TODO: Implement getResponse() method.
  }

  /**
   * @param $template
   * @param array $variables
   * @return string
   */
  public function render($template, array $variables = []) {
    // TODO: Implement render() method.
  }

  /**
   * @param null|Request $request
   */
  public function respond(Request $request) {
    // TODO: Implement respond() method.
  }
}