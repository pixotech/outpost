<?php

namespace Outpost;

interface ResourceInterface {
  public function __invoke(SiteInterface $site);
}