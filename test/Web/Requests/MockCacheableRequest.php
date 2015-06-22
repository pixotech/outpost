<?php

namespace Outpost\Web\Requests;

class MockCacheableRequest extends MockRequest implements CacheableRequestInterface {

  public $cacheKey;

  public function getCacheKey() {
    return $this->cacheKey;
  }

  public function getCacheLifetime() {
    // TODO: Implement getCacheLifetime() method.
  }

  /**
   * @param mixed $response
   * @return mixed
   */
  public function handleCachedResponse($response) {
    // TODO: Implement handleCachedResponse() method.
  }

  /**
   * @param mixed $response
   * @return mixed
   */
  public function prepareResponseForCache($response) {
    // TODO: Implement makeCachedResponse() method.
  }

  /**
   * Prepare a response that has been retrieved from the cache
   *
   * @param mixed $cachedResponse
   * @return mixed
   */
  public function prepareResponseFromCache($cachedResponse) {
    // TODO: Implement prepareResponseFromCache() method.
  }
}