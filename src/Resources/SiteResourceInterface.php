<?php

namespace Outpost\Resources;

use Outpost\SiteInterface;

interface SiteResourceInterface extends ResourceInterface {

  /**
   * @return SiteInterface
   */
  public function getSite();

  /**
   * @param SiteInterface $site
   */
  public function setSite(SiteInterface $site);
}