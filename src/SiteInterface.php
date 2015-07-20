<?php

/**
 * @package Outpost
 * @author Pixo <info@pixotech.com>
 * @copyright 2015, Pixo
 * @license http://opensource.org/licenses/NCSA NCSA
 */

namespace Outpost;

use Outpost\Assets\AssetInterface;
use Outpost\Environments\EnvironmentInterface;
use Outpost\Cache\CacheableInterface;
use Outpost\Events\EventInterface;
use Outpost\Resources\ResourceInterface;
use Symfony\Component\HttpFoundation\Request;

interface SiteInterface {

  /**
   * @param string $key
   */
  public function clearAssetMarker($key);

  /**
   * @param AssetInterface $asset
   */
  public function createAssetMarker(AssetInterface $asset);

  /**
   * @param callable $resource
   * @return mixed
   */
  public function get(callable $resource);

  /**
   * @param AssetInterface $asset
   * @return \SplFileInfo
   */
  public function getAssetFile(AssetInterface $asset);

  /**
   * @param string $key
   * @return AssetInterface
   */
  public function getAssetMarker($key);

  /**
   * @param string $key
   * @return string
   */
  public function getAssetMarkerPath($key);

  /**
   * @param AssetInterface $asset
   * @return string
   */
  public function getAssetUrl(AssetInterface $asset);

  /**
   * @return \Outpost\Cache\CacheInterface
   */
  public function getCache();

  /**
   * @return \Outpost\Web\ClientInterface
   */
  public function getClient();

  /**
   * @return EnvironmentInterface
   */
  public function getEnvironment();

  /**
   * @return \Psr\Log\LoggerInterface
   */
  public function getLog();

  /**
   * @return string
   */
  public function getPublicDirectory();

    /**
   * @param string $name
   * @return mixed
   */
  public function getSetting($name);

  /**
   * @param string $name
   * @return mixed
   */
  public function getSecret($name);

  /**
   * @return \Twig_Environment
   */
  public function getTwig();

  /**
   * @param EventInterface $event
   */
  public function handleEvent(EventInterface $event);

  /**
   * @param string $key
   * @return bool
   */
  public function hasAssetMarker($key);

  /**
   * @param AssetInterface $asset
   * @return bool
   */
  public function hasLocalAsset(AssetInterface $asset);

  /**
   * @param string $name
   * @return bool
   */
  public function hasSetting($name);

  /**
   * @param string $name
   * @return bool
   */
  public function hasSecret($name);

  /**
   * @param null|Request $request
   * @return \Symfony\Component\HttpFoundation\Response
   */
  public function invoke(Request $request = null);
}
