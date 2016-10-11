<?php

/**
 * @package Outpost
 * @author Pixo <info@pixotech.com>
 * @copyright 2016, Pixo
 * @license http://opensource.org/licenses/NCSA NCSA
 */

namespace Outpost;

use Psr\Log\LoggerInterface;
use Stash\Pool;
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
     * @return Pool
     */
    public function getCache();

    /**
     * @return LoggerInterface
     */
    public function getLog();

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
     * @param string $message
     * @param mixed $level
     */
    public function log($message, $level = null);

    /**
     * @param string $className
     * @param array $variables
     * @return mixed
     */
    public function make($className, array $variables);

    /**
     * @param \Exception $error
     */
    public function recover(\Exception $error);

    /**
     * @param Request $request
     */
    public function respond(Request $request);
}
