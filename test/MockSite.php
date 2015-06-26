<?php

namespace Outpost;

use Outpost\Environments\EnvironmentInterface;
use Outpost\Resources\CacheableResourceInterface;
use Outpost\Resources\ResourceInterface;
use Outpost\Web\MockClient;
use Symfony\Component\HttpFoundation\Request;

class MockSite implements SiteInterface {

  public $client;
  public $twig;

  public function __construct() {
    $this->client = new MockClient();
  }

  /**
   * @param ResourceInterface $resource
   * @return mixed
   */
  public function get(ResourceInterface $resource) {
    // TODO: Implement get() method.
  }

  /**
   * @return \Outpost\Cache\CacheInterface
   */
  public function getCache() {
    // TODO: Implement getCache() method.
  }

  /**
   * @param CacheableResourceInterface $resource
   * @return mixed
   */
  public function getCachedResource(CacheableResourceInterface $resource) {
    // TODO: Implement getCachedResource() method.
  }

  /**
   * @return \Outpost\Web\ClientInterface
   */
  public function getClient() {
    return $this->client;
  }

  /**
   * @return EnvironmentInterface
   */
  public function getEnvironment() {
    // TODO: Implement getEnvironment() method.
  }

  /**
   * @return \Psr\Log\LoggerInterface
   */
  public function getLog() {
    // TODO: Implement getLog() method.
  }

  /**
   * @param string $name
   * @return mixed
   */
  public function getSetting($name) {
    // TODO: Implement getSetting() method.
  }

  /**
   * @param string $name
   * @return mixed
   */
  public function getSecret($name) {
    // TODO: Implement getSecret() method.
  }

  /**
   * @return mixed
   */
  public function getTwig() {
    return $this->twig;
  }

  /**
   * @param ResourceInterface $resource
   * @return mixed
   */
  public function getUncachedResource(ResourceInterface $resource) {
    // TODO: Implement getUncachedResource() method.
  }

  /**
   * @param string $name
   * @return bool
   */
  public function hasSetting($name) {
    // TODO: Implement hasSetting() method.
  }

  /**
   * @param string $name
   * @return bool
   */
  public function hasSecret($name) {
    // TODO: Implement hasSecret() method.
  }

  /**
   * @param null|Request $request
   * @return \Symfony\Component\HttpFoundation\Response
   */
  public function invoke(Request $request = NULL) {
    // TODO: Implement invoke() method.
  }
}