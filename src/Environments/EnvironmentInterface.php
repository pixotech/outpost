<?php

/**
 * @package Outpost
 * @author Pixo <info@pixotech.com>
 * @copyright 2015, Pixo
 * @license http://opensource.org/licenses/NCSA NCSA
 */

namespace Outpost\Environments;

interface EnvironmentInterface {

  /**
   * @return string
   */
  public function getAssetBaseUrl();

  /**
   * @return string
   */
  public function getAssetCacheDirectory();

  /**
   * @return null|\Stash\Interfaces\DriverInterface
   */
  public function getCacheDriver();

  /**
   * @return string
   */
  public function getGeneratedAssetsDirectory();

  /**
   * @return string
   */
  public function getPublicDirectory();

  /**
   * @return string
   */
  public function getRootDirectory();

  /**
   * @param string $name
   * @return mixed
   */
  public function getSetting($name);

  /**
   * @return array
   */
  public function getSettings();

  /**
   * @param string $name
   * @return mixed
   */
  public function getSecret($name);

  /**
   * @return array
   */
  public function getSecrets();

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
}