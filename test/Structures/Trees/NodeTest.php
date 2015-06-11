<?php

/**
 * @package Outpost
 * @author Pixo <info@pixotech.com>
 * @copyright 2015, Pixo
 * @license http://opensource.org/licenses/NCSA NCSA
 */

namespace Outpost\Structures\Trees;

class NodeTest extends \PHPUnit_Framework_TestCase {

  public function testAddChildNode() {
    $node1 = new TestNode(1);
    $node2 = new TestNode(2);
    $node1->addChildNode($node2);
    $this->assertEquals($node2, $node1->children[0]);
  }

  public function testGetParent() {
    $node1 = new TestNode(1);
    $node2 = new TestNode(2);
    $node2->parent = $node1;
    $this->assertEquals($node1, $node2->getParent());
  }

  public function testHasParent() {
    $node1 = new TestNode(1);
    $node2 = new TestNode(2);
    $node2->parent = $node1;
    $this->assertFalse($node1->hasParent());
    $this->assertTrue($node2->hasParent());
  }

  public function testSetParent() {
    $node1 = new TestNode(1);
    $node2 = new TestNode(2);
    $node2->setParent($node1);
    $this->assertEquals($node1, $node2->parent);
  }

  public function testOffsetExists() {
    $node1 = new TestNode(1);
    $node2 = new TestNode(2);
    $node1->children[0] = $node2;
    $this->assertTrue($node1->offsetExists(0));
  }

  public function testOffsetGet() {
    $node1 = new TestNode(1);
    $node2 = new TestNode(2);
    $node1->children[0] = $node2;
    $this->assertEquals($node2, $node1->offsetGet(0));
  }

  /**
   * @expectedException \BadMethodCallException
   */
  public function testOffsetSet() {
    $node1 = new TestNode(1);
    $node2 = new TestNode(2);
    $node1->offsetSet(0, $node2);
  }

  /**
   * @expectedException \BadMethodCallException
   */
  public function testOffsetUnset() {
    $node1 = new TestNode(1);
    $node2 = new TestNode(2);
    $node1->addChildNode($node2);
    $node1->offsetUnset(0);
  }

  public function testCount() {
    $node1 = new TestNode(1);
    $node2 = new TestNode(2);
    $node1->children[0] = $node2;
    $this->assertCount(1, $node1);
  }

  public function testIteratorCurrent() {
    $parent = new TestNode(1);
    foreach (range(0, 4) as $i) {
      $parent->children[$i] = new TestNode($i + 2);
    }
    foreach (range(0, 4) as $i) {
      $parent->iteratorPosition = $i;
      $this->assertEquals($parent->children[$i], $parent->current());
    }
  }

  public function testIteratorKey() {
    $parent = new TestNode(1);
    foreach (range(0, 4) as $i) {
      $parent->children[$i] = new TestNode($i + 2);
    }
    foreach (range(0, 4) as $i) {
      $parent->iteratorPosition = $i;
      $this->assertEquals($i, $parent->key());
    }
  }

  public function testIteratorNext() {
    $parent = new TestNode(1);
    foreach (range(0, 4) as $i) {
      $parent->children[$i] = new TestNode($i + 2);
    }
    $parent->iteratorPosition = 0;
    $parent->next();
    $this->assertEquals(1, $parent->iteratorPosition);
  }

  public function testIteratorRewind() {
    $parent = new TestNode(1);
    foreach (range(0, 4) as $i) {
      $parent->children[$i] = new TestNode($i + 2);
    }
    $parent->iteratorPosition = 4;
    $parent->rewind();
    $this->assertEquals(0, $parent->iteratorPosition);
  }

  public function testIteratorValid() {
    $parent = new TestNode(1);
    foreach (range(0, 4) as $i) {
      $parent->children[$i] = new TestNode($i + 2);
    }
    foreach (range(0, 4) as $i) {
      $parent->iteratorPosition = $i;
      $this->assertTrue($parent->valid());
    }
  }

  public function testIteratorInvalid() {
    $parent = new TestNode(1);
    foreach (range(0, 4) as $i) {
      $parent->children[$i] = new TestNode($i + 2);
    }
    $parent->iteratorPosition = 5;
    $this->assertFalse($parent->valid());
  }

  public function testRecursiveIteratorGetChildren() {
    $parent = new TestNode(1);
    foreach (range(0, 4) as $i) {
      $parent->children[$i] = new TestNode($i + 2);
    }
    foreach (range(0, 4) as $i) {
      $parent->iteratorPosition = $i;
      $this->assertEquals($parent->children[$i], $parent->getChildren());
    }
  }

  public function testRecursiveIteratorHasChildren() {
    $parent = new TestNode(1);
    $parent->children[0] = new TestNode(2);
    $parent->children[0]->children[0] = new TestNode(3);
    $parent->iteratorPosition = 0;
    $this->assertTrue($parent->hasChildren());
  }

  public function testRecursiveIteratorHasNoChildren() {
    $parent = new TestNode(1);
    $parent->children[0] = new TestNode(2);
    $parent->iteratorPosition = 0;
    $this->assertFalse($parent->hasChildren());
  }
}