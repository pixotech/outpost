<?php

namespace Outpost\Structures\Trees;

class RootNodeTest extends \PHPUnit_Framework_TestCase {

  public function testConstructEmpty() {
    new RootNode();
  }

  public function testConstructWithNodes() {
    $nodes[] = new TestNode(1);
    $nodes[] = new TestNode(2, 1);
    $nodes[] = new TestNode(3, 1);
    $nodes[] = new TestNode(4, 2);
    $tree = new RootNode($nodes);
    $this->assertCount(1, $tree);
    $this->assertCount(2, $tree->offsetGet(0));
    $this->assertCount(1, $tree->offsetGet(0)->offsetGet(0));
    $this->assertCount(0, $tree->offsetGet(0)->offsetGet(1));
  }

  public function testIterator() {
    $nodes[] = new TestNode(1);
    $nodes[] = new TestNode(2, 1);
    $nodes[] = new TestNode(3, 1);
    $nodes[] = new TestNode(4, 2);
    $nodes[] = new TestNode(5);
    $tree = new RootNode($nodes);

    $items = [];
    foreach ($tree as $item) {
      $items[] = $item;
    }
    $this->assertCount(2, $items);
  }

  public function testRecursiveIterator() {
    $nodes[] = new TestNode(1);
    $nodes[] = new TestNode(2, 1);
    $nodes[] = new TestNode(3, 1);
    $nodes[] = new TestNode(4, 2);
    $tree = new RootNode($nodes);

    $items = [];
    foreach (new \RecursiveIteratorIterator($tree, \RecursiveIteratorIterator::SELF_FIRST) as $item) {
      $items[] = $item;
    }
    $this->assertCount(4, $items);
  }
}