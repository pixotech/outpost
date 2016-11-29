<?php

namespace Outpost\Content\Patterns\Feeds;

interface PostInterface {

  /**
   * @return \DateTime
   */
  public function getPostTime();
}
