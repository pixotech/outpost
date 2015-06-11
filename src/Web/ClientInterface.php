<?php

/**
 * @package Outpost
 * @author Pixo <info@pixotech.com>
 * @copyright 2015, Pixo
 * @license http://opensource.org/licenses/NCSA NCSA
 */

namespace Outpost\Web;

interface ClientInterface {

  public function get($url = null, $options = [], $key = null, $lifetime = null);

  /**
   * @return \Stash\Interfaces\PoolInterface
   */
  public function getCache();

  /**
   * @return \GuzzleHttp\ClientInterface
   */
  public function getClient();
}