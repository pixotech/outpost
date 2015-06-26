<?php

namespace Outpost\Resources;

use Outpost\SiteInterface;

interface ResourceInterface {
  public function __invoke(SiteInterface $site);
}