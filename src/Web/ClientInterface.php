<?php

/**
 * @package Outpost
 * @author Pixo <info@pixotech.com>
 * @copyright 2015, Pixo
 * @license http://opensource.org/licenses/NCSA NCSA
 */

namespace Outpost\Web;

use Outpost\Web\Requests\RequestInterface;

interface ClientInterface {

  /**
   * @return \Stash\Interfaces\PoolInterface
   */
  public function getCache();

  /**
   * @return \GuzzleHttp\ClientInterface
   */
  public function getClient();

  /**
   * @var RequestInterface $request
   * @return mixed
   */
  public function send(RequestInterface $request);
}