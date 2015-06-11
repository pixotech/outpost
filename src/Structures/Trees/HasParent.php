<?php

/**
 * @package Outpost
 * @author Pixo <info@pixotech.com>
 * @copyright 2015, Pixo
 * @license http://opensource.org/licenses/NCSA NCSA
 */

namespace Outpost\Structures\Trees;

trait HasParent {

  protected $parent;

  /**
   * @return NodeInterface
   */
  public function getParent() {
    return $this->parent;
  }

  public function hasParent() {
    return !empty($this->parent);
  }

  public function setParent(NodeInterface $node) {
    $this->parent = $node;
  }
}