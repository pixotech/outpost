<?php

namespace Outpost\Web\Requests;

use GuzzleHttp\Message\ResponseInterface;

interface RequestInterface {

  /**
   * @return null|string
   */
  public function getRequestBody();

  /**
   * @return array
   */
  public function getRequestHeaders();

  /**
   * @return string
   */
  public function getRequestMethod();

  /**
   * @return array
   */
  public function getRequestOptions();

  /**
   * @return string
   */
  public function getRequestUrl();

  /**
   * @param ResponseInterface $response
   * @return mixed
   */
  public function processResponse(ResponseInterface $response);

  /**
   * @param ResponseInterface $response
   * @throws \Exception
   */
  public function validateResponse(ResponseInterface $response);
}