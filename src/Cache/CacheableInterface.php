<?php

namespace Outpost\Cache;

interface CacheableInterface {
  public function getCacheKey();
  public function getCacheLifetime();
}