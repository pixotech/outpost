<?php

/**
 * @package Outpost
 * @author Pixo <info@pixotech.com>
 * @copyright 2015, Pixo
 * @license http://opensource.org/licenses/NCSA NCSA
 */

namespace Outpost\Web;

use GuzzleHttp\ClientInterface as GuzzleClientInterface;
use Stash\Interfaces\PoolInterface;

class Client implements ClientInterface {

  protected $cache;
  protected $client;

  public function __construct(GuzzleClientInterface $client, PoolInterface $cache) {
    $this->client = $client;
    $this->cache = $cache;
  }

  public function get($url = null, $options = [], $key = null, $lifetime = null) {
    if (!isset($key)) $key = 'http/' . md5($url . serialize($options));
    $cached = $this->getCache()->getItem($key);
    $response = $cached->get();
    if ($cached->isMiss()) {
      $cached->lock();
      $response = $this->getUncached($url, $options);
      $cached->set($response, $lifetime);
    }
    return $response;
  }

  /**
   * @return PoolInterface
   */
  public function getCache() {
    return $this->cache;
  }

  /**
   * @return GuzzleClientInterface
   */
  public function getClient() {
    return $this->client;
  }

  protected function getUncached($url = null, $options = []) {
    $response = $this->getClient()->get($url, $options);
    return (string)$response->getBody();
  }
}