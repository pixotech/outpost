<?php

/**
 * @package Outpost
 * @author Pixo <info@pixotech.com>
 * @copyright 2015, Pixo
 * @license http://opensource.org/licenses/NCSA NCSA
 */

namespace Outpost\Responders;

interface ResponderInterface {

  /**
   * @return \Stash\Interfaces\PoolInterface
   */
  public function getCache();

  /**
   * @return \GuzzleHttp\ClientInterface
   */
  public function getClient();

  /**
   * @return \Psr\Log\LoggerInterface
   */
  public function getLog();

  /**
   * @return \Symfony\Component\HttpFoundation\Request
   */
  public function getRequest();

  /**
   * @return string
   */
  public function getRequestPath();

  /**
   * @return \Symfony\Component\HttpFoundation\Response
   */
  public function invoke();
}
