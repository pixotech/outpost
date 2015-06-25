<?php

namespace Outpost\Resources;

interface CacheableResourceInterface extends ResourceInterface {
  public function getCacheKey();
  public function getCacheLifetime();
}