<?php

/**
 * @package Outpost
 * @author Pixo <info@pixotech.com>
 * @copyright 2015, Pixo
 * @license http://opensource.org/licenses/NCSA NCSA
 */

namespace Outpost;

use Outpost\Assets\AssetManagerInterface;
use Outpost\Environments\EnvironmentInterface;
use Outpost\Events\EventInterface;
use Outpost\Events\ListenerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

interface SiteInterface {

  public function addListener(ListenerInterface $listener);

  /**
   * @param callable $resource
   * @return mixed
   */
  public function get(callable $resource);

  /**
   * @return AssetManagerInterface
   */
  public function getAssetManager();

  /**
   * @return \Outpost\Cache\CacheInterface
   */
  public function getCache();

  /**
   * @return EnvironmentInterface
   */
  public function getEnvironment();

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
   * @param Request $request
   * @return Response
   */
  public function makeResponse(Request $request);

  /**
   * @param EventInterface $event
   */
  public function report(EventInterface $event);

  /**
   * @param Request $request
   * @return Response
   */
  public function respond(Request $request);
}
