<?php

namespace Outpost\Content\Patterns\Feeds;

class Feed implements FeedInterface {

  protected $posts = [];

  public function add(PostInterface $post) {
    $this->posts[] = $post;
    $this->sort();
  }

  public function getIterator() {
    return new \ArrayIterator($this->posts);
  }

  protected function sort() {
    $cmp = function (PostInterface $a, PostInterface $b) {
      return $a->getPostTime() == $b->getPostTime() ? 0 : ($a->getPostTime() > $b->getPostTime() ? -1 : 1);
    };
    usort($this->posts, $cmp);
  }
}
