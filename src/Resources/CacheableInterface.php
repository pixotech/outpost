<?php

namespace Outpost\Resources;

interface CacheableInterface {
  public function getCacheKey();
  public function getCacheLifetime();
}