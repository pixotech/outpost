<?php

namespace Outpost\Resources;

use Outpost\SiteInterface;

interface ResourceInterface {
  public function invoke(SiteInterface $site);
}