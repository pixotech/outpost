<?php

/**
 * @package Outpost
 * @author Pixo <info@pixotech.com>
 * @copyright 2015, Pixo
 * @license http://opensource.org/licenses/NCSA NCSA
 */

namespace Outpost\Structures\Trees;

class TestNode extends Node {

  public $id;
  public $iteratorPosition = 0;
  public $children = [];
  public $parent;
  public $parentId;

  public function __construct($id, $parentId = null) {
    $this->id = $id;
    if (isset($parentId)) $this->parentId = $parentId;
  }

  public function getId() {
    return $this->id;
  }

  public function getParentId() {
    return $this->parentId;
  }
}