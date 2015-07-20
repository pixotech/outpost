<?php

/**
 * @package Outpost
 * @author Pixo <info@pixotech.com>
 * @copyright 2015, Pixo
 * @license http://opensource.org/licenses/NCSA NCSA
 */

namespace Outpost\Navigation;

class Menu implements \ArrayAccess, \Countable, \RecursiveIterator {

  protected $items = [];
  protected $iteratorPosition = 0;

  public static function flatten(Menu $menu) {
    return iterator_to_array($menu->allItems(), false);
  }

  public function allItems() {
    return new \RecursiveIteratorIterator($this, \RecursiveIteratorIterator::SELF_FIRST);
  }

  public function count() {
    return count($this->items);
  }

  public function current() {
    return $this->items[$this->iteratorPosition];
  }

  public function find($id) {
    /** @var ItemInterface $item */
    foreach ($this->allItems() as $item) {
      if ($item->getId() == $id) return $item;
    }
    return null;
  }

  public function getActiveItem() {
    foreach ($this->allItems() as $item) {
      if ($item->isActive()) return $item;
    }
    return null;
  }

  public function getChildren() {
    return $this->items[$this->iteratorPosition];
  }

  public function hasChildren() {
    return count($this->items[$this->iteratorPosition]) > 0;
  }

  public function items() {
    return new \IteratorIterator($this);
  }

  public function key() {
    return $this->iteratorPosition;
  }

  public function next() {
    ++$this->iteratorPosition;
  }

  public function offsetExists($offset) {
    return array_key_exists($offset, $this->items);
  }

  public function offsetGet($offset) {
    return $this->items[$offset];
  }

  public function offsetSet($offset, $value) {
    throw new \BadMethodCallException(); // For now
  }

  public function offsetUnset($offset) {
    throw new \BadMethodCallException(); // For now
  }

  public function rewind() {
    $this->iteratorPosition = 0;
  }

  public function setActiveItem($id) {
    if ($item = $this->find($id)) $item->setAsActive();
  }

  public function valid() {
    return $this->iteratorPosition < $this->count();
  }
}