<?php

/**
 * @package Outpost
 * @author Pixo <info@pixotech.com>
 * @copyright 2015, Pixo
 * @license http://opensource.org/licenses/NCSA NCSA
 */

namespace Outpost\Structures\Trees;

trait HasChildren {

  protected $children = array();
  protected $iteratorPosition = 0;

  public function addChildNode(NodeInterface $node) {
    $node->setParent($this);
    $this->children[] = $node;
  }

  // Array access

  public function offsetExists($offset) {
    return array_key_exists($offset, $this->children);
  }

  public function offsetGet($offset) {
    return $this->children[$offset];
  }

  public function offsetSet($offset, $value) {
    throw new \BadMethodCallException(); // For now
  }

  public function offsetUnset($offset) {
    throw new \BadMethodCallException(); // For now
  }

  // Countable

  public function count() {
    return count($this->children);
  }

  // Iterator

  public function current() {
    return $this->children[$this->iteratorPosition];
  }

  public function key() {
    return $this->iteratorPosition;
  }

  public function next() {
    ++$this->iteratorPosition;
  }

  public function rewind() {
    $this->iteratorPosition = 0;
  }

  public function valid() {
    return $this->iteratorPosition < $this->count();
  }

  // Recursive iterator

  public function getChildren() {
    return $this->children[$this->iteratorPosition];
  }

  public function hasChildren() {
    return count($this->children[$this->iteratorPosition]) > 0;
  }
}