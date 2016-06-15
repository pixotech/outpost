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

interface SiteInterface
{

    /**
     * @param string $method
     * @param string $path
     * @param callable $handler
     * @param string $name
     */
    public function addRoute($method, $path, callable $handler, $name = null);

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
     * @param string $name
     * @param array $parameters
     * @return string
     */
    public function getUrl($name, array $parameters = []);

    /**
     * @param Request $request
     */
    public function respond(Request $request);
}
