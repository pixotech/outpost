<?php

/**
 * @package Outpost
 * @author Pixo <info@pixotech.com>
 * @copyright 2016, Pixo
 * @license http://opensource.org/licenses/NCSA NCSA
 */

namespace Outpost;

use Outpost\Events\EventInterface;
use Symfony\Component\HttpFoundation\Request;

interface SiteInterface {

  /**
   * @param callable $resource
   * @return mixed
   */
  public function get(callable $resource);

  /**
   * @return \Outpost\Cache\CacheInterface
   */
  public function getCache();

  /**
   * @return \GuzzleHttp\ClientInterface
   */
  public function getClient();

    /**
   * @return \Phroute\Phroute\RouteCollector
   */
  public function getRouter();

  /**
   * @param EventInterface $event
   */
  public function report(EventInterface $event);

    /**
   * @param Request $request
   */
  public function respond(Request $request);

  /**
   * @param string $method
   * @param string $path
   * @param callable $handler
   */
  public function route($method, $path, callable $handler);

  /**
   * @param callable $listener
   */
  public function subscribe(callable $listener);
}
