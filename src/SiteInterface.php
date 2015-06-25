<?php

/**
 * @package Outpost
 * @author Pixo <info@pixotech.com>
 * @copyright 2015, Pixo
 * @license http://opensource.org/licenses/NCSA NCSA
 */

namespace Outpost;

use Outpost\Environments\EnvironmentInterface;
use Outpost\Resources\CacheableResourceInterface;
use Outpost\Resources\ResourceInterface;
use Symfony\Component\HttpFoundation\Request;

interface SiteInterface {

  /**
   * @param ResourceInterface $resource
   * @return mixed
   */
  public function get(ResourceInterface $resource);

  /**
   * @return \Outpost\Cache\CacheInterface
   */
  public function getCache();

  /**
   * @param CacheableResourceInterface $resource
   * @return mixed
   */
  public function getCachedResource(CacheableResourceInterface $resource);

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
   * @param ResourceInterface $resource
   * @return mixed
   */
  public function getUncachedResource(ResourceInterface $resource);

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
