<?php

/**
 * @package Outpost
 * @author Pixo <info@pixotech.com>
 * @copyright 2015, Pixo
 * @license http://opensource.org/licenses/NCSA NCSA
 */

namespace Outpost\Web\Exceptions;

use GuzzleHttp\Message\ResponseInterface;
use Outpost\Recovery\HasDescriptionInterface;

abstract class ClientException extends \Exception implements HasDescriptionInterface {

  protected $response;

  public function __construct(ResponseInterface $response) {
    $message = $response->getReasonPhrase() . ': ' . $response->getEffectiveUrl();
    parent::__construct($message, $response->getStatusCode());
    $this->response = $response;
  }

  public function getDescription() {
    $desc  = '<h1>' . $this->getResponseReasonPhrase() . '</h1>';
    $desc .= sprintf('<p><a href="%s">%s</a></p>', $this->getResponseUrl(), htmlentities($this->getResponseUrl()));
    $desc .= '<pre>' . $this->getResponseBody() . '</pre>';
    $desc .= @\Kint::dump($this->response);
    return $desc;
  }

  public function getResponseBody() {
    return (string)$this->response->getBody();
  }

  public function getResponseReasonPhrase() {
    return (string)$this->response->getReasonPhrase();
  }

  public function getResponseUrl() {
    return (string)$this->response->getEffectiveUrl();
  }
}