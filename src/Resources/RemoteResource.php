<?php

namespace Outpost\Resources;

class RemoteResource extends SiteResource implements RemoteResourceInterface {

  public function __invoke() {
    return $this->getClient()->send($this->getRequest());
  }

  /**
   * @return \GuzzleHttp\Message\RequestInterface
   */
  public function getRequest() {
    $method = $this->getRequestMethod();
    $url = $this->getRequestUrl();
    $options = $this->getRequestOptions();
    return $this->getClient()->createRequest($method, $url, $options);
  }

  /**
   * @return \GuzzleHttp\ClientInterface
   */
  protected function getClient() {
    return $this->getSite()->getClient();
  }

  /**
   * @return array
   */
  protected function getQuery() {
    return [];
  }

  /**
   * @return string
   */
  protected function getRequestMethod() {
    return 'GET';
  }

  /**
   * @return array
   */
  protected function getRequestOptions() {
    $options = [];
    if ($query = $this->getQuery()) $options['query'] = $query;
    return $options;
  }

  /**
   * @return string
   */
  protected function getRequestUrl() {
    return '';
  }
}