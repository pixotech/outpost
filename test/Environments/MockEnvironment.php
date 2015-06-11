<?php

/**
 * @package Outpost
 * @author Pixo <info@pixotech.com>
 * @copyright 2015, Pixo
 * @license http://opensource.org/licenses/NCSA NCSA
 */

namespace Outpost\Environments;

use Monolog\Handler\TestHandler;
use Stash\Driver\Ephemeral;
use Symfony\Component\HttpFoundation\Request;

class MockEnvironment implements EnvironmentInterface {

  public $cacheDriver;
  public $logHandlers = [];
  public $request;
  public $root;

  public static function makeTestEnvironment(Request $request = null) {
    $environment = new MockEnvironment();
    $environment->cacheDriver = new Ephemeral();
    $environment->logHandlers = [new TestHandler()];
    $environment->request = $request;
    return $environment;
  }

  /**
   * @return null|\Stash\Interfaces\DriverInterface
   */
  public function getCacheDriver() {
    return $this->cacheDriver;
  }

  /**
   * @return \Monolog\Handler\HandlerInterface[]
   */
  public function getLogHandlers() {
    return $this->logHandlers;
  }

  /**
   * @return Request
   */
  public function getRequest() {
    return $this->request;
  }

  /**
   * @return string
   */
  public function getRootDirectory() {
    return $this->root;
  }
}