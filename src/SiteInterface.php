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
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

interface SiteInterface {

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
   * @param Request $request
   * @return Response
   */
  public function getResponse(Request $request);

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
   * @param $template
   * @param array $variables
   * @return string
   */
  public function render($template, array $variables = []);

  /**
   * @param null|Request $request
   */
  public function respond(Request $request);
}
