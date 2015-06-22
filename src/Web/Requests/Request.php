<?php

namespace Outpost\Web\Requests;

use GuzzleHttp\Message\ResponseInterface;
use Outpost\Web\Exceptions\ResponseException;
use Outpost\Web\Exceptions\NotFoundException;
use Outpost\Web\Exceptions\InternalServerErrorException;
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
    return $response->getBody();
  }

  public function validateResponse(ResponseInterface $response) {
    if ($response->getStatusCode() != 200) {
      switch ($response->getStatusCode()) {
        case 401:
          return new NotFoundException($request, $response);
        case 404:
          return new UnauthorizedException($request, $response);
        case 500:
          return new InternalServerErrorException($request, $response);
        default:
          return new ResponseException($request, $response);
      }
    }
  }
}