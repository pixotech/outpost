<?php

namespace Outpost\Web\Requests;

use GuzzleHttp\Message\ResponseInterface;
use Outpost\Web\Exceptions\BadRequestException;
use Outpost\Web\Exceptions\ClientErrorException;
use Outpost\Web\Exceptions\ForbiddenException;
use Outpost\Web\Exceptions\NotImplementedException;
use Outpost\Web\Exceptions\ResponseException;
use Outpost\Web\Exceptions\NotFoundException;
use Outpost\Web\Exceptions\InternalServerErrorException;
use Outpost\Web\Exceptions\ServerErrorException;
use Outpost\Web\Exceptions\UnauthorizedException;

class Request implements RequestInterface {

  protected $authPassword;
  protected $authUsername;
  protected $method;
  protected $url;

  public function __construct($url, $method = 'GET') {
    $this->url = $url;
    $this->method = $method;
  }

  public function authenticate($username, $password) {
    $this->authUsername = $username;
    $this->authPassword = $password;
  }

  public function getAuthenticationPassword() {
    return $this->authPassword;
  }

  public function getAuthenticationUsername() {
    return $this->authUsername;
  }

  public function getRequestBody() {
    return null;
  }

  public function getRequestHeaders() {
    $headers = [];
    $headers += $this->makeAuthenticationHeaders();
    return $headers;
  }

  public function getRequestMethod() {
    return $this->method;
  }

  public function getRequestOptions() {
    return [];
  }

  public function getRequestUrl() {
    return $this->url;
  }

  public function makeAuthenticationHeaders() {
    $headers = [];
    $username = $this->getAuthenticationUsername();
    $password = $this->getAuthenticationPassword();
    if ($username && $password) $headers['Authorization'] = 'Basic ' . base64_encode("$username:$password");
    return $headers;
  }

  public function processResponse(ResponseInterface $response) {
    return (string)$response->getBody();
  }

  public function validateResponse(ResponseInterface $response) {
    $statusCode = (string)$response->getStatusCode();
    if ($statusCode != '200') {
      switch ($statusCode) {
        case '400':
          throw new BadRequestException($this, $response);
        case '401':
          throw new UnauthorizedException($this, $response);
        case '403':
          throw new ForbiddenException($this, $response);
        case '404':
          throw new NotFoundException($this, $response);
        case '500':
          throw new InternalServerErrorException($this, $response);
        case '501':
          throw new NotImplementedException($this, $response);
        default:
          if ($statusCode[0] == '4') throw new ClientErrorException($this, $response);
          elseif ($statusCode[0] == '5') throw new ServerErrorException($this, $response);
          else throw new ResponseException($this, $response);
      }
    }
  }
}