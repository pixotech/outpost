<?php

namespace Outpost\Web\Requests;

interface CacheableRequestInterface extends RequestInterface {
  public function getCacheKey();
  public function getCacheLifetime();
}