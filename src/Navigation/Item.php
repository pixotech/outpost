<?php

/**
 * @package Outpost
 * @author Pixo <info@pixotech.com>
 * @copyright 2015, Pixo
 * @license http://opensource.org/licenses/NCSA NCSA
 */

namespace Outpost\Navigation;

abstract class Item extends Menu implements ItemInterface {

  protected $active;
  protected $id;
  protected $label;
  protected $parent;
  protected $parentId;
  protected $position = 0;
  protected $url;

  public static function makeTree(array $items) {
    $tree = [];
    /** @var Item[] $itemsById */
    $itemsById = array();
    $unsorted = $items;
    do {
      $toSort = $unsorted;
      $unsorted = [];
      $numberOfItemsSorted = 0;
      foreach ($toSort as $item) {
        if (!($item instanceof Item)) {
          throw new \UnexpectedValueException("Invalid menu item");
        }
        $itemsById[$item->id] = $item;
        if (!$item->parentId) {
          $tree[] = $itemsById[$item->id];
          static::sort($tree);
          $numberOfItemsSorted++;
        }
        elseif (isset($itemsById[$item->parentId])) {
          $itemsById[$item->id]->parent = $itemsById[$item->parentId];
          $itemsById[$item->parentId]->items[] = $itemsById[$item->id];
          static::sort($itemsById[$item->parentId]->items);
          $numberOfItemsSorted++;
        }
        else {
          $unsorted[] = $item;
        }
      }
    }
    while (!empty($unsorted) && $numberOfItemsSorted);
    return $tree;
  }

  public static function sort(array &$items) {
    usort($items, [__CLASS__, 'sortCmp']);
  }

  public static function sortCmp(ItemInterface $a, ItemInterface $b) {
    $aPos = $a->getPosition();
    $bPos = $b->getPosition();
    return $aPos == $bPos ? 0 : ($aPos < $bPos ? -1 : 1);
  }

  public function getId() {
    return $this->id;
  }

  /**
   * @return mixed
   */
  public function getLabel() {
    return $this->label;
  }

  /**
   * @return Menu
   */
  public function getParent() {
    return $this->parent;
  }

  /**
   * @return mixed
   */
  public function getParentId() {
    return $this->parentId;
  }

  public function getPosition() {
    return $this->position;
  }

  /**
   * @return mixed
   */
  public function getUrl() {
    return $this->url;
  }

  public function hasParent() {
    return !empty($this->parentId);
  }

  public function isActive() {
    return $this->active;
  }

  public function setAsActive() {
    $this->active = true;
  }

  public function setParent(ItemInterface $menu) {
    $this->parent = $menu;
  }
}