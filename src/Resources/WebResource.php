<?php

namespace Outpost\Resources;

use GuzzleHttp\Message\ResponseInterface;
use Outpost\SiteInterface;
use Outpost\Web\Exceptions\BadRequestException;
use Outpost\Web\Exceptions\ClientErrorException;
use Outpost\Web\Exceptions\ForbiddenException;
use Outpost\Web\Exceptions\NotImplementedException;
use Outpost\Web\Exceptions\ResponseException;
use Outpost\Web\Exceptions\NotFoundException;
use Outpost\Web\Exceptions\InternalServerErrorException;
use Outpost\Web\Exceptions\ServerErrorException;
use Outpost\Web\Exceptions\UnauthorizedException;

class WebResource implements WebResourceInterface {

  protected $method;
  protected $url;

  public function __construct($url, $method = 'GET') {
    $this->url = $url;
    $this->method = $method;
  }

  /**
   * @return \GuzzleHttp\Message\Request
   */
  public function getRequest() {
    return new \Outpost\Web\Requests\Request($this->url, $this->method);
  }

  public function invoke(SiteInterface $site) {
    $response = $site->getClient()->send($this->getRequest());
    $this->validateResponse($response);
    return $this->processResponse($response);
  }

  public function processResponse(ResponseInterface $response) {
    return (string)$response->getBody();
  }

  public function validateResponse(ResponseInterface $response) {
    $statusCode = (string)$response->getStatusCode();
    if ($statusCode != '200') {
      switch ($statusCode) {
        case '400':
          throw new BadRequestException($this->getRequest(), $response);
        case '401':
          throw new UnauthorizedException($this->getRequest(), $response);
        case '403':
          throw new ForbiddenException($this->getRequest(), $response);
        case '404':
          throw new NotFoundException($this->getRequest(), $response);
        case '500':
          throw new InternalServerErrorException($this->getRequest(), $response);
        case '501':
          throw new NotImplementedException($this->getRequest(), $response);
        default:
          if ($statusCode[0] == '4') throw new ClientErrorException($this->getRequest(), $response);
          elseif ($statusCode[0] == '5') throw new ServerErrorException($this->getRequest(), $response);
          else throw new ResponseException($this->getRequest(), $response);
      }
    }
  }
}