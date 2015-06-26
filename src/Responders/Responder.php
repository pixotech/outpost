<?php

/**
 * @package Outpost
 * @author Pixo <info@pixotech.com>
 * @copyright 2015, Pixo
 * @license http://opensource.org/licenses/NCSA NCSA
 */

namespace Outpost\Responders;

use Outpost\Html\Document;
use Outpost\SiteInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

abstract class Responder implements ResponderInterface {

  protected $request;

  /**
   * @var SiteInterface
   */
  protected $site;

  public function __construct(SiteInterface $site, Request $request) {
    $this->site = $site;
    $this->request = $request;
  }

  /**
   * @return \Stash\Interfaces\PoolInterface
   */
  public function getCache() {
    return $this->site->getCache();
  }

  /**
   * @return \GuzzleHttp\ClientInterface
   */
  public function getClient() {
    return $this->site->getClient();
  }

  /**
   * @return \Psr\Log\LoggerInterface
   */
  public function getLog() {
    return $this->site->getLog();
  }

  /**
   * @return \Symfony\Component\HttpFoundation\Request
   */
  public function getRequest() {
    return $this->request;
  }

  /**
   * @return string
   */
  public function getRequestPath() {
    return $this->getRequest()->getPathInfo();
  }

  /**
   * @return SiteInterface
   */
  public function getSite() {
    return $this->site;
  }

  /**
   * @return \Twig_Environment
   */
  public function getTwig() {
    return $this->site->getTwig();
  }

  /**
   * @return Response
   */
  abstract function invoke();

  protected function makeDocument($body, $title = null) {
    return new Document($body, $title);
  }

  protected function makeResponse($content = '', $status = 200, $headers = array()) {
    return new Response((string)$content, $status, $headers);
  }
}