<?php

/**
 * @package Outpost
 * @author Pixo <info@pixotech.com>
 * @copyright 2015, Pixo
 * @license http://opensource.org/licenses/NCSA NCSA
 */

namespace Outpost\Web\Exceptions;

use Exception;
use GuzzleHttp\Message\ResponseInterface;
use Outpost\Recovery\HasDescriptionInterface;
use Outpost\Web\Requests\RequestInterface;

class ResponseException extends \Exception implements HasDescriptionInterface {

  protected $request;
  protected $response;

  public function __construct(RequestInterface $request, ResponseInterface $response) {
    $this->request = $request;
    $this->response = $response;
    $message = $response->getReasonPhrase() . ': ' . $response->getEffectiveUrl();
    $statusCode = $response->getStatusCode();
    parent::__construct($message, $statusCode);
  }

  public function getDescription() {
    $desc  = '<h1>' . $this->getResponseReasonPhrase() . '</h1>';

    $desc .= '<h2>Request</h2>';
    $desc .= @\Kint::dump($this->request);

    $desc .= '<h2>Response</h2>';
    $desc .= sprintf('<p><a href="%s">%s</a></p>', $this->getResponseUrl(), htmlentities($this->getResponseUrl()));
    $desc .= '<pre>' . htmlentities($this->getResponseBody()) . '</pre>';
    $desc .= @\Kint::dump($this->response);

    return $desc;
  }

  public function getRequest() {
    return $this->request;
  }

  public function getResponse() {
    return $this->response;
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