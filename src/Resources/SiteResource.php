<?php

namespace Outpost\Resources;

use Outpost\SiteInterface;

class SiteResource implements SiteResourceInterface {

  /**
   * @var SiteInterface
   */
  private $site;

  /**
   * @return SiteInterface
   */
  public function getSite() {
    return $this->site;
  }

  /**
   * @param SiteInterface $site
   */
  public function setSite(SiteInterface $site) {
    $this->site = $site;
  }

  /**
   * @param callable $resource
   * @return mixed
   */
  protected function get(callable $resource) {
    return $this->getSite()->get($resource);
  }
}