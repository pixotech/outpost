<?php

namespace Outpost\Content\Patterns\Feeds;

/*
 * Feeds
 *
 * Feeds are collections of posts. The most recent posts appear first.
 */
interface FeedInterface extends \IteratorAggregate {

  /**
   * @param PostInterface $post
   */
  public function add(PostInterface $post);
}
