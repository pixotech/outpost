<?php

/**
 * @package Outpost
 * @author Pixo <info@pixotech.com>
 * @copyright 2015, Pixo
 * @license http://opensource.org/licenses/NCSA NCSA
 */

namespace Outpost\Environments;

use Symfony\Component\HttpFoundation\Request;

interface EnvironmentInterface {

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
   * @return \Monolog\Handler\HandlerInterface[]
   */
  public function getLogHandlers();

  /**
   * @return string
   */
  public function getPublicDirectory();

  /**
   * @return Request
   */
  public function getRequest();

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
   * @return \Twig_LoaderInterface
   */
  public function getTwigLoader();

  /**
   * @return array
   */
  public function getTwigOptions();

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